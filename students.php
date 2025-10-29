<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    die("Unauthorized access! <a href='admin_login.php'>Login</a>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Students</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f7fa;
    padding: 20px;
}
h1 {
    color: #2e5dd7;
    text-align: center;
}
.options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 30px;
}
.option-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.option-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
.option-card i {
    font-size: 36px;
    margin-bottom: 10px;
    color: #2e5dd7;
}
.option-card span {
    display: block;
    margin-top: 10px;
    font-weight: 600;
    font-size: 16px;
}
</style>
<script src="https://kit.fontawesome.com/yourfontawesomekey.js" crossorigin="anonymous"></script>
</head>
<body>

<h1>Manage Students</h1>

<div class="options">
    <div class="option-card" onclick="window.location.href='up_official_notice.php'">
        <i class="fas fa-file-upload"></i>
        <span>Upload Notice</span>
    </div>

    <div class="option-card" onclick="window.location.href='upload_courses.php'">
        <i class="fas fa-book"></i>
        <span>Upload Courses</span>
    </div>

    <div class="option-card" onclick="window.location.href='admin_id_approve.php'">
        <i class="fas fa-user-graduate"></i>
        <span>Student ID</span>
    </div>

    <div class="option-card" onclick="window.location.href='hall_interview.php'">
        <i class="fas fa-building"></i>
        <span>Hall Interview</span>
    </div>

    <div class="option-card" onclick="window.location.href='admin_certificate.php'">
        <i class="fas fa-certificate"></i>
        <span>Certificate</span>
    </div>
</div>

</body>
</html>
