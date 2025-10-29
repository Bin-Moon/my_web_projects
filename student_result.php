<?php
// Enable errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php'; // your DB connection

// Check if student is logged in
if (!isset($_SESSION['student_unique_id']) || $_SESSION['role'] != "student") {
    die("Please login as student!");
}

$student_id = $_SESSION['student_unique_id'];

$results = [];
$selected_year = "";
$selected_semester = "";

// Handle search
if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['year']) && isset($_GET['semester'])) {
    $selected_year = $conn->real_escape_string($_GET['year']);
    $selected_semester = $conn->real_escape_string($_GET['semester']);

    if (!empty($selected_year) && !empty($selected_semester)) {
        $sql = "SELECT course_name, marks, grade 
                FROM results 
                WHERE student_unique_id = '$student_id' 
                AND academic_year = '$selected_year' 
                AND semester = '$selected_semester'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My CT Results</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
}
.container {
    max-width: 800px;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #2e5dd7;
}
form {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}
input, button {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    background-color: #2e5dd7;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background-color: #1a3ca8;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background-color: #2e5dd7;
    color: white;
}
.no-data {
    text-align: center;
    color: #999;
    font-style: italic;
    margin-top: 20px;
}
</style>
</head>
<body>
<div class="container">
    <h2>My CT Results</h2>

    <form method="GET" action="">
        <input 
            type="text" 
            name="year" 
            placeholder="e.g. 2021-2022" 
            value="<?php echo htmlspecialchars($selected_year); ?>" 
            required>

        <input 
            type="text" 
            name="semester" 
            placeholder="e.g. Semester 5" 
            value="<?php echo htmlspecialchars($selected_semester); ?>" 
            required>

        <button type="submit">View</button>
    </form>

    <?php if (!empty($selected_year) && !empty($selected_semester)): ?>
        <?php if (!empty($results)): ?>
            <table>
                <tr>
                    <th>Course Name</th>
                    <th>Marks</th>
                    <th>Grade</th>
                </tr>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($r['marks']); ?></td>
                        <td><?php echo htmlspecialchars($r['grade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No results found for this year and semester.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
