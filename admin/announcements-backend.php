<?php
// announcements-backend.php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

// Check if user is authenticated
requireAuth();

// Database connection
$db = connectToDB();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                handleAddAnnouncement($db);
                break;
            case 'edit':
                handleEditAnnouncement($db);
                break;
            case 'delete':
                handleDeleteAnnouncement($db);
                break;
        }
    }
}

// Handle GET requests (for fetching single announcement)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'get' && isset($_GET['id'])) {
        $announcement = getAnnouncementById($db, $_GET['id']);
        header('Content-Type: application/json');
        echo json_encode($announcement);
        exit();
    }
}

// Function to handle adding a new announcement
function handleAddAnnouncement($db) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $date = $_POST['date'];
    $posted_by = $_SESSION['user_id'];
    
    // Validate required fields
    if (empty($title) || empty($content) || empty($date)) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'All required fields must be filled'];
        header("Location: announcements.php");
        exit();
    }
    
    // Handle file upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/assets/announcements/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Validate image file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['image']['tmp_name']);
        finfo_close($file_info);
        
        if (!in_array($mime_type, $allowed_types)) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Only JPG, PNG, and GIF images are allowed'];
            header("Location: announcements.php");
            exit();
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('announcement_') . '.' . $file_ext;
        $destination = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $image_path = 'assets/announcements/' . $filename;
        }
    }
    
    $stmt = $db->prepare("INSERT INTO announcements (title, content, image_path, date_posted, posted_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $content, $image_path, $date, $posted_by);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Announcement added successfully'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error adding announcement: ' . $db->error];
    }
    
    $stmt->close();
    header("Location: announcements.php");
    exit();
}

// Function to handle editing an announcement
function handleEditAnnouncement($db) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $date = $_POST['date'];
    
    // Validate required fields
    if (empty($title) || empty($content) || empty($date)) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'All required fields must be filled'];
        header("Location: announcements.php");
        exit();
    }
    
    // Get current announcement data
    $stmt = $db->prepare("SELECT image_path FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();
    $stmt->close();
    
    $image_path = $announcement['image_path'];
    
    // Handle file upload if new image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Validate image file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['image']['tmp_name']);
        finfo_close($file_info);
        
        if (!in_array($mime_type, $allowed_types)) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Only JPG, PNG, and GIF images are allowed'];
            header("Location: announcements.php");
            exit();
        }
        
        // Delete old image if exists
        if ($image_path && file_exists(__DIR__ . '/' . $image_path)) {
            unlink(__DIR__ . '/' . $image_path);
        }
        
        $upload_dir = __DIR__ . '/assets/announcements/';
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('announcement_') . '.' . $file_ext;
        $destination = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $image_path = 'assets/announcements/' . $filename;
        }
    }
    
    $stmt = $db->prepare("UPDATE announcements SET title = ?, content = ?, image_path = ?, date_posted = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $content, $image_path, $date, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Announcement updated successfully'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error updating announcement: ' . $db->error];
    }
    
    $stmt->close();
    header("Location: announcements.php");
    exit();
}

// Function to handle deleting an announcement
function handleDeleteAnnouncement($db) {
    $id = $_POST['id'];
    
    // First get the image path to delete the file
    $stmt = $db->prepare("SELECT image_path FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();
    $stmt->close();
    
    // Delete the image file if exists
    if ($announcement['image_path'] && file_exists(__DIR__ . '/' . $announcement['image_path'])) {
        unlink(__DIR__ . '/' . $announcement['image_path']);
    }
    
    // Delete the announcement from database
    $stmt = $db->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Announcement deleted successfully'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error deleting announcement: ' . $db->error];
    }
    
    $stmt->close();
    header("Location: announcements.php");
    exit();
}

// Function to get all announcements
function getAllAnnouncements($db) {
    $query = "SELECT a.*, u.username FROM announcements a 
              JOIN admin_users u ON a.posted_by = u.id 
              ORDER BY a.date_posted DESC, a.created_at DESC";
    $result = $db->query($query);
    
    $announcements = [];
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
    
    return $announcements;
}

// Function to get a single announcement by ID
function getAnnouncementById($db, $id) {
    $stmt = $db->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcement = $result->fetch_assoc();
    $stmt->close();
    
    return $announcement;
}
?>