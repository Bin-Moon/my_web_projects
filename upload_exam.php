<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    header("HTTP/1.1 403 Forbidden");
    echo "Unauthorized access";
    exit;
}

include 'db.php'; // your DB connection

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $year = $_POST['year'] ?? '';
    $semester = $_POST['semester'] ?? '';

    if(empty($year) || empty($semester)){
        die("Year and semester are required.");
    }

    if(!isset($_FILES['exam_file'])){
        die("No file uploaded");
    }

    $file = $_FILES['exam_file'];
    $filename = $file['name'];
    $tmpName = $file['tmp_name'];
    $fileError = $file['error'];

    // Allowed file extensions
    $allowedExt = ['pdf', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowedExt)){
        die("Only PDF, JPG, JPEG, PNG files are allowed.");
    }

    if($fileError !== 0){
        die("File upload error code: $fileError");
    }

    // Base uploads folder
    $baseDir = __DIR__ . "/uploads/exam_routine";

    // Check and create exam_routine folder
    if(!is_dir($baseDir)){
        if(!mkdir($baseDir, 0777, true)){
            die("Failed to create 'exam_routine' folder. Please check folder permissions.");
        }
    }

    // Year/Semester folder
    $uploadDir = $baseDir . "/$year/semester_$semester/";
    if(!is_dir($uploadDir)){
        if(!mkdir($uploadDir, 0777, true)){
            die("Failed to create directory: $uploadDir. Check folder permissions.");
        }
    }

    if(!is_writable($uploadDir)){
        die("Upload folder is not writable: $uploadDir");
    }

    // Unique file name
    $newFileName = "exam_routine_" . time() . "." . $ext;
    $destination = $uploadDir . $newFileName;

    if(move_uploaded_file($tmpName, $destination)){
        // Save in DB (optional)
        $stmt = $conn->prepare("INSERT INTO exam_routine (year, semester, file_path) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE file_path=?");
        $stmt->bind_param("ssss", $year, $semester, $destination, $destination);
        $stmt->execute();

        echo "Exam routine uploaded successfully!";
    } else {
        die("Failed to move uploaded file. Check folder permissions and path.");
    }

} else {
    echo "Invalid request method.";
}
?>

