<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if resident exists
    $query = "SELECT ra.*, r.first_name, r.last_name, r.photo_path 
              FROM resident_accounts ra 
              JOIN residents r ON ra.resident_id = r.id 
              WHERE ra.email = ? AND ra.account_status = 'Approved'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['resident_id'] = $user['resident_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['photo_path'] = $user['photo_path'];
            $_SESSION['logged_in'] = true;
            
            // Log activity
            $activity = "Logged in to the system";
            $log_query = "INSERT INTO activity_logs (user_id, activity) VALUES (?, ?)";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param("is", $user['resident_id'], $activity);
            $log_stmt->execute();
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password";
            header("Location: signup.php#login");
            exit();
        }
    } else {
        $_SESSION['error'] = "Account not found or not yet approved";
        header("Location: signup.php#login");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}
?>