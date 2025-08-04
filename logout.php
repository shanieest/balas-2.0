<?php
require_once 'inccludes/db.php';
require_once 'includes/auth.php';

$auth = new Auth($conn);
$auth->logout();

header("Location: index.php");
exit();
?>