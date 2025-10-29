<?php
session_start();
include 'db.php';

if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    header("Location: login.php");
    exit;
}

$course   = $_GET['course'] ?? '';
$date     = $_GET['date'] ?? '';
$year     = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['students'])) {
    foreach($_POST['students'] as $email => $status){
        $stmt = $conn->prepare("INSERT INTO attendance 
            (student_email, course, date, status, year, semester, file_path) 
            VALUES (?, ?, ?, ?, ?, ?, NULL)");
        $stmt->bind_param("sssssi", $email, $course, $date, $status, $year, $semester);
        $stmt->execute();
    }
    echo "<p>✅ Manual attendance saved successfully!</p>";
    exit;
}

$students = $conn->query("SELECT email, name FROM s_info");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manual Attendance</title>
</head>
<body>
    <h2>Manual Attendance — <?= htmlspecialchars($course) ?> (<?= htmlspecialchars($date) ?>)</h2>
    <form method="POST">
        <table border="1" cellpadding="8">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
            <?php while($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <select name="students[<?= $row['email'] ?>]">
                        <option value="Present">Present</option>
                        <option value="Absent" selected>Absent</option>
                    </select>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <button type="submit">Save Attendance</button>
    </form>
</body>
</html>
