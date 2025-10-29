<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: teacher_login.php");
    exit;
}

require_once "db.php"; // your DB connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard - Notices</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f7fa;
            padding: 20px;
        }

        h2 {
            color: #2e5dd7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #2e5dd7;
            color: white;
        }

        a {
            text-decoration: none;
            color: #2e5dd7;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h2>Notices</h2>

    <?php
    $result = $conn->query("SELECT * FROM teac_notices ORDER BY id DESC");

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>View Online</th><th>Download</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $file_path = "uploads/notices/" . $row['file_name'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td><a href='$file_path' target='_blank'>View</a></td>";
            echo "<td><a href='$file_path' download>Download</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No notices uploaded yet.</p>";
    }
    ?>

</body>

</html>