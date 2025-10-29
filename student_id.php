<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['student_unique_id']) || $_SESSION['role'] != "student") {
    die("Please login as student!");
}

$student_id = $_SESSION['student_unique_id'];
$message = "";

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $blood_group = $_POST['blood_group'];
    $department = $_POST['department'];
    $hall_name = $_POST['hall_name'];

    // Check if already applied
    $check = $conn->prepare("SELECT * FROM student_id_applications WHERE student_unique_id = ?");
    $check->bind_param("s", $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "<p style='color:red;'>You have already applied for an ID card.</p>";
    } else {
        $sql = $conn->prepare("INSERT INTO student_id_applications (student_unique_id, name, blood_group, department, hall_name) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss", $student_id, $name, $blood_group, $department, $hall_name);

        if ($sql->execute()) {
            $message = "<p style='color:green;'>Your ID card application has been submitted successfully!</p>";
        } else {
            $message = "<p style='color:red;'>Error submitting application. Please try again.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Apply for Student ID</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f3f6fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    width: 400px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #2e5dd7;
}
form {
    display: flex;
    flex-direction: column;
}
label {
    margin-top: 10px;
    font-weight: 600;
}
input, select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-top: 5px;
}
button {
    margin-top: 20px;
    padding: 10px;
    background: #2e5dd7;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}
button:hover {
    background: #2049b5;
}
.message {
    text-align: center;
    margin-top: 15px;
}
</style>
</head>
<body>
<div class="container">
    <h2>🎓 Apply for Student ID Card</h2>
    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Student ID</label>
        <input type="text" value="<?php echo htmlspecialchars($student_id); ?>" readonly>

        <label>Blood Group</label>
        <select name="blood_group" required>
            <option value="">Select</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>

        <label>Department</label>
        <input type="text" name="department" required>

        <label>Hall Name</label>
        <input type="text" name="hall_name" required>

        <button type="submit">Submit Application</button>
    </form>

    <div class="message"><?php echo $message; ?></div>
</div>
</body>
</html>
