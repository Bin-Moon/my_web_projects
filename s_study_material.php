<?php
session_start();
include 'db.php';

//  Only student can view
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

//  Collect dropdown filters
$year     = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$course   = $_GET['course'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Study Materials</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }

        a {
            color: blue;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <h2>📘 Study Materials</h2>

    <!-- Filter Form -->
    <form method="get" action="">
        <label>Year:
            <input type="text" name="year"
                value="<?= htmlspecialchars($year) ?>"
                placeholder="e.g. 2023-2024">
        </label>
        <label>Semester:
            <input type="text" name="semester"
                value="<?= htmlspecialchars($semester) ?>"
                placeholder="e.g. 5">
        </label>
        <label>Course:
            <input type="text" name="course"
                value="<?= htmlspecialchars($course) ?>"
                placeholder="e.g. ICE1101">
        </label>
        <button type="submit">Search</button>
    </form>


    <?php
    if ($year && $semester && $course) {
        // Fetch from DB
        $stmt = $conn->prepare("SELECT title, file_path, uploaded_at FROM study_materials WHERE year=? AND semester=? AND course=? ORDER BY uploaded_at DESC");
        $stmt->bind_param("sss", $year, $semester, $course);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>
                <tr>
                    <th>Title</th>
                    <th>File</th>
                    <th>Uploaded At</th>
                </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td><a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>Download</a></td>
                    <td>" . $row['uploaded_at'] . "</td>
                  </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>⚠️ No materials found for this selection.</p>";
        }

        $stmt->close();
    }
    $conn->close();
    ?>

</body>

</html>