<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only allow students
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'student'){
    die("Unauthorized");
}

require_once 'db.php'; 

$schedule_text = '';
$year = '';
$semester = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $year = trim($_POST['year'] ?? '');
    $semester = trim($_POST['semester'] ?? '');

    if(!empty($year) && !empty($semester)){
        $sql = "SELECT schedule_text FROM class_schedule WHERE year='$year' AND semester='$semester' LIMIT 1";
        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            $row = $result->fetch_assoc();
            $schedule_text = $row['schedule_text'];
        } else {
            $schedule_text = "No schedule available for the entered year and semester.";
        }
    } else {
        $schedule_text = "Please enter both year and semester.";
    }
}

$conn->close();

// Function to highlight dates (simple yyyy-mm-dd or dd-mm-yyyy)
function highlight_dates($text) {
    $pattern = '/\b(\d{4}-\d{2}-\d{2}|\d{2}-\d{2}-\d{4})\b/';
    return preg_replace($pattern, '<span class="date-highlight">$1</span>', htmlspecialchars($text));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f5f7;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            padding: 10px 15px;
            border: none;
            background-color: #4CAF50;
            color: #fff;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .schedule {
            white-space: pre-wrap;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .message {
            color: red;
            text-align: center;
        }
        .date-highlight {
            background-color: #fffa91;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Class Schedule</h2>

    <form method="POST" action="">
        <input type="text" name="year" placeholder="Enter Year (e.g., 2023-2024)" value="<?php echo htmlspecialchars($year); ?>" required>
        <input type="text" name="semester" placeholder="Enter Semester (e.g., 1)" value="<?php echo htmlspecialchars($semester); ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if(!empty($schedule_text) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="schedule"><?php echo highlight_dates($schedule_text); ?></div>
    <?php elseif($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="message"><?php echo $schedule_text; ?></div>
    <?php endif; ?>
</div>

</body>
</html>
