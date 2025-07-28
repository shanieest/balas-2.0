<?php
// Move these lines BEFORE session_start
// ini_set('session.cookie_httponly', 1);
//ini_set('session.cookie_secure', 1); // Enable only if using HTTPS
//ini_set('session.use_strict_mode', 1);
//ini_set('session.gc_maxlifetime', 1800);
//session_set_cookie_params(1800);

session_start();

require_once 'db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id'])  || isset($_SESSION['user_id']);
}

// Redirect to login if not authenticated
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Session expired. Please login again.']);
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserData($conn) {
    if (!isLoggedIn()) return null;

    $user_id = getUserId();
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function hasPermission($requiredPosition) {
    global $conn;
    if (!isLoggedIn()) return false;

    $user = getUserData($conn);
    return $user && $user['position'] === $requiredPosition;
}

function logout() {
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, '/');
    session_regenerate_id(true);
    header("Location: index.php");
    exit();
}
?>
