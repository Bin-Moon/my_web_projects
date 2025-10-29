<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    die("Unauthorized access! <a href='admin_login.php'>Login</a>");
}
require_once "db.php";

// Handle assigning interview date
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['app_id'], $_POST['interview_date'])){
    $app_id = $_POST['app_id'];
    $interview_date = $_POST['interview_date'];

    $sql = "UPDATE hall_applications SET interview_date=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $interview_date, $app_id);
    $stmt->execute();
}

// Fetch all hall applications
$sql = "SELECT * FROM hall_applications ORDER BY application_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hall Interview - Admin</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f7fa; padding: 20px; }
h1 { text-align: center; color: #2e5dd7; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
th { background-color: #2e5dd7; color: white; }
tr:hover { background-color: #f1f1f1; }
input[type="datetime-local"] { padding: 5px; border-radius: 6px; border: 1px solid #ccc; }
button { padding: 6px 10px; border: none; background: #2e5dd7; color: white; border-radius: 6px; cursor: pointer; }
button:hover { background-color: #1f3bb3; }
</style>
</head>
<body>

<h1>Hall Interview - Admin Panel</h1>

<table>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Department</th>
        <th>Session</th>
        <th>Year</th>
        <th>Semester</th>
        <th>Student ID</th>
        <th>Hall</th>
        <th>Application Date</th>
        <th>Interview Date</th>
        <th>Assign Interview</th>
    </tr>
    <?php
    $count = 1;
    while($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $count++ ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['department']) ?></td>
        <td><?= htmlspecialchars($row['session']) ?></td>
        <td><?= htmlspecialchars($row['year']) ?></td>
        <td><?= htmlspecialchars($row['semester']) ?></td>
        <td><?= htmlspecialchars($row['student_unique_id']) ?></td>
        <td><?= htmlspecialchars($row['hall_name']) ?></td>
        <td><?= date("d M Y, h:i A", strtotime($row['application_date'])) ?></td>
        <td>
            <?php
            if($row['interview_date']){
                echo date("d M Y, h:i A", strtotime($row['interview_date']));
            } else {
                echo "Not Assigned";
            }
            ?>
        </td>
        <td>
            <?php if(!$row['interview_date']): ?>
            <form method="post" style="display:flex; gap:5px;">
                <input type="datetime-local" name="interview_date" required>
                <input type="hidden" name="app_id" value="<?= $row['id'] ?>">
                <button type="submit">Assign</button>
            </form>
            <?php else: ?>
                ✅
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
