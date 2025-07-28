<?php
require_once 'includes/db.php';

// Sanitize helper
function sanitize($data, $conn) {
    return htmlspecialchars(mysqli_real_escape_string($conn, trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $first_name    = sanitize($_POST['firstName'], $conn);
    $last_name     = sanitize($_POST['lastName'], $conn);
    $birthdate     = sanitize($_POST['birthdate'], $conn);
    $sex           = sanitize($_POST['sex'], $conn);
    $email         = sanitize($_POST['registerEmail'], $conn);
    $phone         = sanitize($_POST['phone'], $conn);
    $address       = sanitize($_POST['address'], $conn);
    $password      = $_POST['registerPassword'];
    $confirm       = $_POST['confirmPassword'];
    $id_type       = sanitize($_POST['idType'], $conn);
    $id_number     = sanitize($_POST['idNumber'], $conn);

    // Validate passwords
    if ($password !== $confirm) {
        die('Passwords do not match.');
    }

    if (strlen($password) < 8) {
        die('Password must be at least 8 characters long.');
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM residents WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        die('This email is already registered.');
    }
    $check->close();

    // Upload ID file
    if (isset($_FILES['idUpload']) && $_FILES['idUpload']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $file_name = $_FILES['idUpload']['name'];
        $file_tmp  = $_FILES['idUpload']['tmp_name'];
        $file_size = $_FILES['idUpload']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die('Invalid file type. Only JPG, PNG, and PDF allowed.');
        }

        if ($file_size > 5 * 1024 * 1024) {
            die('File size must not exceed 5MB.');
        }

        $new_name = uniqid('id_') . '.' . $ext;
        $upload_dir = 'uploads/ids/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        move_uploaded_file($file_tmp, $upload_dir . $new_name);
        $id_image = $upload_dir . $new_name;
    } else {
        die('ID upload failed. Please try again.');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert to DB
    $stmt = $conn->prepare("INSERT INTO residents (first_name, last_name, birthdate, sex, email, phone, address, password, id_type, id_number, id_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $first_name, $last_name, $birthdate, $sex, $email, $phone, $address, $hashed_password, $id_type, $id_number, $id_image);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method.';
}
?>
