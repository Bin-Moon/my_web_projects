<?php
// Enable errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php'; // your database connection file

// Check if teacher is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] != "teacher") {
    die("Please login as teacher!");
}

// Get year & semester from dashboard
$academic_year = isset($_GET['year']) ? $_GET['year'] : '';
$semester      = isset($_GET['semester']) ? $_GET['semester'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $student_id    = $conn->real_escape_string($_POST['student_unique_id']);
    $course_name   = $conn->real_escape_string($_POST['course_name']);
    $marks         = $conn->real_escape_string($_POST['marks']);
    $academic_year = $conn->real_escape_string($_POST['academic_year']);
    $semester      = $conn->real_escape_string($_POST['semester']);

    if (!empty($student_id) && !empty($course_name) && $marks !== "" && !empty($academic_year) && !empty($semester)) {
        $sql = "INSERT INTO results (student_unique_id, course_name, marks, grade, academic_year, semester) 
                VALUES ('$student_id', '$course_name', '$marks', '', '$academic_year', '$semester')";

        if ($conn->query($sql) === TRUE) {
            $success = "CT marks uploaded successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CT Marks</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f6f8;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2e5dd7;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 12px;
            background-color: #2e5dd7;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1a3ca8;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }

        label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Upload CT Marks</h2>

        <?php if (isset($success)) {
            echo "<p class='success'>$success</p>";
        } ?>
        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>

        <form method="POST" action="">
            <label for="student_unique_id">Student ID</label>
            <input type="text" id="student_unique_id" name="student_unique_id" placeholder="Enter Student Unique ID" required>

            <label for="course_name">Course Name</label>
            <input type="text" id="course_name" name="course_name" placeholder="Enter Course Name" required>

            <label for="marks">Marks</label>
            <input type="number" id="marks" name="marks" placeholder="Enter Marks" step="0.01" required>

            <!-- Hidden fields for year and semester -->
            <input type="hidden" name="academic_year" value="<?php echo htmlspecialchars($academic_year); ?>">
            <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">

            <button type="submit">Upload</button>
        </form>
    </div>
</body>

</html>