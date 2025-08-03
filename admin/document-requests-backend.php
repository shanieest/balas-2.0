<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    $action = $_GET['action'] ?? '';
    $user_id = getUserId();

    switch ($action) {
        case 'list':
            handleListRequests();
            break;
        case 'approve':
            handleApproveRequest($user_id);
            break;
        case 'reject':
            handleRejectRequest($user_id);
            break;
        case 'export':
            handleExportRequests();
            break;
        default:
            $response['message'] = 'Invalid action';
            echo json_encode($response);
            exit();
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
    exit();
}

function handleListRequests() {
    global $conn, $response;
    
    $status = $_GET['status'] ?? 'Pending';
    $id = $_GET['id'] ?? null;
    
    $query = "SELECT dr.*, 
              CONCAT(r.first_name, ' ', r.last_name) as resident_name,
              r.contact_number, r.email, r.address,
              CONCAT(a.first_name, ' ', a.last_name) as processed_by
              FROM document_requests dr
              JOIN residents r ON dr.resident_id = r.id
              LEFT JOIN admin_users a ON dr.processed_by = a.id";
    
    $where = [];
    $params = [];
    $types = '';
    
    if ($id) {
        $where[] = "dr.id = ?";
        $params[] = $id;
        $types .= 'i';
    } else {
        $where[] = "dr.status = ?";
        $params[] = $status;
        $types .= 's';
    }
    
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
    
    $query .= " ORDER BY dr.date_requested DESC";
    
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    
    // Get counts for each status
    $countQuery = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                  SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved,
                  SUM(CASE WHEN status = 'Disapproved' THEN 1 ELSE 0 END) as disapproved
                  FROM document_requests";
    
    $countResult = $conn->query($countQuery)->fetch_assoc();
    
    $response['success'] = true;
    $response['data'] = $requests;
    $response['counts'] = $countResult;
    echo json_encode($response);
}

function handleApproveRequest($user_id) {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Request ID is required");
    }
    
    $note = $data['note'] ?? '';
    
    $stmt = $conn->prepare("UPDATE document_requests SET 
        status = 'Approved', processed_by = ?, date_processed = NOW(), notes = ?
        WHERE id = ?");
    $stmt->bind_param("isi", $user_id, $note, $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to approve request: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Request approved successfully';
    echo json_encode($response);
}

function handleRejectRequest($user_id) {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id']) || empty($data['reason'])) {
        throw new Exception("Request ID and reason are required");
    }
    
    $stmt = $conn->prepare("UPDATE document_requests SET 
        status = 'Disapproved', processed_by = ?, date_processed = NOW(), notes = ?
        WHERE id = ?");
    $stmt->bind_param("isi", $user_id, $data['reason'], $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to reject request: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Request rejected successfully';
    echo json_encode($response);
}

function handleExportRequests() {
    global $conn;
    
    $type = $_GET['type'] ?? 'all';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="document_requests_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Header row
    fputcsv($output, [
        'Request ID', 'Resident Name', 'Document Type', 'Purpose', 'Status',
        'Date Requested', 'Date Processed', 'Processed By', 'Notes'
    ]);
    
    // Data rows
    $query = "SELECT dr.request_number, 
              CONCAT(r.first_name, ' ', r.last_name) as resident_name,
              dr.document_type, dr.purpose, dr.status, dr.date_requested,
              dr.date_processed, CONCAT(a.first_name, ' ', a.last_name) as processed_by,
              dr.notes
              FROM document_requests dr
              JOIN residents r ON dr.resident_id = r.id
              LEFT JOIN admin_users a ON dr.processed_by = a.id";
    
    if ($type !== 'all') {
        $query .= " WHERE dr.document_type = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($query);
    }
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['request_number'],
            $row['resident_name'],
            $row['document_type'],
            $row['purpose'],
            $row['status'],
            $row['date_requested'],
            $row['date_processed'] ?? '',
            $row['processed_by'] ?? '',
            $row['notes'] ?? ''
        ]);
    }
    
    fclose($output);
    exit();
}
?>