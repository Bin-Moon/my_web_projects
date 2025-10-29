<?php
session_start();
include 'db.php';

if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $course   = $_POST['course'];
    $date     = $_POST['date'];
    $year     = $_POST['year'];
    $semester = $_POST['semester'];

    // ✅ Case 1: File chosen
    if(isset($_FILES['attendance_file']) && $_FILES['attendance_file']['error'] == 0){
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName   = time() . "_" . basename($_FILES['attendance_file']['name']);
        $targetFile = $targetDir . $fileName;

        if(move_uploaded_file($_FILES['attendance_file']['tmp_name'], $targetFile)){
            // Insert record into attendance table
            $stmt = $conn->prepare("INSERT INTO attendance 
                (student_email, course, date, status, year, semester, file_path) 
                VALUES (NULL, ?, ?, NULL, ?, ?, ?)");
            $stmt->bind_param("sssis", $course, $date, $year, $semester, $targetFile);
            $stmt->execute();

            echo "<p>✅ Attendance file uploaded successfully!</p>";
            exit; // 🚀 Stop here, don't redirect
        }
    }

    // ✅ Case 2: No file → redirect to manual attendance page
    header("Location: manual_attendance.php?course=$course&date=$date&year=$year&semester=$semester");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload or Mark Attendance</title>
</head>
<body>
    <h2>Upload Attendance File OR Mark Manually</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="course" value="CSE101">
        <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
        <input type="hidden" name="year" value="2025">
        <input type="hidden" name="semester" value="1">

        <label>Upload Attendance File:</label>
        <input type="file" name="attendance_file" accept=".pdf,.xls,.xlsx,.csv,.jpg,.png">
        <br><br>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
