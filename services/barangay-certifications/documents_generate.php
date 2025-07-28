<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require '../../includes/db.php';

$request_id = $_GET['request_id'];

$stmt = $conn->prepare("
    SELECT dr.*, r.full_name, r.address, r.purok, r.civil_status, r.sex, r.age
    FROM document_requests dr
    JOIN residents r ON dr.resident_id = r.id
    WHERE dr.id = ?
");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Load template
$templatePath = __DIR__ . '/../templates/' . $data['document_type'] . '.docx';

if (!file_exists($templatePath)) {
    die("Template not found: " . $templatePath);
}

$template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

// Replace placeholders
$template->setValue('full_name', $data['full_name']);
$template->setValue('address', $data['address']);
$template->setValue('purok', $data['purok']);
$template->setValue('age', $data['age']);
$template->setValue('sex', $data['sex']);
$template->setValue('purpose', $data['purpose']);
$template->setValue('civil_status', $data['civil_status']);
$template->setValue('request_date', date('F j, Y', strtotime($data['request_date'])));
$template->setValue('queue_number', str_pad($data['queue_number'], 3, '0', STR_PAD_LEFT));
$template->setValue('year', $data['request_year']);
// add more as needed

// Save and download
$filename = $data['document_type'] . '-' . $data['queue_number'] . '.docx';
$template->saveAs($filename);

header("Content-Disposition: attachment; filename=" . $filename);
readfile($filename);
unlink($filename); // delete after download
exit;
?>