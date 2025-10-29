<?php
require_once "db.php"; // DB connection
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Check Hall Interview Date</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 40px;
}
.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
}
h2 {
    color: #2e5dd7;
    margin-bottom: 20px;
}
form {
    margin-bottom: 25px;
}
input {
    padding: 10px;
    width: 80%;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
}
button {
    margin-top: 15px;
    padding: 10px 20px;
    background: #2e5dd7;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
button:hover {
    background: #1f3bb3;
}
p {
    font-size: 18px;
    color: #333;
}
.status {
    font-weight: bold;
    font-size: 20px;
    margin-top: 15px;
}
.success { color: green; }
.pending { color: orange; }
.error { color: red; }
a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #2e5dd7;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}
a:hover { background: #1f3bb3; }
</style>
</head>
<body>
<div class="container">
    <h2>Check Hall Interview Date</h2>

    <form method="post">
        <input type="text" name="student_unique_id" placeholder="Enter your Student ID" required>
        <button type="submit">Check Status</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_unique_id = trim($_POST['student_unique_id']);

        // Fetch the hall application for this student
        $sql = "SELECT * FROM hall_applications WHERE student_unique_id = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $application = $result->fetch_assoc();

        if (!$application) {
            echo "<p class='error'>❌ No hall application found for this Student ID.</p>";
        } else {
            echo "<p><strong>Name:</strong> " . htmlspecialchars($application['name']) . "</p>";
            echo "<p><strong>Department:</strong> " . htmlspecialchars($application['department']) . "</p>";
            echo "<p><strong>Hall:</strong> " . htmlspecialchars($application['hall_name']) . "</p>";
            echo "<p><strong>Applied On:</strong> " . date("d M Y, h:i A", strtotime($application['application_date'])) . "</p>";

            if ($application['interview_date']) {
                echo "<p class='status success'>✅ Interview Date: " . 
                     date("d M Y, h:i A", strtotime($application['interview_date'])) . "</p>";
            } else {
                echo "<p class='status pending'>⏳ Interview date not assigned yet.</p>";
            }
        }
    }
    ?>

    <a href="hall_admission.php">← Back to Hall Admission</a>
</div>
</body>
</html>
