<?php
session_start();

// Only teachers can access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    die("Access denied! Please login as teacher.");
}

// Include your existing DB connection
include 'db.php';

// Get academic year & semester from dashboard link
$year = isset($_GET['year']) ? $_GET['year'] : "";
$semester = isset($_GET['semester']) ? $_GET['semester'] : "";

$upload_error = "";
$success_msg = "";

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_assignment'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if(!empty($_FILES['pdf_file']['name'])) {
        $target_dir = "uploads/assignments/";

        // Create folder if it doesn't exist
        if(!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES['pdf_file']['name']);
        $file_path = $target_dir . time() . "_" . $file_name;
        $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        if($file_type != "pdf") {
            $upload_error = "❌ Only PDF files are allowed!";
        } else {
            if(move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $file_path)) {
                // SQL query to store assignment info
                $stmt = $conn->prepare("INSERT INTO assignments (title, description, file_path, academic_year, semester, upload_date) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssss", $title, $description, $file_path, $year, $semester);

                if($stmt->execute()) {
                    $success_msg = "✅ Assignment uploaded successfully!";
                } else {
                    $upload_error = "❌ Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $upload_error = "❌ File upload failed!";
            }
        }
    } else {
        $upload_error = "❌ Please select a PDF file!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Assignment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4 border-0 rounded-4">
        <h3 class="mb-4 text-center text-primary">📘 Upload Assignment</h3>

        <!-- Show selected academic year & semester -->
        <div class="alert alert-info">
            <strong>Academic Session:</strong> <?= htmlspecialchars($year) ?> |
            <strong>Semester:</strong> <?= htmlspecialchars($semester) ?>
        </div>

        <!-- Error or Success Message -->
        <?php if($upload_error): ?>
            <div class="alert alert-danger"><?= $upload_error ?></div>
        <?php elseif($success_msg): ?>
            <div class="alert alert-success"><?= $success_msg ?></div>
        <?php endif; ?>

        <!-- Upload Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Assignment Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter assignment title" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description (optional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Write a short description..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload PDF File</label>
                <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
            </div>

            <button type="submit" name="upload_assignment" class="btn btn-primary w-100">Upload Assignment</button>
        </form>

        <div class="text-center mt-3">
            <a href="teacher_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
