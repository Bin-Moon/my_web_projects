<?php
session_start();
include 'db.php';

// only students should see this page
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY si_no DESC");

// Fetch tasks
$tasks = $conn->query("SELECT * FROM tasks ORDER BY si_no DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Notice</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f6fa;
        margin: 0;
        padding: 20px;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .notice-box {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .notice-box h3 {
        margin: 0 0 8px;
        color: #2e5dd7;
    }
    .notice-box p {
        margin: 4px 0;
    }
    .date {
        font-size: 12px;
        color: gray;
    }
</style>
</head>
<body>

<h2>📢 Notices</h2>

<!-- Announcements -->
<?php while($row = $announcements->fetch_assoc()): ?>
    <div class="notice-box">
        <h3>📢 Announcement</h3>
        <p><strong>Year:</strong> <?= htmlspecialchars($row['year']) ?> | 
           <strong>Semester:</strong> <?= htmlspecialchars($row['semester']) ?> | 
           <strong>Course:</strong> <?= htmlspecialchars($row['course']) ?></p>
        <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <p class="date">Posted on: <?= $row['created_at'] ?? '' ?></p>
    </div>
<?php endwhile; ?>

<!-- Tasks -->
<?php while($row = $tasks->fetch_assoc()): ?>
    <div class="notice-box">
        <h3>📝 Task</h3>
        <p><strong>Title:</strong> <?= htmlspecialchars($row['title']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <p><strong>Due Date:</strong> <?= htmlspecialchars($row['due_date']) ?></p>
        <p><strong>Year:</strong> <?= htmlspecialchars($row['year']) ?> | 
           <strong>Semester:</strong> <?= htmlspecialchars($row['semester']) ?></p>
    </div>
<?php endwhile; ?>

</body>
</html>
