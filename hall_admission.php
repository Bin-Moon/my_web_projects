<?php
require_once "db.php"; // DB connection

$success = "";

// Hall list
$halls = [
    "Bhasa Shohid Abdus Salam Hall",
    "Hazrat Bibi Khadiza Hall",
    "Bir Muktijuddha Abdul Malek Ukil Hall",
    "July Shahid Smriti Chatri Hall",
    "Nawab Faizunnesa Choudhurani Hall"
];

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $department = $_POST['department'];
    $session = $_POST['session'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $student_id = $_POST['student_id'];
    $hall_name = $_POST['hall_name'];
    $application_date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO hall_applications (name, department, session, year, semester, student_unique_id, hall_name, application_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $department, $session, $year, $semester, $student_id, $hall_name, $application_date);
    $stmt->execute();

    $success = "Hall application submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hall Admission Form</title>
<style>
body { font-family: 'Poppins', sans-serif; background: #f5f7fa; margin: 0; padding: 20px; }
.container { max-width: 600px; margin: 30px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #2e5dd7; margin-bottom: 20px; }
form { display: flex; flex-direction: column; gap: 15px; }
input, select, button { padding: 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; }
button { background-color: #2e5dd7; color: white; border: none; cursor: pointer; }
button:hover { background-color: #1f3bb3; }
.success { color: green; text-align: center; margin-bottom: 10px; }
</style>
</head>
<body>
<div class="container">
    <h2>Hall Admission Form</h2>

    <?php if($success) echo "<p class='success'>$success</p>"; ?>

    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Department:</label>
        <input type="text" name="department" required>

        <label>Session:</label>
        <input type="text" name="session" required>

        <label>Year:</label>
        <input type="text" name="year" required>

        <label>Semester:</label>
        <input type="text" name="semester" required>

        <label>Student ID:</label>
        <input type="text" name="student_id" required>

        <label>Select Hall:</label>
        <select name="hall_name" required>
            <option value="">--Select Hall--</option>
            <?php
            foreach($halls as $hall){
                echo "<option value='$hall'>$hall</option>";
            }
            ?>
        </select>

        <button type="submit">Submit Application</button>


    </form>


    <div style="margin-top:20px; text-align:center;">
    <a href="check_interview.php" 
       style="padding:10px 20px; background:#2e5dd7; color:white; border-radius:6px; text-decoration:none; display:inline-block;">
       Check Interview Date
    </a>
</div>
</div>
</body>
</html>
