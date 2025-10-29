<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    die("Unauthorized access! <a href='admin_login.php'>Login</a>");
}

require_once "db.php"; // include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $file = $_FILES['notice_file'];

    if ($file['error'] == 0) {
        $fileName = basename($file['name']);
        $targetDir = "uploads/notices/";
        $targetFile = $targetDir . time() . "_" . $fileName;

        // create folder if not exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // upload file
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO official_notices (title, file_name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $title, $targetFile);
            $stmt->execute();
            echo "<script>alert('Notice uploaded successfully!');</script>";
        } else {
            echo "<script>alert('Failed to upload file!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Official Notice</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f4f7fa;
  padding: 30px;
}
form {
  background: white;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  width: 400px;
  margin: auto;
}
h2 {
  text-align: center;
  color: #2e5dd7;
}
input[type="text"], input[type="file"] {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
}
button {
  width: 100%;
  padding: 10px;
  background-color: #2e5dd7;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}
button:hover {
  background-color: #1b45a3;
}
</style>
</head>
<body>
<h2>Upload Official Notice</h2>
<form method="POST" enctype="multipart/form-data">
  <label>Notice Title:</label>
  <input type="text" name="title" required>
  <label>Upload PDF:</label>
  <input type="file" name="notice_file" accept="application/pdf" required>
  <button type="submit">Upload Notice</button>
</form>
</body>
</html>
