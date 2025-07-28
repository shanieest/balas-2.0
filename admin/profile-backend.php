<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireAuth();

// Only allow AJAX requests for backend files
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('HTTP/1.0 403 Forbidden');
    die('Direct access not allowed');
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    $user_id = getUserId();
    
    switch ($action) {
        case 'get_profile':
            $stmt = $conn->prepare("SELECT id, username, first_name, last_name, email, position, contact_number FROM admin_users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $response['data'] = $result->fetch_assoc();
                $response['success'] = true;
            } else {
                $response['message'] = 'User not found';
            }
            break;
            
        case 'update_profile':
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $contact = trim($_POST['contact_number'] ?? '');
            
            // Validate inputs
            if (empty($firstName) || empty($lastName) || empty($email)) {
                throw new Exception("All required fields must be filled");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            
            // Check if email exists for another user
            $stmt = $conn->prepare("SELECT id FROM admin_users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $user_id);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("Email address already exists");
            }
            
            $stmt = $conn->prepare("UPDATE admin_users 
                                   SET first_name = ?, last_name = ?, email = ?, contact_number = ?, updated_at = NOW()
                                   WHERE id = ?");
            $stmt->bind_param("ssssi", $firstName, $lastName, $email, $contact, $user_id);
            
            if ($stmt->execute()) {
                // Update session name
                $_SESSION['user_first_name'] = $firstName;
                $_SESSION['user_last_name'] = $lastName;
                
                $response['success'] = true;
                $response['message'] = 'Profile updated successfully';
            } else {
                throw new Exception("Failed to update profile: " . $stmt->error);
            }
            break;
            
        case 'update_password':
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if (empty($currentPassword)) {
                throw new Exception("Current password is required");
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception("New passwords do not match");
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception("Password must be at least 8 characters long");
            }
            
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM admin_users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if (!password_verify($currentPassword, $user['password'])) {
                throw new Exception("Current password is incorrect");
            }
            
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin_users SET password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $user_id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Password updated successfully';
            } else {
                throw new Exception("Failed to update password: " . $stmt->error);
            }
            break;
            
        case 'upload_photo':
            if (empty($_FILES['photo']['tmp_name'])) {
                throw new Exception("No file uploaded");
            }
            
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($_FILES['photo']['type'], $allowed_types)) {
                throw new Exception("Only JPG, PNG, and GIF files are allowed");
            }
            
            if ($_FILES['photo']['size'] > $max_size) {
                throw new Exception("File size must be less than 5MB");
            }
            
            $upload_dir = __DIR__ . '/assets/uploads/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Generate unique filename
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = "profile_{$user_id}_" . time() . ".$ext";
            $target_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
                // Get old photo path
                $stmt = $conn->prepare("SELECT photo_path FROM admin_users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $old_photo = $result->fetch_assoc()['photo_path'];
                
                // Delete old photo if exists
                if ($old_photo && file_exists($old_photo)) {
                    unlink($old_photo);
                }
                
                // Update database with relative path
                $relative_path = 'assets/uploads/profiles/' . $filename;
                $stmt = $conn->prepare("UPDATE admin_users SET photo_path = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param("si", $relative_path, $user_id);
                
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Profile photo updated successfully';
                    $response['photo_path'] = $relative_path;
                } else {
                    throw new Exception("Failed to update photo in database");
                }
            } else {
                throw new Exception("Failed to upload photo");
            }
            break;
            
        case 'delete_account':
            if ($_POST['confirmation'] !== 'DELETE MY ACCOUNT') {
                throw new Exception("Confirmation text does not match");
            }
            
            // Get user data for logging
            $stmt = $conn->prepare("SELECT username FROM admin_users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $username = $result->fetch_assoc()['username'];
            
            // Delete user
            $stmt = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                // Destroy session
                session_destroy();
                
                $response['success'] = true;
                $response['message'] = 'Account deleted successfully';
                $response['redirect'] = 'index.php';
            } else {
                throw new Exception("Failed to delete account: " . $stmt->error);
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