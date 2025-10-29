<?php
session_start();
include 'db.php';

// ✅ Only CR can upload
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'cr') {
    header("Location: login.php");
    exit;
}

// ✅ Collect form data
$year     = trim($_POST['year'] ?? '');
$semester = trim($_POST['semester'] ?? '');
$course   = trim($_POST['course'] ?? '');
$title    = trim($_POST['title'] ?? '');
$file     = $_FILES['material_file'] ?? null;

// ✅ Validation
if (!$year || !$semester || !$course || !$title || !$file) {
    die("❌ All fields including file are required!");
}
if ($file['error'] !== UPLOAD_ERR_OK) {
    die("❌ File upload error: " . $file['error']);
}

// ✅ File extension & validation
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['pdf','doc','docx','ppt','pptx','zip'];

if (!in_array($ext, $allowed)) {
    die("❌ Invalid file type. Allowed: pdf, doc, docx, ppt, pptx, zip");
}
if ($file['size'] > 20 * 1024 * 1024) { // 20 MB max
    die("❌ File too large. Max 20MB allowed.");
}

// ✅ Create upload directory
$uploadDir = __DIR__ . "/uploads/study_materials/$year/Semester-$semester/$course";
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
    die("❌ Failed to create directory: $uploadDir");
}

// ✅ Prepare safe filename
$filename = uniqid("mat_") . "." . $ext;
$targetPath = $uploadDir . "/" . $filename;
$relativePath = "WebDevelopement/uploads/study_materials/$year/Semester-$semester/$course/$filename";

// ✅ Move file
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    die("❌ Failed to move uploaded file.");
}

// ✅ Insert into DB
$stmt = $conn->prepare("INSERT INTO study_materials (year, semester, course, title, file_path) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $year, $semester, $course, $title, $relativePath);

if ($stmt->execute()) {
    header("Location: cr_dashboard.php?upload=success");
    exit;
} else {
    die("⚠️ Database insert failed: " . $stmt->error);
}
?>
