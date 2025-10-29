<?php
session_start();
require_once "db.php";

// Check Student login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    die("Please login as Student!");
}

$student_name = $_SESSION['student_name'];

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender_name, sender_role, message) VALUES (?, 'Student', ?)");
        $stmt->bind_param("ss", $student_name, $message);
        $stmt->execute();
    }
}

// Fetch all chat messages
$messages = $conn->query("SELECT * FROM chat_messages ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Chat</title>
</head>
<body>
<h2>Student Chat</h2>

<div style="height:300px; overflow-y:scroll; border:1px solid #ccc; padding:10px;">
<?php
if ($messages->num_rows > 0) {
    while ($row = $messages->fetch_assoc()) {
        echo "<b>".$row['sender_name']." (".$row['sender_role']."):</b> ".htmlspecialchars($row['message'])."<br>";
    }
} else {
    echo "No messages yet.";
}
?>
</div>

<form method="POST">
    <input type="text" name="message" placeholder="Type your message" required>
    <button type="submit">Send</button>
</form>
</body>
</html>
