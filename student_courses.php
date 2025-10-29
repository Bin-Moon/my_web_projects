<?php
require_once "db.php";

$courses = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    $sql = "SELECT * FROM courses WHERE year='$year' AND semester='$semester'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Courses</title>
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #f5f7fa;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 850px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #2e5dd7;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            margin: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 14px;
            background-color: #2e5dd7;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2e5dd7;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 View Courses</h2>

    <form method="POST">
        <input type="text" name="year" placeholder="Enter Year (e.g. 1st)" required>
        <input type="text" name="semester" placeholder="Enter Semester (e.g. 2nd)" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <?php if (count($courses) > 0): ?>
            <table>
                <tr>
                    <th>SI No</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Credit</th>
                    <th>Course Type</th>
                </tr>
                <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= $c['si_no'] ?></td>
                        <td><?= $c['course_code'] ?></td>
                        <td><?= $c['course_title'] ?></td>
                        <td><?= $c['credit'] ?></td>
                        <td><?= $c['course_type'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:red;">No courses found for this year and semester.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
