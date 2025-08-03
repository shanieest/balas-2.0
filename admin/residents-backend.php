<?php
ob_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

ini_set('display_errors', 0);
error_reporting(0);

$response = ['success' => false, 'message' => ''];

try {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access', 401);
    }

    if ($conn->connect_error) {
        throw new Exception('Database connection failed', 500);
    }

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
                $stmt = $conn->prepare("SELECT r.*, a.username, a.status as account_status, 
                                      a.id_file as valid_id_path, a.registration_date as date_requested
                                      FROM resident_accounts r
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
                $offset = ($page - 1) * $perPage;
                $searchTerm = "%$search%";
                
                $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM resident_accounts r
                                           WHERE (r.first_name LIKE ? OR r.last_name LIKE ? OR r.phone LIKE ?)");
                $countStmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
                $countStmt->execute();
                $total = $countStmt->get_result()->fetch_assoc()['total'];
                
                $stmt = $conn->prepare("SELECT r.* 
                                      FROM resident_accounts r
                                      WHERE (r.first_name LIKE ? OR r.last_name LIKE ? OR r.phone LIKE ?)
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
                $stmt = $conn->prepare("SELECT r.* 
                                      FROM resident_accounts r
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
                $offset = ($page - 1) * $perPage;
                $statusFilter = $status === 'all' ? '%' : $status;
                
                $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM resident_accounts r
                                           WHERE r.status LIKE ?");
                $countStmt->bind_param("s", $statusFilter);
                $countStmt->execute();
                $total = $countStmt->get_result()->fetch_assoc()['total'];
                
                $stmt = $conn->prepare("SELECT r.id, r.first_name, r.last_name, r.email, r.phone as contact_number,
                                      r.status as account_status, r.registration_date as date_requested
                                      FROM resident_accounts r
                                      WHERE r.status LIKE ?
                                      ORDER BY r.status, r.registration_date DESC
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
            // Required fields validation
            $required = ['firstName', 'lastName', 'sex', 'birthdate', 'houseNumber', 'purok', 'contactNumber'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field", 400);
                }
            }

            // Sanitize inputs
            $firstName = $conn->real_escape_string(trim($_POST['firstName']));
            $middleName = isset($_POST['middleName']) ? $conn->real_escape_string(trim($_POST['middleName'])) : '';
            $lastName = $conn->real_escape_string(trim($_POST['lastName']));
            $suffix = isset($_POST['suffix']) ? $conn->real_escape_string(trim($_POST['suffix'])) : '';
            $sex = in_array($_POST['sex'], ['male', 'female']) ? $_POST['sex'] : '';
            $birthdate = $_POST['birthdate'];
            $houseNumber = $conn->real_escape_string(trim($_POST['houseNumber']));
            $purok = $conn->real_escape_string(trim($_POST['purok']));
            $address = "House $houseNumber, Purok $purok, Balas, Mexico, Pampanga, Philippines";
            $contactNumber = preg_replace('/[^0-9]/', '', $_POST['contactNumber']);
            $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';

            // Validate fields
            if (empty($sex)) throw new Exception('Invalid sex value', 400);
            if (!DateTime::createFromFormat('Y-m-d', $birthdate)) throw new Exception('Invalid birthdate format', 400);
            if (strlen($contactNumber) < 10 || strlen($contactNumber) > 15) throw new Exception('Contact number must be 10-15 digits', 400);

            // Calculate age
            $today = new DateTime();
            $birthDate = new DateTime($birthdate);
            if ($birthDate > $today) throw new Exception('Birthdate cannot be in the future', 400);
            $age = $today->diff($birthDate)->y;

            // Insert resident
            $stmt = $conn->prepare("INSERT INTO resident_accounts 
                (first_name, middle_name, last_name, suffix, sex, birthdate, age, 
                email, phone, house_no, purok, full_address, registration_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')");
            $stmt->bind_param("ssssssisssss", 
                $firstName, $middleName, $lastName, $suffix, $sex, $birthdate, $age,
                $email, $contactNumber, $houseNumber, $purok, $address);

            if ($stmt->execute()) {
                $residentId = $stmt->insert_id;

                // Handle account creation if requested
                if (isset($_POST['createAccount'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    
                    // In your case, the account is part of the same table
                    $updateStmt = $conn->prepare("UPDATE resident_accounts SET 
                        password = ?, status = 'Approved' WHERE id = ?");
                    $updateStmt->bind_param("si", $password, $residentId);
                    
                    if (!$updateStmt->execute()) {
                        throw new Exception('Failed to create account: ' . $updateStmt->error, 500);
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
            $checkStmt = $conn->prepare("SELECT id FROM resident_accounts WHERE id = ?");
            $checkStmt->bind_param("i", $residentId);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows === 0) {
                throw new Exception('Resident not found', 404);
            }

            // Sanitize inputs
            $firstName = $conn->real_escape_string(trim($_POST['firstName']));
            $middleName = isset($_POST['middleName']) ? $conn->real_escape_string(trim($_POST['middleName'])) : '';
            $lastName = $conn->real_escape_string(trim($_POST['lastName']));
            $suffix = isset($_POST['suffix']) ? $conn->real_escape_string(trim($_POST['suffix'])) : '';
            $sex = in_array($_POST['sex'], ['male', 'female']) ? $_POST['sex'] : '';
            $birthdate = $_POST['birthdate'];
            $houseNumber = $conn->real_escape_string(trim($_POST['houseNumber']));
            $purok = $conn->real_escape_string(trim($_POST['purok']));
            $address = "House $houseNumber, Purok $purok, Balas, Mexico, Pampanga, Philippines";
            $contactNumber = preg_replace('/[^0-9]/', '', $_POST['contactNumber']);
            $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';

            // Validate fields
            if (empty($sex)) throw new Exception('Invalid sex value', 400);
            if (!DateTime::createFromFormat('Y-m-d', $birthdate)) throw new Exception('Invalid birthdate format', 400);
            if (strlen($contactNumber) < 10 || strlen($contactNumber) > 15) throw new Exception('Contact number must be 10-15 digits', 400);

            // Calculate age
            $today = new DateTime();
            $birthDate = new DateTime($birthdate);
            if ($birthDate > $today) throw new Exception('Birthdate cannot be in the future', 400);
            $age = $today->diff($birthDate)->y;

            // Update resident
            $stmt = $conn->prepare("UPDATE resident_accounts SET 
                first_name = ?, middle_name = ?, last_name = ?, suffix = ?, 
                sex = ?, birthdate = ?, age = ?, email = ?, phone = ?,
                house_no = ?, purok = ?, full_address = ?
                WHERE id = ?");
            $stmt->bind_param("ssssssissssi", 
                $firstName, $middleName, $lastName, $suffix, $sex, $birthdate, $age,
                $email, $contactNumber, $houseNumber, $purok, $address, $residentId);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Resident updated successfully';
            } else {
                throw new Exception("Failed to update resident: " . $stmt->error, 500);
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
                                   SET status = ?, processed_by = ?, date_processed = NOW(), notes = ?
                                   WHERE id = ?");
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
            $stmt = $conn->prepare("DELETE FROM resident_accounts WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Resident deleted successfully';
            } else {
                throw new Exception("Failed to delete resident: " . $stmt->error, 500);
            }
            break;

        case 'export':
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="residents_' . date('Y-m-d') . '.xls"');

            $result = $conn->query("SELECT * FROM resident_accounts ORDER BY last_name, first_name");

            echo "ID\tFirst Name\tMiddle Name\tLast Name\tSuffix\tSex\tBirthdate\tAge\tEmail\tPhone\tHouse No\tPurok\tAddress\tStatus\tRegistration Date\n";

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

ob_end_clean();
echo json_encode($response);
exit();