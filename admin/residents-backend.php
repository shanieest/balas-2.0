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
            handleListResidents();
            break;
        case 'account_requests':
            handleAccountRequests();
            break;
        case 'add':
            handleAddResident();
            break;
        case 'edit':
            handleEditResident();
            break;
        case 'delete':
            handleDeleteResident();
            break;
        case 'verify':
            handleVerifyResident();
            break;
        case 'process_request':
            handleProcessRequest();
            break;
        case 'export':
            handleExportResidents();
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

function handleListResidents() {
    global $conn, $response;
    
    $page = $_GET['page'] ?? 1;
    $per_page = $_GET['per_page'] ?? 10;
    $search = $_GET['search'] ?? '';
    $id = $_GET['id'] ?? null;
    
    $offset = ($page - 1) * $per_page;
    
    $query = "SELECT r.*, ra.account_status, ra.notes as account_notes, 
              ra.date_processed as account_date_processed, 
              CONCAT(a.first_name, ' ', a.last_name) as processed_by
              FROM residents r
              LEFT JOIN resident_accounts ra ON r.id = ra.resident_id
              LEFT JOIN admin_users a ON ra.processed_by = a.id";
    
    $where = [];
    $params = [];
    $types = '';
    
    if ($id) {
        $where[] = "r.id = ?";
        $params[] = $id;
        $types .= 'i';
    } else {
        $where[] = "r.verification_status = 'Verified'";
        
        if ($search) {
            $where[] = "(CONCAT(r.first_name, ' ', r.last_name) LIKE ? OR r.contact_number LIKE ? OR r.email LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'sss';
        }
    }
    
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM residents r";
    if (!empty($where)) {
        $countQuery .= " WHERE " . implode(" AND ", $where);
    }
    
    $stmt = $conn->prepare($countQuery);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'];
    
    // Get paginated data
    $query .= " ORDER BY r.last_name, r.first_name LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $residents = [];
    while ($row = $result->fetch_assoc()) {
        $residents[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $residents;
    $response['pagination'] = [
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => ceil($total / $per_page)
    ];
    
    echo json_encode($response);
}

function handleAccountRequests() {
    global $conn, $response;
    
    $page = $_GET['page'] ?? 1;
    $per_page = $_GET['per_page'] ?? 10;
    $status = $_GET['status'] ?? 'all';
    $id = $_GET['id'] ?? null;
    
    $offset = ($page - 1) * $per_page;
    
    $query = "SELECT r.*, ra.id as account_id, ra.account_status, ra.notes, 
              ra.date_processed, ra.date_requested,
              CONCAT(a.first_name, ' ', a.last_name) as processed_by
              FROM residents r
              JOIN resident_accounts ra ON r.id = ra.resident_id
              LEFT JOIN admin_users a ON ra.processed_by = a.id";
    
    $where = [];
    $params = [];
    $types = '';
    
    if ($id) {
        $where[] = "ra.id = ?";
        $params[] = $id;
        $types .= 'i';
    } else {
        if ($status !== 'all') {
            $where[] = "ra.account_status = ?";
            $params[] = $status;
            $types .= 's';
        }
    }
    
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
    
    // Get total count and pending count
    $countQuery = "SELECT 
                  COUNT(*) as total,
                  SUM(CASE WHEN ra.account_status = 'Pending' THEN 1 ELSE 0 END) as pending_count
                  FROM resident_accounts ra";
    if (!empty($where)) {
        $countQuery = str_replace("r.*, ra.id", "COUNT(*) as total, SUM(CASE WHEN ra.account_status = 'Pending' THEN 1 ELSE 0 END) as pending_count", $query);
        $countQuery = preg_replace("/ORDER BY.*$/", "", $countQuery);
        $countQuery = preg_replace("/LIMIT.*$/", "", $countQuery);
    }
    
    $stmt = $conn->prepare($countQuery);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $countResult = $stmt->get_result()->fetch_assoc();
    $total = $countResult['total'];
    $pending_count = $countResult['pending_count'] ?? 0;
    
    // Get paginated data
    $query .= " ORDER BY ra.date_requested DESC LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $requests;
    $response['pending_count'] = $pending_count;
    $response['pagination'] = [
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => ceil($total / $per_page)
    ];
    
    echo json_encode($response);
}

function handleAddResident() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['firstName', 'lastName', 'sex', 'contactNumber', 'houseNumber', 'purok', 'birthdate'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Calculate age
    $birthdate = new DateTime($data['birthdate']);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;
    
    // Generate address
    $address = "House {$data['houseNumber']}, Purok {$data['purok']}, Balas, Mexico, Pampanga, Philippines";
    
    // Insert resident
    $stmt = $conn->prepare("INSERT INTO residents 
        (first_name, last_name, middle_name, suffix, sex, birthdate, age, contact_number, email, house_number, purok, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssisssss", 
        $data['firstName'], $data['lastName'], $data['middleName'] ?? '', $data['suffix'] ?? '',
        $data['sex'], $data['birthdate'], $age, $data['contactNumber'], $data['email'] ?? '',
        $data['houseNumber'], $data['purok'], $address);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add resident: " . $stmt->error);
    }
    
    $resident_id = $stmt->insert_id;
    
    // Create account if requested
    if (!empty($data['createAccount']) && $data['createAccount'] === 'true') {
        if (empty($data['email']) || empty($data['password'])) {
            throw new Exception("Email and password are required to create an account");
        }
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO resident_accounts 
            (resident_id, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $resident_id, $data['email'], $hashed_password);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to create resident account: " . $stmt->error);
        }
    }
    
    $response['success'] = true;
    $response['message'] = 'Resident added successfully';
    echo json_encode($response);
}

function handleEditResident() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['id'])) {
        throw new Exception("Resident ID is required");
    }
    
    $required = ['firstName', 'lastName', 'sex', 'contactNumber', 'houseNumber', 'purok', 'birthdate'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Calculate age
    $birthdate = new DateTime($data['birthdate']);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;
    
    // Generate address
    $address = "House {$data['houseNumber']}, Purok {$data['purok']}, Balas, Mexico, Pampanga, Philippines";
    
    // Update resident
    $stmt = $conn->prepare("UPDATE residents SET 
        first_name = ?, last_name = ?, middle_name = ?, suffix = ?, sex = ?, 
        birthdate = ?, age = ?, contact_number = ?, email = ?, 
        house_number = ?, purok = ?, address = ?
        WHERE id = ?");
    
    $stmt->bind_param("ssssssisssssi", 
        $data['firstName'], $data['lastName'], $data['middleName'] ?? '', $data['suffix'] ?? '',
        $data['sex'], $data['birthdate'], $age, $data['contactNumber'], $data['email'] ?? '',
        $data['houseNumber'], $data['purok'], $address, $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update resident: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Resident updated successfully';
    echo json_encode($response);
}

function handleDeleteResident() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Resident ID is required");
    }
    
    $stmt = $conn->prepare("DELETE FROM residents WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete resident: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Resident deleted successfully';
    echo json_encode($response);
}

function handleVerifyResident() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id'])) {
        throw new Exception("Resident ID is required");
    }
    
    $user_id = getUserId();
    
    $stmt = $conn->prepare("UPDATE residents SET verification_status = 'Verified' WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to verify resident: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = 'Resident verified successfully';
    echo json_encode($response);
}

