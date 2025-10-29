<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    die("Unauthorized access! <a href='admin_login.php'>Login</a>");
}

require_once "db.php"; // your DB connection

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year = trim($_POST['year']);
    $semester = trim($_POST['semester']);
    $course_code = trim($_POST['course_code']);
    $course_title = trim($_POST['course_title']);
    $credits = trim($_POST['credits']);

    if ($year && $semester && $course_code && $course_title) {
        $stmt = $conn->prepare("INSERT INTO courses (year, semester, course_code, course_title, credits, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssi", $year, $semester, $course_code, $course_title, $credits);
        if ($stmt->execute()) {
            $success = "Course uploaded successfully!";
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Courses</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f7fa;
    padding: 20px;
}
.container {
    max-width: 500px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
h1 {
    text-align: center;
    color: #2e5dd7;
    margin-bottom: 20px;
}
input[type=text], input[type=number] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    width: 100%;
    padding: 10px;
    background: #2e5dd7;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}
button:hover {
    background: #1743a1;
}
.success { color: green; text-align:center; margin-bottom:10px; }
.error { color: red; text-align:center; margin-bottom:10px; }
</style>
</head>
<body>

<div class="container">
<h1>Upload Courses</h1>

<?php if($success): ?>
    <p class="success"><?= $success ?></p>
<?php endif; ?>

<?php if($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <label for="year">Year (e.g., 2023-2024)</label>
    <input type="text" name="year" required placeholder="Enter Academic Year">

    <label for="semester">Semester (e.g., 1,2,3...)</label>
    <input type="text" name="semester" required placeholder="Enter Semester Number">

    <label for="course_code">Course Code</label>
    <input type="text" name="course_code" required placeholder="Enter Course Code">

    <label for="course_title">Course Title</label>
    <input type="text" name="course_title" required placeholder="Enter Course Title">

    <label for="credits">Credits</label>
    <input type="number" name="credits" min="0" value="3" required>

    <button type="submit">Upload</button>
</form>
</div>

</body>
</html>
