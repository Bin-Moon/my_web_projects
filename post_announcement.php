<?php
session_start();
include 'db.php'; // Make sure this path is correct

// Only CRs can post announcements
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'cr') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cr_dashboard.php");
    exit;
}

// Grab and sanitize input
$year = trim($_POST['year'] ?? '');
$semester = trim($_POST['semester'] ?? '');
$course = trim($_POST['course'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if ($course === '' || $message === '') {
    $_SESSION['flash_error'] = "Course and message are required.";
    header("Location: cr_dashboard.php");
    exit;
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO announcements (year, semester, course, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $year, $semester, $course, $message);

if ($stmt->execute()) {
    $_SESSION['flash_success'] = "Announcement posted successfully.";
} else {
    $_SESSION['flash_error'] = "Database error: " . $stmt->error;
}

header("Location: cr_dashboard.php");
exit;
