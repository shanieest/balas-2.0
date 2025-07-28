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

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    switch ($action) {
        case 'get_announcements':
            $result = $conn->query("SELECT a.*, u.username as posted_by_name 
                                  FROM announcements a
                                  JOIN admin_users u ON a.posted_by = u.id
                                  ORDER BY a.created_at DESC");
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['success'] = true;
            break;
            
        case 'get_announcement':
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT a.*, u.username as posted_by_name 
                                   FROM announcements a
                                   JOIN admin_users u ON a.posted_by = u.id
                                   WHERE a.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $response['data'] = $result->fetch_assoc();
                $response['success'] = true;
            } else {
                $response['message'] = 'Announcement not found';
            }
            break;
            
        case 'add_announcement':
            $title = $conn->real_escape_string($_POST['title']);
            $content = $conn->real_escape_string($_POST['content']);
            $posted_by = getUserId();
            
            // Handle file upload
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'assets/uploads/announcements/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $file_ext;
                $target_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $image_path = $target_path;
                }
            }
            
            $stmt = $conn->prepare("INSERT INTO announcements (title, content, image_path, posted_by) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $title, $content, $image_path, $posted_by);
            
            if ($stmt->execute()) {
                // Log activity
                logActivity($conn, $posted_by, "Added new announcement: $title");
                
                $response['success'] = true;
                $response['message'] = 'Announcement added successfully';
            } else {
                throw new Exception("Failed to add announcement: " . $stmt->error);
            }
            break;
            
        case 'update_announcement':
            $id = $_POST['id'];
            $title = $conn->real_escape_string($_POST['title']);
            $content = $conn->real_escape_string($_POST['content']);
            
            // Get current image path
            $stmt = $conn->prepare("SELECT image_path FROM announcements WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_image = $result->fetch_assoc()['image_path'];
            
            // Handle file upload
            $image_path = $current_image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                // Delete old image if exists
                if ($current_image && file_exists($current_image)) {
                    unlink($current_image);
                }
                
                $upload_dir = 'assets/uploads/announcements/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $file_ext;
                $target_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $image_path = $target_path;
                }
            }
            
            $stmt = $conn->prepare("UPDATE announcements 
                                    SET title = ?, content = ?, image_path = ?, updated_at = NOW() 
                                    WHERE id = ?");
            $stmt->bind_param("sssi", $title, $content, $image_path, $id);
            
            if ($stmt->execute()) {
                // Log activity
                logActivity($conn, getUserId(), "Updated announcement ID $id");
                
                $response['success'] = true;
                $response['message'] = 'Announcement updated successfully';
            } else {
                throw new Exception("Failed to update announcement: " . $stmt->error);
            }
            break;
            
        case 'delete_announcement':
            $id = $_POST['id'];
            
            // Get image path first
            $stmt = $conn->prepare("SELECT image_path FROM announcements WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $image_path = $result->fetch_assoc()['image_path'];
            
            // Delete announcement
            $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                // Delete image file if exists
                if ($image_path && file_exists($image_path)) {
                    unlink($image_path);
                }
                
                // Log activity
                logActivity($conn, getUserId(), "Deleted announcement ID $id");
                
                $response['success'] = true;
                $response['message'] = 'Announcement deleted successfully';
            } else {
                throw new Exception("Failed to delete announcement: " . $stmt->error);
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