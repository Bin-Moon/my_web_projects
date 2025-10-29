<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit;
}

require_once "db.php"; // your DB connection

$upload_error = "";
$success_msg = "";

// Handle PDF upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_notice'])) {
    $title = trim($_POST['title']);
    $file = $_FILES['pdf_file'];

    $allowed_types = ['application/pdf'];
    if (in_array($file['type'], $allowed_types)) {
        $target_dir = __DIR__ . "/uploads/notices/"; // absolute path
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($file['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO teac_notices (title, file_name) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $file_name);
            if ($stmt->execute()) {
                $success_msg = "Notice uploaded successfully!";
            } else {
                $upload_error = "Database error: " . $stmt->error;
            }
        } else {
            $upload_error = "Failed to move uploaded file. Check folder permissions.";
        }
    } else {
        $upload_error = "Only PDF files are allowed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Upload Notice</title>
    <style>
        body { font-family: Arial; background: #f4f7fa; padding: 20px; }
        h2 { color: #2e5dd7; }
        form { margin-bottom: 20px; }
        input[type=text], input[type=file] { padding: 8px; width: 300px; margin: 5px 0; }
        button { padding: 8px 15px; background: #2e5dd7; color: white; border: none; cursor: pointer; }
        button:hover { background: #1743a1; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>Upload Notice</h2>

<?php if ($upload_error): ?>
    <p class="error"><?= $upload_error ?></p>
<?php endif; ?>

<?php if ($success_msg): ?>
    <p class="success"><?= $success_msg ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Notice Title" required><br>
    <input type="file" name="pdf_file" accept="application/pdf" required><br>
    <button type="submit" name="upload_notice">Upload Notice</button>
</form>

</body>
</html>
