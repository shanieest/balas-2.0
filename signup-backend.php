<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $middleName = $conn->real_escape_string($_POST['middleName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $suffix = $conn->real_escape_string($_POST['suffix'] ?? '');
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $age = intval($_POST['age']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $email = $conn->real_escape_string($_POST['registerEmail']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $houseNo = $conn->real_escape_string($_POST['house_no']);
    $purok = $conn->real_escape_string($_POST['purok']);
    $fullAddress = $conn->real_escape_string($_POST['full_address']);
    $password = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
    $idType = $conn->real_escape_string($_POST['idType']);
    $idNumber = $conn->real_escape_string($_POST['idNumber']);
    
    // Handle file upload
    $validIdPath = '';
    if (isset($_FILES['idUpload']) && $_FILES['idUpload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/valid_ids/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExt = pathinfo($_FILES['idUpload']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('id_') . '.' . $fileExt;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['idUpload']['tmp_name'], $filePath)) {
            $validIdPath = $filePath;
        }
    }
    
    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM resident_accounts WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    
    if ($checkEmail->num_rows > 0) {
        $_SESSION['error'] = "Email address already registered";
        header("Location: signup.php#register");
        exit();
    }
    
    // Check if resident already exists
    $checkResident = $conn->prepare("SELECT id FROM residents WHERE email = ?");
    $checkResident->bind_param("s", $email);
    $checkResident->execute();
    $checkResident->store_result();
    
    if ($checkResident->num_rows > 0) {
        $_SESSION['error'] = "You are already registered as a resident";
        header("Location: signup.php#register");
        exit();
    }
    
    // Insert resident data
    $residentQuery = "INSERT INTO residents (
        first_name, last_name, middle_name, suffix, sex, birthdate, age, 
        contact_number, email, house_number, purok, address, 
        verification_status, resident_status, valid_id_path
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Active', ?)";
    
    $residentStmt = $conn->prepare($residentQuery);
    $residentStmt->bind_param(
        "ssssssissssss", 
        $firstName, $lastName, $middleName, $suffix, $sex, $birthdate, $age,
        $phone, $email, $houseNo, $purok, $fullAddress, $validIdPath
    );
    
    if ($residentStmt->execute()) {
        $residentId = $conn->insert_id;
        
        // Insert resident account
        $accountQuery = "INSERT INTO resident_accounts (
            resident_id, email, password, account_status
        ) VALUES (?, ?, ?, 'Pending')";
        
        $accountStmt = $conn->prepare($accountQuery);
        $accountStmt->bind_param("iss", $residentId, $email, $password);
        
        if ($accountStmt->execute()) {
            $_SESSION['success'] = "Registration successful! Your account is pending approval.";
            header("Location: signup.php#login");
            exit();
        } else {
            $_SESSION['error'] = "Error creating account: " . $conn->error;
            header("Location: signup.php#register");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error registering resident: " . $conn->error;
        header("Location: signup.php#register");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}
?>