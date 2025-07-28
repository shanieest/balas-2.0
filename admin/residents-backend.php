<?php
// Start output buffering to prevent any accidental output
ob_start();

// Set headers first
header('Content-Type: application/json');

// Use absolute paths for includes
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

// Error reporting - disable in production
ini_set('display_errors', 0);
error_reporting(0);

// Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    // Check authentication
    session_start();
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access', 401);
    }

    // Get database connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed', 500);
    }

    // Get action parameter
    $action = $_GET['action'] ?? '';
    if (empty($action)) {
        throw new Exception('No action specified', 400);
    }

    switch ($action) {
        case 'list':
            $id = $_GET['id'] ?? null;
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = max(1, intval($_GET['per_page'] ?? 10));
            $search = trim($_GET['search'] ?? '');

            if ($id) {
                // Get single resident
                $stmt = $conn->prepare("SELECT r.*, a.username, a.account_status, a.notes as account_notes, 
                                      a.date_processed as account_date_processed, u.username as account_processed_by
                                      FROM residents r
                                      LEFT JOIN resident_accounts a ON r.id = a.resident_id
                                      LEFT JOIN admin_users u ON a.processed_by = u.id
                                      WHERE r.id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $response['data'] = $result->fetch_assoc();
                    $response['success'] = true;
                } else {
                    throw new Exception('Resident not found', 404);
                }
            } else {
                // Get paginated residents
                $offset = ($page - 1) * $perPage;
                $searchTerm = "%$search%";
                
                // Count total
                $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM residents r
                                           WHERE (r.first_name LIKE ? OR r.last_name LIKE ? OR r.contact_number LIKE ?)");
                $countStmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
                $countStmt->execute();
                $total = $countStmt->get_result()->fetch_assoc()['total'];
                
                // Get data
                $stmt = $conn->prepare("SELECT r.*, a.account_status 
                                      FROM residents r
                                      LEFT JOIN resident_accounts a ON r.id = a.resident_id
                                      WHERE (r.first_name LIKE ? OR r.last_name LIKE ? OR r.contact_number LIKE ?)
                                      ORDER BY r.last_name, r.first_name
                                      LIMIT ? OFFSET ?");
                $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $perPage, $offset);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
                $response['pagination'] = [
                    'total' => $total,
                    'page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => ceil($total / $perPage)
                ];
                $response['success'] = true;
            }
            break;

        case 'account_requests':
            $id = $_GET['id'] ?? null;
            $status = in_array($_GET['status'] ?? '', ['Pending', 'Approved', 'Disapproved']) ? $_GET['status'] : 'all';
            $page = max(1, intval($_GET['page'] ?? 1));
            $perPage = max(1, intval($_GET['per_page'] ?? 10));

            if ($id) {
                // Get single request
                $stmt = $conn->prepare("SELECT r.*, a.username, a.account_status, a.notes, 
                                      a.date_processed, u.username as processed_by
                                      FROM residents r
                                      JOIN resident_accounts a ON r.id = a.resident_id
                                      LEFT JOIN admin_users u ON a.processed_by = u.id
                                      WHERE r.id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $response['data'] = $result->fetch_assoc();
                    $response['success'] = true;
                } else {
                    throw new Exception('Request not found', 404);
                }
            } else {
                // Get paginated requests
                $offset = ($page - 1) * $perPage;
                $statusFilter = $status === 'all' ? '%' : $status;
                
                // Count total
                $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM residents r
                                           JOIN resident_accounts a ON r.id = a.resident_id
                                           WHERE a.account_status LIKE ?");
                $countStmt->bind_param("s", $statusFilter);
                $countStmt->execute();
                $total = $countStmt->get_result()->fetch_assoc()['total'];
                
                // Get data
                $stmt = $conn->prepare("SELECT r.id, r.first_name, r.last_name, r.email, r.contact_number, 
                                      a.username, a.account_status, a.date_processed, 
                                      u.username as processed_by, a.date_processed as date_requested
                                      FROM residents r
                                      JOIN resident_accounts a ON r.id = a.resident_id
                                      LEFT JOIN admin_users u ON a.processed_by = u.id
                                      WHERE a.account_status LIKE ?
                                      ORDER BY a.account_status, a.date_processed DESC
                                      LIMIT ? OFFSET ?");
                $stmt->bind_param("sii", $statusFilter, $perPage, $offset);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
                $response['pagination'] = [
                    'total' => $total,
                    'page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => ceil($total / $perPage)
                ];
                $response['success'] = true;
            }
            break;

        case 'add':
            $required = ['firstName', 'lastName', 'sex', 'civilStatus', 'birthdate', 'address', 'contactNumber'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field", 400);
                }
            }

            // Validate and sanitize inputs
            $firstName = $conn->real_escape_string(trim($_POST['firstName']));
            $middleName = isset($_POST['middleName']) ? $conn->real_escape_string(trim($_POST['middleName'])) : '';
            $lastName = $conn->real_escape_string(trim($_POST['lastName']));
            $suffix = isset($_POST['suffix']) ? $conn->real_escape_string(trim($_POST['suffix'])) : '';
            $sex = in_array($_POST['sex'], ['male', 'female']) ? $_POST['sex'] : '';
            $civilStatus = in_array($_POST['civilStatus'], ['Single', 'Married', 'Widowed', 'Separated', 'Divorced']) ? $_POST['civilStatus'] : '';
            $birthdate = $_POST['birthdate'];
            $address = $conn->real_escape_string(trim($_POST['address']));
            $contactNumber = preg_replace('/[^0-9]/', '', $_POST['contactNumber']);
            $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';

            // Validate required fields
            if (empty($sex)) throw new Exception('Invalid sex value', 400);
            if (empty($civilStatus)) throw new Exception('Invalid civil status value', 400);
            if (!DateTime::createFromFormat('Y-m-d', $birthdate)) throw new Exception('Invalid birthdate format', 400);
            if (strlen($contactNumber) < 10 || strlen($contactNumber) > 15) throw new Exception('Contact number must be 10-15 digits', 400);

            // Calculate age
            $today = new DateTime();
            $birthDate = new DateTime($birthdate);
            if ($birthDate > $today) {
                throw new Exception('Birthdate cannot be in the future', 400);
            }
            $age = $today->diff($birthDate)->y;

            // Insert resident
            $stmt = $conn->prepare("INSERT INTO residents (first_name, middle_name, last_name, suffix, sex, civil_status, 
                                  birthdate, age, address, contact_number, email, verification_status) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Verified')");
            $stmt->bind_param("sssssssisss", $firstName, $middleName, $lastName, $suffix, $sex, $civilStatus, 
                             $birthdate, $age, $address, $contactNumber, $email);

            if ($stmt->execute()) {
                $residentId = $stmt->insert_id;

                // Create account if requested
                if (isset($_POST['createAccount']) && $_POST['createAccount'] === 'true') {
                    if (empty($_POST['username']) || empty($_POST['password'])) {
                        throw new Exception('Username and password are required when creating an account', 400);
                    }

                    $username = $conn->real_escape_string(trim($_POST['username']));
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO resident_accounts (resident_id, username, password, account_status) 
                                          VALUES (?, ?, ?, 'Approved')");
                    $stmt->bind_param("iss", $residentId, $username, $password);
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to create resident account: ' . $stmt->error, 500);
                    }
                }

                $response['success'] = true;
                $response['message'] = 'Resident added successfully';
                $response['residentId'] = $residentId;
            } else {
                throw new Exception("Failed to add resident: " . $stmt->error, 500);
            }
            break;

        case 'edit':
            if (empty($_POST['id'])) {
                throw new Exception('Resident ID is required', 400);
            }

            $residentId = intval($_POST['id']);
            
            // Check if resident exists
            $checkStmt = $conn->prepare("SELECT id FROM residents WHERE id = ?");
            $checkStmt->bind_param("i", $residentId);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows === 0) {
                throw new Exception('Resident not found', 404);
            }

            // Validate and sanitize inputs
            $firstName = $conn->real_escape_string(trim($_POST['firstName']));
            $middleName = isset($_POST['middleName']) ? $conn->real_escape_string(trim($_POST['middleName'])) : '';
            $lastName = $conn->real_escape_string(trim($_POST['lastName']));
            $suffix = isset($_POST['suffix']) ? $conn->real_escape_string(trim($_POST['suffix'])) : '';
            $sex = in_array($_POST['sex'], ['male', 'female']) ? $_POST['sex'] : '';
            $civilStatus = in_array($_POST['civilStatus'], ['Single', 'Married', 'Widowed', 'Separated', 'Divorced']) ? $_POST['civilStatus'] : '';
            $birthdate = $_POST['birthdate'];
            $address = $conn->real_escape_string(trim($_POST['address']));
            $contactNumber = preg_replace('/[^0-9]/', '', $_POST['contactNumber']);
            $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';

            // Validate required fields
            if (empty($sex)) throw new Exception('Invalid sex value', 400);
            if (empty($civilStatus)) throw new Exception('Invalid civil status value', 400);
            if (!DateTime::createFromFormat('Y-m-d', $birthdate)) throw new Exception('Invalid birthdate format', 400);
            if (strlen($contactNumber) < 10 || strlen($contactNumber) > 15) throw new Exception('Contact number must be 10-15 digits', 400);

            // Calculate age
            $today = new DateTime();
            $birthDate = new DateTime($birthdate);
            if ($birthDate > $today) {
                throw new Exception('Birthdate cannot be in the future', 400);
            }
            $age = $today->diff($birthDate)->y;

            // Update resident
            $stmt = $conn->prepare("UPDATE residents SET 
                                  first_name = ?, middle_name = ?, last_name = ?, suffix = ?, 
                                  sex = ?, civil_status = ?, birthdate = ?, age = ?, 
                                  address = ?, contact_number = ?, email = ?
                                  WHERE id = ?");
            $stmt->bind_param("sssssssisssi", $firstName, $middleName, $lastName, $suffix, 
                             $sex, $civilStatus, $birthdate, $age, $address, $contactNumber, 
                             $email, $residentId);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Resident updated successfully';
            } else {
                throw new Exception("Failed to update resident: " . $stmt->error, 500);
            }
            break;

        case 'verify':
            if (empty($_POST['id'])) {
                throw new Exception('Resident ID is required', 400);
            }

            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE residents SET verification_status = 'Verified' WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Resident verified successfully';
            } else {
                throw new Exception("Failed to verify resident: " . $stmt->error, 500);
            }
            break;

        case 'process_request':
            if (empty($_POST['id']) || empty($_POST['action'])) {
                throw new Exception('Request ID and action are required', 400);
            }

            $id = intval($_POST['id']);
            $action = $_POST['action'] === 'approve' ? 'approve' : 'reject';
            $note = isset($_POST['note']) ? $conn->real_escape_string(trim($_POST['note'])) : '';
            $status = $action === 'approve' ? 'Approved' : 'Disapproved';

            if ($action === 'reject' && empty($note)) {
                throw new Exception('A rejection reason is required', 400);
            }

            $stmt = $conn->prepare("UPDATE resident_accounts 
                                   SET account_status = ?, processed_by = ?, date_processed = NOW(), notes = ?
                                   WHERE resident_id = ?");
            $stmt->bind_param("sisi", $status, $_SESSION['user_id'], $note, $id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Account request $action successfully";
            } else {
                throw new Exception("Failed to process request: " . $stmt->error, 500);
            }
            break;

        case 'delete':
            if (empty($_POST['id'])) {
                throw new Exception('Resident ID is required', 400);
            }

            $id = intval($_POST['id']);
            
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Delete related account first
                $stmt = $conn->prepare("DELETE FROM resident_accounts WHERE resident_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                
                // Then delete resident
                $stmt = $conn->prepare("DELETE FROM residents WHERE id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $conn->commit();
                    $response['success'] = true;
                    $response['message'] = 'Resident deleted successfully';
                } else {
                    throw new Exception("Failed to delete resident: " . $stmt->error, 500);
                }
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
            break;

        case 'export':
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="residents_' . date('Y-m-d') . '.xls"');

            $result = $conn->query("SELECT * FROM residents ORDER BY last_name, first_name");

            echo "ID\tFirst Name\tMiddle Name\tLast Name\tSuffix\tSex\tCivil Status\tBirthdate\tAge\tAddress\tContact\tEmail\tVerification Status\tResident Status\n";

            while ($row = $result->fetch_assoc()) {
                echo implode("\t", array_values($row)) . "\n";
            }
            exit();

        default:
            throw new Exception('Invalid action', 400);
    }

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    $response['message'] = $e->getMessage();
    $response['code'] = $e->getCode();
}

// Clean any output and send JSON
ob_end_clean();
echo json_encode($response);
exit();