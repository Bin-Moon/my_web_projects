<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['email']) || strtolower($_SESSION['role']) != 'student'){
    die("Unauthorized");
}

require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student - Class Routine</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

    <h2 class="mb-4">📘 Class Routine</h2>

    <!-- Search Form -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-5">
            <label for="year" class="form-label">Academic Year</label>
            <input type="text" name="year" id="year" class="form-control" 
                   placeholder="e.g. 2023-2024" value="<?= htmlspecialchars($_GET['year'] ?? '') ?>">
        </div>

        <div class="col-md-5">
            <label for="semester" class="form-label">Semester</label>
            <input type="text" name="semester" id="semester" class="form-control" 
                   placeholder="e.g.5" value="<?= htmlspecialchars($_GET['semester'] ?? '') ?>">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">🔍 Search</button>
        </div>
    </form>

    <?php
    if(!empty($_GET['year']) && !empty($_GET['semester'])){
        $year = trim($_GET['year']);
        $semester = trim($_GET['semester']);

        $sql = "SELECT file_path, uploaded_at 
                FROM class_routines 
                WHERE year = ? AND semester = ? 
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $year, $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $file_path = $row['file_path'];
            $uploaded_at = $row['uploaded_at'];

            echo "<div class='card p-3 shadow-sm'>
                    <h5>Routine Found</h5>
                    <p><b>Year:</b> $year | <b>Semester:</b> $semester</p>
                    <p><small>Uploaded at: $uploaded_at</small></p>
                    <a href='$file_path' target='_blank' class='btn btn-success mb-3'>📂 View / Download Routine</a>
                    <iframe src='$file_path' width='100%' height='500px' style='border:1px solid #ccc;'></iframe>
                 </div>";
        } else {
            echo "<p class='text-danger'>⚠️ No routine uploaded for <b>$year</b>, <b>$semester</b>.</p>";
        }
    }
    ?>

</body>
</html>
