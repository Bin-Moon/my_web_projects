<?php
session_start();

// Only students can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    die("Access denied! Please login as a student.");
}

include 'db.php'; // your database connection

$assignments = [];
$year = $semester = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'] ?? '';
    $semester = $_POST['semester'] ?? '';

    if ($year && $semester) {
        $stmt = $conn->prepare("SELECT id, title, description, file_path, upload_date FROM assignments WHERE academic_year=? AND semester=? ORDER BY upload_date DESC");
        $stmt->bind_param("ss", $year, $semester);
        $stmt->execute();
        $result = $stmt->get_result();
        $assignments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Assignments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow p-4 rounded-4">
            <h3 class="mb-4 text-center text-primary">📘 View Assignments</h3>

            <!-- Year & Semester Selection -->
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Academic Session</label>
                    <select name="year" class="form-select" required>
                        <option value="" disabled selected>Select session</option>
                        <option <?= $year == "2020-2021" ? "selected" : "" ?>>2020-2021</option>
                        <option <?= $year == "2021-2022" ? "selected" : "" ?>>2021-2022</option>
                        <option <?= $year == "2022-2023" ? "selected" : "" ?>>2022-2023</option>
                        <option <?= $year == "2023-2024" ? "selected" : "" ?>>2023-2024</option>
                        <option <?= $year == "2024-2025" ? "selected" : "" ?>>2024-2025</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="" disabled selected>Select semester</option>
                        <?php
                        for ($i = 1; $i <= 8; $i++) {
                            $sem = "Semester $i";
                            $selected = ($semester == $sem) ? "selected" : "";
                            echo "<option $selected>$sem</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">View Assignments</button>
                </div>
            </form>

            <!-- Show Assignments -->
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <?php if (empty($assignments)): ?>
                    <div class="alert alert-warning text-center">No assignments found for <?= htmlspecialchars($year) ?> - <?= htmlspecialchars($semester) ?>.</div>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Upload Date</th>
                                <th>View</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $assign): ?>
                                <tr>
                                    <td><?= htmlspecialchars($assign['title']) ?></td>
                                    <td><?= htmlspecialchars($assign['description']) ?></td>
                                    <td><?= date("d M Y, H:i", strtotime($assign['upload_date'])) ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($assign['file_path']) ?>" class="btn btn-sm btn-info" target="_blank">
                                            View PDF
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= htmlspecialchars($assign['file_path']) ?>" download class="btn btn-sm btn-success">
                                            Download PDF
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            <?php endif; ?>

            <div class="text-center mt-3">
                <a href="student_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
            </div>
        </div>
    </div>

</body>

</html>