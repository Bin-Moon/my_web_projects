<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // Make sure your DB connection file is correct

// Require logged-in CR
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'cr') {
    header("Location: login.php");
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cr_dashboard.php");
    exit;
}

// Grab & sanitize inputs
$year = trim($_POST['year'] ?? '');
$semester = trim($_POST['semester'] ?? '');
$course = trim($_POST['title'] ?? ''); // form uses 'title' for course
$due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

// Basic validation
if ($course === '') {
    $_SESSION['flash_error'] = "Course/Report title is required.";
    header("Location: cr_dashboard.php");
    exit;
}

// File handling
$filePath = null;
if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['report_file'];

    // Check upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash_error'] = "File upload error. Code: " . $file['error'];
        header("Location: cr_dashboard.php");
        exit;
    }

    // Allowed extensions & mime types
    $allowedExts = ['pdf', 'doc', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        $_SESSION['flash_error'] = "Invalid file type. Only PDF/DOC/DOCX allowed.";
        header("Location: cr_dashboard.php");
        exit;
    }

    // Optional MIME check
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowedMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    if (!in_array($mime, $allowedMimes)) {
        $_SESSION['flash_error'] = "File mime-type not allowed ($mime).";
        header("Location: cr_dashboard.php");
        exit;
    }

    // Ensure upload directory exists
    $uploadDir = __DIR__ . '/uploads/reports/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    try {
        $unique = time() . '_' . bin2hex(random_bytes(8));
    } catch (Exception $e) {
        $unique = time() . '_' . mt_rand(1000, 9999);
    }
    $newName = $unique . '.' . $ext;
    $dest = $uploadDir . $newName;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $_SESSION['flash_error'] = "Failed to move uploaded file.";
        header("Location: cr_dashboard.php");
        exit;
    }

    // Store relative path
    $filePath = 'uploads/reports/' . $newName;
}

// Insert into DB
$stmt = $conn->prepare("INSERT INTO reports (year, semester, course, due_date, file_path) VALUES (?, ?, ?, ?, ?)");
if ($stmt === false) {
    $_SESSION['flash_error'] = "DB prepare failed: " . $conn->error;
    header("Location: cr_dashboard.php");
    exit;
}

$stmt->bind_param('sssss', $year, $semester, $course, $due_date, $filePath);

if ($stmt->execute()) {
    $_SESSION['flash_success'] = "Report uploaded successfully.";
    header("Location: cr_dashboard.php");
    exit;
} else {
    $_SESSION['flash_error'] = "Database error: " . $stmt->error;
    header("Location: cr_dashboard.php");
    exit;
}
?>
