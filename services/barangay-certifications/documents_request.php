<?php
require '../../includes/db.php';

$full_name = $_POST['full_name'];
$dob = $_POST['dob'];
$document_type = $_POST['document_type'];
$purpose = $_POST['purpose'];
$year = date("Y");

// Check if resident exists
$checkResident = $conn->prepare("SELECT * FROM residents WHERE full_name = ? AND dob = ?");
$checkResident->bind_param("ss", $full_name, $dob);
$checkResident->execute();
$residentResult = $checkResident->get_result();

if ($residentResult->num_rows === 0) {
    die("<script>alert('Resident not found. Must be registered in census.'); window.location.href = '/barangay-balas/index.php';</script>");
}

$resident = $residentResult->fetch_assoc();
$resident_id = $resident['id'];

// Get latest queue number
$stmt = $conn->prepare("SELECT MAX(queue_number) as last_queue FROM document_requests WHERE document_type = ? AND request_year = ?");
$stmt->bind_param("si", $document_type, $year);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$next_queue_raw = isset($result['last_queue']) ? (int)$result['last_queue'] + 1 : 1;
$next_queue = str_pad($next_queue_raw, 3, '0', STR_PAD_LEFT);

// Insert request including address, sex, and civil status
$insert = $conn->prepare("
    INSERT INTO document_requests (
        resident_id, document_type, request_year, queue_number, purpose,
        address, sex, civil_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$insert->bind_param(
    "isisssss",
    $resident_id,
    $document_type,
    $year,
    $next_queue,
    $purpose,
    $resident['address'],
    $resident['sex'],
    $resident['civil_status']
    
);

if ($insert->execute()) {
    echo "<script>
        alert('Document requested successfully! Queue No: $next_queue');
        window.location.href = '/barangay-balas/index.php';
    </script>";
} else {
    echo "<script>alert('Request failed. Please try again.');</script>";
}
?>
