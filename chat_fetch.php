<?php
session_start();
include 'db.php';

$year     = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';

$stmt = $conn->prepare("SELECT si_no, message, created_at 
                        FROM chat_messages 
                        WHERE year = ? AND semester = ?
                        ORDER BY created_at DESC");
$stmt->bind_param("ss", $year, $semester);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header("Content-Type: application/json");
echo json_encode($messages);
