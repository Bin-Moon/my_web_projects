<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['email']) || strtolower($_SESSION['role']) != 'cr'){
    die("Unauthorized");
}

require_once 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $year = $_POST['year'] ?? '';
    $semester = $_POST['semester'] ?? '';

    // Check required fields
    if(empty($year) || empty($semester) || !isset($_FILES['routine_file'])){
        http_response_code(400);
        echo "All fields are required!";
        exit;
    }

    $file = $_FILES['routine_file'];
    $allowed_ext = ['pdf', 'doc', 'docx', 'txt'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if(!in_array($file_ext, $allowed_ext)){
        http_response_code(400);
        echo "Invalid file type! Allowed: PDF, DOC, DOCX, TXT";
        exit;
    }

    // Create uploads folder if not exists
    $upload_dir = __DIR__ . "/uploads/routine/$year/$semester/";
    if(!is_dir($upload_dir)){
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($file['name']);
    $file_path = $upload_dir . $file_name;

    if(move_uploaded_file($file['tmp_name'], $file_path)){

        // Save file path in database (relative path)
        $relative_path = "uploads/routine/$year/$semester/$file_name";

        // Check if record exists
        $sql_check = "SELECT id FROM class_routines WHERE year='$year' AND semester='$semester'";
        $res = $conn->query($sql_check);

        if($res->num_rows > 0){
            $sql = "UPDATE class_routines SET file_path='$relative_path', uploaded_at=NOW() 
                    WHERE year='$year' AND semester='$semester'";
        } else {
            $sql = "INSERT INTO class_routines (year, semester, file_path, uploaded_at) 
                    VALUES ('$year', '$semester', '$relative_path', NOW())";
        }

        if($conn->query($sql)){
            echo "success";
        } else {
            http_response_code(500);
            echo "DB Error: " . $conn->error;
        }

    } else {
        http_response_code(500);
        echo "Failed to upload file!";
    }

    exit;
}

$conn->close();
?>
