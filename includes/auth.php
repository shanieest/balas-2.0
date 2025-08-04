<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: signup.php");
    exit();
}

$inactive = 1800; // 30 minutes in seconds
if (isset($_SESSION['last_activity'])) {
    $session_life = time() - $_SESSION['last_activity'];
    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        header("Location: signup.php?session_expired=1");
        exit();
    }
}
$_SESSION['last_activity'] = time();
?>