function handleProcessRequest() {
    global $conn, $response;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['id']) || empty($data['action'])) {
        throw new Exception("Request ID and action are required");
    }
    
    $user_id = getUserId();
    $status = $data['action'] === 'approve' ? 'Approved' : 'Disapproved';
    $note = $data['note'] ?? '';
    
    $stmt = $conn->prepare("UPDATE resident_accounts SET 
        account_status = ?, processed_by = ?, date_processed = NOW(), notes = ?
        WHERE id = ?");
    $stmt->bind_param("sisi", $status, $user_id, $note, $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to process request: " . $stmt->error);
    }
    
    $response['success'] = true;
    $response['message'] = "Request {$data['action']}d successfully";
    echo json_encode($response);
}

function handleExportResidents() {
    global $conn;
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="residents_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Header row
    fputcsv($output, [
        'ID', 'First Name', 'Last Name', 'Middle Name', 'Suffix', 'Sex', 'Birthdate', 'Age',
        'Contact Number', 'Email', 'House Number', 'Purok', 'Address', 'Verification Status',
        'Resident Status', 'Date Created', 'Date Updated'
    ]);
    
    // Data rows
    $query = "SELECT * FROM residents ORDER BY last_name, first_name";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['first_name'],
            $row['last_name'],
            $row['middle_name'],
            $row['suffix'],
            $row['sex'],
            $row['birthdate'],
            $row['age'],
            $row['contact_number'],
            $row['email'],
            $row['house_number'],
            $row['purok'],
            $row['address'],
            $row['verification_status'],
            $row['resident_status'],
            $row['created_at'],
            $row['updated_at']
        ]);
    }
    
    fclose($output);
    exit();
}
?>