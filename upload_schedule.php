<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only allow CRs
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    die("Unauthorized");
}

// Include database connection
require_once 'db.php'; // make sure path is correct

// If POST, save schedule
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $year = $_POST['year'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $schedule_text = $_POST['schedule_text'] ?? '';

    if(empty($year) || empty($semester) || empty($schedule_text)){
        die("All fields are required!");
    }

    // Sanitize input
    $schedule_text = $conn->real_escape_string($schedule_text);

    // Check if a schedule already exists
    $sql_check = "SELECT * FROM class_schedule WHERE year='$year' AND semester='$semester'";
    $result = $conn->query($sql_check);

    if($result->num_rows > 0){
        // Update existing schedule
        $sql = "UPDATE class_schedule SET schedule_text='$schedule_text', updated_at=NOW() 
                WHERE year='$year' AND semester='$semester'";
    } else {
        // Insert new schedule
        $sql = "INSERT INTO class_schedule (year, semester, schedule_text, created_at) 
                VALUES ('$year', '$semester', '$schedule_text', NOW())";
    }

    if($conn->query($sql) === TRUE){
        header("Location: CRs_dashboard.php?msg=Schedule saved");
        exit;
    } else {
        die("DB Error: " . $conn->error);
    }
}

// Optional: GET request to return existing schedule (for AJAX)
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $year = $_GET['year'] ?? '';
    $semester = $_GET['semester'] ?? '';

    $sql = "SELECT schedule_text FROM class_schedule WHERE year='$year' AND semester='$semester' LIMIT 1";
    $res = $conn->query($sql);

    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        echo json_encode(['schedule_text' => $row['schedule_text']]);
    } else {
        echo json_encode(['schedule_text' => '']);
    }
}

$conn->close();
?>






