<?php
require_once 'includes/db.php';

$firstName = trim($_POST['firstName']);
$middleName = trim($_POST['middleName']);
$lastName = trim($_POST['lastName']);
$suffix = trim($_POST['suffix']);
$birthdate = date('Y-m-d', strtotime($_POST['birthdate']));
$sex = $_POST['sex'];
$email = trim($_POST['registerEmail']);
$phone = trim($_POST['phone']);
$houseNo = trim($_POST['house_no']);
$purok = trim($_POST['purok']);
$fullAddress = trim($_POST['full_address']);
$password = $_POST['registerPassword'];
$confirmPassword = $_POST['confirmPassword'];
$idType = ($_POST['idType'] == 'other') ? trim($_POST['otherIdType']) : $_POST['idType'];
$idNumber = trim($_POST['idNumber']);

// Calculate age from birthdate
$today = new DateTime();
$birthdateObj = new DateTime($birthdate);
$age = $today->diff($birthdateObj)->y;

// Validate Password
if ($password !== $confirmPassword) {
    die("Passwords do not match.");
}

if (strlen($password) < 8) {
    die("Password must be at least 8 characters long.");
}

// Hash Password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Handle ID Upload
$targetDir = "uploads/ids/";
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        die("Failed to create upload directory.");
    }
}

$idFileName = basename($_FILES["idUpload"]["name"]);
$idFileTmp = $_FILES["idUpload"]["tmp_name"];
$idFileSize = $_FILES["idUpload"]["size"];
$idFileType = strtolower(pathinfo($idFileName, PATHINFO_EXTENSION));

$allowedTypes = array("jpg", "jpeg", "png", "pdf");

if (!in_array($idFileType, $allowedTypes)) {
    die("Only JPG, JPEG, PNG, and PDF files are allowed.");
}

if ($idFileSize > 5 * 1024 * 1024) {
    die("File size exceeds the maximum limit of 5MB.");
}

$newFileName = uniqid() . "." . $idFileType;
$targetFilePath = $targetDir . $newFileName;

if (!move_uploaded_file($idFileTmp, $targetFilePath)) {
    die("Failed to upload ID file.");
}

// Check if email already exists
$checkEmailSql = "SELECT id FROM resident_accounts WHERE email = ?";
$stmt = $conn->prepare($checkEmailSql);
if (!$stmt) {
    die("Error preparing email check statement: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    die("Email is already registered.");
}
$stmt->close();

// Insert into Database
$sql = "INSERT INTO resident_accounts 
        (first_name, middle_name, last_name, suffix, birthdate, age, sex, email, phone, 
         house_no, purok, full_address, password, id_type, id_number, id_file) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$bindResult = $stmt->bind_param(
    "sssssisssssssss",
    $firstName,
    $middleName,
    $lastName,
    $suffix,
    $birthdate,
    $age,
    $sex,
    $email,
    $phone,
    $houseNo,
    $purok,
    $fullAddress,
    $hashedPassword,
    $idType,
    $idNumber,
    $newFileName
);

if (!$bindResult) {
    die("Error binding parameters: " . $stmt->error);
}

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! Your account is pending approval.'); window.location.href='index.php';</script>";
} else {
    die("Error executing statement: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>