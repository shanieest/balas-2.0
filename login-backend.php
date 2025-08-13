<?php
session_start();
require_once 'includes/db.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $response['message'] = "Invalid CSRF token";
        echo json_encode($response);
        exit();
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['rememberMe']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $response['message'] = "Email and password are required";
        echo json_encode($response);
        exit();
    }

    try {
        // Get user with account status
        $stmt = $conn->prepare("SELECT r.*, a.password, a.account_status 
                              FROM residents r
                              JOIN resident_accounts a ON r.id = a.resident_id
                              WHERE r.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $response['message'] = "Invalid email or password";
            echo json_encode($response);
            exit();
        }

        $user = $result->fetch_assoc();

        // Check account status
        if ($user['account_status'] !== 'Approved') {
            $response['message'] = "Your account is pending approval. Please contact barangay administration.";
            echo json_encode($response);
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = 'resident';
            $_SESSION['logged_in'] = true;

            // Remember me functionality
            if ($rememberMe) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + 60 * 60 * 24 * 30; // 30 days
                
                setcookie('remember_token', $token, $expiry, '/');
                
                // Store token in database
                $updateStmt = $conn->prepare("UPDATE resident_accounts 
                                             SET remember_token = ?, token_expiry = ?
                                             WHERE resident_id = ?");
                $updateStmt->bind_param("ssi", $token, date('Y-m-d H:i:s', $expiry), $user['id']);
                $updateStmt->execute();
            }

            $response['success'] = true;
            $response['message'] = "Login successful";
            $response['redirect'] = "dashboard.php";
        } else {
            $response['message'] = "Invalid email or password";
        }
    } catch (Exception $e) {
        $response['message'] = "Login error: " . $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
?>