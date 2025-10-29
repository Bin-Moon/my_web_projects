<?php
session_start();
include 'db.php';

// Only CR can delete
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    exit("Unauthorized access");
}

$year     = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$course   = $_GET['course'] ?? '';
$file     = $_GET['file'] ?? '';

if($year && $semester && $course && $file){
    // Build file path
    $filePath = __DIR__ . "/uploads/study_materials/$year/Semester-$semester/$course/$file";

    // Delete DB row first
    $stmt = $conn->prepare("DELETE FROM study_materials WHERE year=? AND semester=? AND course=? AND file_path LIKE ?");
    $likePath = "%/$file"; 
    $stmt->bind_param("ssss", $year, $semester, $course, $likePath);
    $stmt->execute();

    // Delete file from server
    if(file_exists($filePath)){
        unlink($filePath);
    }
}

echo "Deleted successfully";
?>
