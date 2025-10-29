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
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            padding: 20px;
        }
        h1 {
            color: #2e5dd7;
        }
        .nav {
            margin: 20px 0;
        }
        .nav a {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            background: #2e5dd7;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .nav a:hover {
            background: #1743a1;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin</h1>
    <p>You are logged in as <b><?= $_SESSION['email'] ?></b></p>

    <div class="nav">
        <a href="students.php" >Manage Students</a>
        <a href="manage_cr.php">Manage CRs</a>
        <a  onclick="window.location.href='admin_notices.php'">Manage Notices</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
