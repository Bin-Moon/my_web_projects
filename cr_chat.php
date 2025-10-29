<?php
session_start();
require_once "db.php";

// Check CR login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cr') {
    die("Please login as CR!");
}

// CR name from login session
$cr_name = $_SESSION['cr_name'] ?? "CR";

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender_name, sender_role, message) VALUES (?, 'CR', ?)");
        $stmt->bind_param("ss", $cr_name, $message);
        $stmt->execute();
    }
}

// Fetch all chat messages
$messages = $conn->query("SELECT * FROM chat_messages ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CR Chat</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fe; padding: 20px; }
        h2 { text-align: center; color: #2e5dd7; }
        #chat-box { 
            height: 400px; 
            overflow-y: scroll; 
            border: 1px solid #ccc; 
            padding: 10px; 
            background: #fff;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .message { margin-bottom: 8px; }
        .message b { color: #2e5dd7; }
        form { display: flex; gap: 10px; }
        input[type="text"] { flex: 1; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 20px; background: #2e5dd7; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1d3cb8; }
    </style>
</head>
<body>

<h2>CR Chat</h2>

<div id="chat-box">
    <?php
    if ($messages->num_rows > 0) {
        while ($row = $messages->fetch_assoc()) {
            echo "<div class='message'><b>" . htmlspecialchars($row['sender_name']) . " (" . $row['sender_role'] . "):</b> " . htmlspecialchars($row['message']) . "</div>";
        }
    } else {
        echo "No messages yet.";
    }
    ?>
</div>

<form method="POST">
    <input type="text" name="message" placeholder="Type your message..." required>
    <button type="submit">Send</button>
</form>

</body>
</html>
