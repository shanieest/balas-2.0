<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireAuth();

// Only allow AJAX requests for backend
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access not allowed');
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    switch ($action) {
        case 'get_officials':
            $result = $conn->query("SELECT * FROM barangay_officials ORDER BY 
                                  CASE position 
                                    WHEN 'Barangay Captain' THEN 1
                                    WHEN 'Barangay Secretary' THEN 2
                                    WHEN 'Barangay Treasurer' THEN 3
                                    WHEN 'Barangay Kagawad' THEN 4
                                    WHEN 'SK Chairman' THEN 5
                                    ELSE 6
                                  END, last_name, first_name");
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['success'] = true;
            break;
            
        case 'get_official':
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM barangay_officials WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $response['data'] = $result->fetch_assoc();
                $response['success'] = true;
            } else {
                $response['message'] = 'Official not found';
            }
            break;
            
        case 'add_official':
            $data = json_decode(file_get_contents('php://input'), true);
            
            $firstName = $conn->real_escape_string($data['first_name']);
            $middleName = $conn->real_escape_string($data['middle_name'] ?? '');
            $lastName = $conn->real_escape_string($data['last_name']);
            $position = $conn->real_escape_string($data['position']);
            $email = $conn->real_escape_string($data['email']);
            $contact = $conn->real_escape_string($data['contact_number'] ?? '');
            $status = $conn->real_escape_string($data['status']);
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM barangay_officials WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("Email address already exists");
            }
            
            // Insert official
            $stmt = $conn->prepare("INSERT INTO barangay_officials 
                                   (first_name, middle_name, last_name, position, email, contact_number, status) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $firstName, $middleName, $lastName, $position, $email, $contact, $status);
            
            if ($stmt->execute()) {
                $officialId = $stmt->insert_id;
                
                // Also add as admin user
                $stmt = $conn->prepare("INSERT INTO admin_users 
                                       (username, password, first_name, last_name, email, position, contact_number) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                $username = strtolower($firstName[0] . $lastName);
                $stmt->bind_param("sssssss", $username, $password, $firstName, $lastName, $email, $position, $contact);
                $stmt->execute();
                
                // Log activity
                logActivity($conn, getUserId(), "Added new barangay official: $firstName $lastName");
                
                $response['success'] = true;
                $response['message'] = 'Official added successfully';
            } else {
                throw new Exception("Failed to add official: " . $stmt->error);
            }
            break;
            
        case 'update_official':
            $data = json_decode(file_get_contents('php://input'), true);
            
            $id = $data['id'];
            $firstName = $conn->real_escape_string($data['first_name']);
            $middleName = $conn->real_escape_string($data['middle_name'] ?? '');
            $lastName = $conn->real_escape_string($data['last_name']);
            $position = $conn->real_escape_string($data['position']);
            $email = $conn->real_escape_string($data['email']);
            $contact = $conn->real_escape_string($data['contact_number'] ?? '');
            $status = $conn->real_escape_string($data['status']);
            
            // Check if email already exists for another official
            $stmt = $conn->prepare("SELECT id FROM barangay_officials WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $id);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("Email address already exists for another official");
            }
            
            // Update official
            $stmt = $conn->prepare("UPDATE barangay_officials 
                                   SET first_name = ?, middle_name = ?, last_name = ?, position = ?, 
                                       email = ?, contact_number = ?, status = ? 
                                   WHERE id = ?");
            $stmt->bind_param("sssssssi", $firstName, $middleName, $lastName, $position, 
                             $email, $contact, $status, $id);
            
            if ($stmt->execute()) {
                // Update password if provided
                if (!empty($data['password'])) {
                    $password = password_hash($data['password'], PASSWORD_DEFAULT);
                    $conn->query("UPDATE admin_users SET password = '$password' WHERE email = '$email'");
                }
                
                // Log activity
                logActivity($conn, getUserId(), "Updated barangay official ID $id");
                
                $response['success'] = true;
                $response['message'] = 'Official updated successfully';
            } else {
                throw new Exception("Failed to update official: " . $stmt->error);
            }
            break;
            
        case 'delete_official':
            $id = $_GET['id'];
            
            // Get official data first
            $stmt = $conn->prepare("SELECT email FROM barangay_officials WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $official = $result->fetch_assoc();
                $email = $official['email'];
                
                // Delete from admin users
                $conn->query("DELETE FROM admin_users WHERE email = '$email'");
                
                // Delete official
                $stmt = $conn->prepare("DELETE FROM barangay_officials WHERE id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    // Log activity
                    logActivity($conn, getUserId(), "Deleted barangay official ID $id");
                    
                    $response['success'] = true;
                    $response['message'] = 'Official deleted successfully';
                } else {
                    throw new Exception("Failed to delete official: " . $stmt->error);
                }
            } else {
                throw new Exception("Official not found");
            }
            break;
            
        default:
            $response['message'] = 'Invalid action';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>