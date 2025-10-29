<?php
session_start();
require_once "db.php";

// Check if student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    die("Please login first! <a href='student_login.php'>Login</a>");
}

$student_id = $_SESSION['student_unique_id'];

// Fetch notifications
$notif_result = $conn->query("SELECT * FROM notifications WHERE student_unique_id='$student_id' ORDER BY created_at DESC");

// Optional: mark all notifications as read
$conn->query("UPDATE notifications SET status='read' WHERE student_unique_id='$student_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Notifications</title>
<style>
body { font-family: 'Poppins', sans-serif; background: #f3f6fa; padding: 20px; }
h2 { color: #2e5dd7; text-align:center; margin-bottom: 20px; }
.notification-box { max-width: 600px; margin: auto; background: white; border-radius: 12px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);}
.notification-box ul { list-style: none; padding: 0; }
.notification-box li { padding: 10px; border-bottom: 1px solid #eee; margin-bottom: 8px; font-weight: 500; }
.notification-box li.unread { font-weight: 600; }
.notification-box li small { color: gray; font-size: 12px; display: block; margin-top: 5px; }
</style>
</head>
<body>

<h2>🔔 My Notifications</h2>
<div class="notification-box">
    <ul>
        <?php if($notif_result->num_rows > 0): ?>
            <?php while($n = $notif_result->fetch_assoc()): ?>
                <li class="<?php echo $n['status']=='unread'?'unread':''; ?>">
                    <?php echo htmlspecialchars($n['message']); ?>
                    <small><?php echo $n['created_at']; ?></small>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No notifications yet.</li>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>
