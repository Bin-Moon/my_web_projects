<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_code = $_POST['course_code'];
    $course_title = $_POST['course_title'];
    $credit = $_POST['credit'];
    $course_type = $_POST['course_type'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    $sql = "INSERT INTO courses (course_code, course_title, credit, course_type, year, semester)
            VALUES ('$course_code', '$course_title', '$credit', '$course_type', '$year', '$semester')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green; text-align:center;'>✅ Course uploaded successfully!</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Courses</title>
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #f5f7fa;
            padding: 40px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #2e5dd7;
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background-color: #2e5dd7;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #1d47b5;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📘 Upload Course</h2>
    <form method="POST">
        <label>Course Code</label>
        <input type="text" name="course_code" required>

        <label>Course Title</label>
        <input type="text" name="course_title" required>

        <label>Credit</label>
        <input type="number" step="0.1" name="credit" required>

        <label>Course Type</label>
        <select name="course_type" required>
            <option value="">Select Type</option>
            <option value="Theory">Theory</option>
            <option value="Lab">Lab</option>
            <option value="Project">Project</option>
        </select>

        <label>Year</label>
        <input type="text" name="year" placeholder="e.g., 1st" required>

        <label>Semester</label>
        <input type="text" name="semester" placeholder="e.g., 2nd" required>

        <button type="submit">Upload Course</button>
    </form>
</div>
</body>
</html>
