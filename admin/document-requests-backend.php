<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireAuth();

// Only allow AJAX requests for backend files
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access not allowed');
}

header('Content-Type: application/json');

// Include PHPWord library for document generation
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    switch ($action) {
        case 'get_requests':
            $status = $_GET['status'] ?? null;
            $type = $_GET['type'] ?? null;
            
            $query = "SELECT r.*, res.first_name, res.last_name, res.address, res.contact_number, 
                             res.email, u.username as processed_by_name
                      FROM document_requests r
                      JOIN residents res ON r.resident_id = res.id
                      LEFT JOIN admin_users u ON r.processed_by = u.id";
            
            $conditions = [];
            $params = [];
            $types = '';
            
            if ($status) {
                $conditions[] = "r.status = ?";
                $params[] = $status;
                $types .= 's';
            }
            
            if ($type) {
                $conditions[] = "r.document_type = ?";
                $params[] = $type;
                $types .= 's';
            }
            
            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $query .= " ORDER BY r.date_requested DESC";
            
            $stmt = $conn->prepare($query);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['success'] = true;
            break;
            
        case 'get_request':
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT r.*, res.first_name, res.middle_name, res.last_name, 
                                   res.address, res.contact_number, res.email, res.birthdate,
                                   u.username as processed_by_name
                                   FROM document_requests r
                                   JOIN residents res ON r.resident_id = res.id
                                   LEFT JOIN admin_users u ON r.processed_by = u.id
                                   WHERE r.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $response['data'] = $result->fetch_assoc();
                $response['success'] = true;
            } else {
                $response['message'] = 'Request not found';
            }
            break;
            
        case 'process_request':
            $id = $_POST['id'];
            $status = $_POST['status'];
            $notes = $conn->real_escape_string($_POST['notes'] ?? '');
            
            $stmt = $conn->prepare("UPDATE document_requests 
                                   SET status = ?, processed_by = ?, date_processed = NOW(), notes = ?
                                   WHERE id = ?");
            $stmt->bind_param("sisi", $status, getUserId(), $notes, $id);
            
            if ($stmt->execute()) {
                // Log activity
                $action = $status == 'Approved' ? 'Approved' : 'Disapproved';
                logActivity($conn, getUserId(), "$action document request ID $id");
                
                $response['success'] = true;
                $response['message'] = "Request $status successfully";
                
                // If approved, generate the document
                if ($status == 'Approved') {
                    $response['document_url'] = generateDocument($conn, $id);
                }
            } else {
                throw new Exception("Failed to process request: " . $stmt->error);
            }
            break;
            
        case 'export':
            $type = $_GET['type'] ?? 'all';
            
            $query = "SELECT r.id, r.document_type, r.status, r.date_requested, r.date_processed,
                             CONCAT(res.first_name, ' ', res.last_name) as resident_name,
                             res.address, res.contact_number, res.email,
                             u.username as processed_by
                      FROM document_requests r
                      JOIN residents res ON r.resident_id = res.id
                      LEFT JOIN admin_users u ON r.processed_by = u.id";
            
            if ($type != 'all') {
                $query .= " WHERE r.document_type = '$type'";
            }
            
            $query .= " ORDER BY r.date_requested DESC";
            
            $result = $conn->query($query);
            $data = $result->fetch_all(MYSQLI_ASSOC);
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="document_requests_' . $type . '_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Header row
            fputcsv($output, array_keys($data[0]));
            
            // Data rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit();
            
        default:
            $response['message'] = 'Invalid action';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

// Function to generate document (Word format)
function generateDocument($conn, $request_id) {
    $stmt = $conn->prepare("SELECT r.*, res.*, u.username as processed_by_name
                           FROM document_requests r
                           JOIN residents res ON r.resident_id = res.id
                           LEFT JOIN admin_users u ON r.processed_by = u.id
                           WHERE r.id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    
    // Add logo
    $section->addImage('assets/img/balas-logo.png', [
        'width' => 100,
        'height' => 100,
        'alignment' => 'center'
    ]);
    
    // Document title
    $section->addText(strtoupper($request['document_type']), [
        'name' => 'Arial',
        'size' => 16,
        'bold' => true
    ], ['alignment' => 'center']);
    
    $section->addTextBreak(2);
    
    // Document content based on type
    switch ($request['document_type']) {
        case 'Barangay Clearance':
            $content = "TO WHOM IT MAY CONCERN:\n\n";
            $content .= "This is to certify that {$request['first_name']} {$request['middle_name']} {$request['last_name']}, ";
            $content .= "of legal age, {$request['civil_status']}, Filipino, and a resident of {$request['address']}, ";
            $content .= "Barangay Balas, is known to be of good moral character and has no derogatory record in this barangay.\n\n";
            $content .= "This certification is issued upon the request of {$request['first_name']} {$request['last_name']} ";
            $content .= "for {$request['purpose']}.\n\n";
            $content .= "Issued this " . date('jS \d\a\y \o\f F, Y') . " at Barangay Balas.\n\n";
            $content .= "Certified by:\n\n\n";
            $content .= "___________________________\n";
            $content .= "Barangay Captain\n";
            $content .= "Barangay Balas";
            break;
            
        case 'Certificate of Residency':
            $content = "CERTIFICATE OF RESIDENCY\n\n";
            $content .= "TO WHOM IT MAY CONCERN:\n\n";
            $content .= "This is to certify that {$request['first_name']} {$request['middle_name']} {$request['last_name']}, ";
            $content .= "of legal age, is a bona fide resident of {$request['address']}, Barangay Balas.\n\n";
            $content .= "This certification is issued upon the request of the above-named person ";
            $content .= "for {$request['purpose']}.\n\n";
            $content .= "Issued this " . date('jS \d\a\y \o\f F, Y') . " at Barangay Balas.\n\n";
            $content .= "Certified by:\n\n\n";
            $content .= "___________________________\n";
            $content .= "Barangay Captain\n";
            $content .= "Barangay Balas";
            break;
            
        // Add other document types as needed
            
        default:
            $content = "DOCUMENT\n\n";
            $content .= "This is a certification issued by Barangay Balas.\n\n";
            $content .= "Issued this " . date('jS \d\a\y \o\f F, Y') . " at Barangay Balas.\n\n";
            $content .= "Certified by:\n\n\n";
            $content .= "___________________________\n";
            $content .= "Barangay Captain\n";
            $content .= "Barangay Balas";
    }
    
    $section->addText($content, [
        'name' => 'Arial',
        'size' => 12
    ]);
    
    // Save document
    $filename = "document_{$request['document_type']}_{$request_id}.docx";
    $filepath = "assets/documents/$filename";
    
    if (!is_dir('assets/documents')) {
        mkdir('assets/documents', 0777, true);
    }
    
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($filepath);
    
    return $filepath;
}
?>