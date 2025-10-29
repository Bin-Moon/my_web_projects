<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "ICE_info";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $email = trim($conn->real_escape_string($_POST['email']));
  $role  = trim($conn->real_escape_string($_POST['role']));

  if (!empty($email) && !empty($role)) {
    if ($role == "student") {
      $table = "s_info";
    } elseif ($role == "teacher") {
      $table = "teacher";
    } elseif ($role == "cr") {
      $table = "CR";
    } else {
      echo "<script>alert('Invalid role');</script>";
      exit;
    }

    $query = "SELECT * FROM $table WHERE email='$email' LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
      $_SESSION['reset_email'] = $email;
      $_SESSION['reset_role']  = $role;
      header("Location: reset_pass.php");
      exit;
    } else {
      echo "<script>alert('No account found with that email');</script>";
    }
  } else {
    echo "<script>alert('Please enter your email and select role');</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f7fe;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .box {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      margin-top: 15px;
      padding: 10px 25px;
      background-color: #0a69ff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #094bcc;
    }
  </style>
</head>
<body>
  <form class="box" method="POST">
    <h2>Forgot Password</h2>
    <label>Email:</label>
    <input type="email" name="email" placeholder="Enter your registered email" required>

    <label>Role:</label>
    <select name="role" required>
      <option value="">Select your role</option>
      <option value="student">Student</option>
      <option value="teacher">Teacher</option>
      <option value="cr">Class Representative</option>
    </select>

    <button type="submit">Verify Email</button>
    <p style="margin-top:15px;">
      <a href="login.php" style="color:#0a69ff; text-decoration:none;">Back to Login</a>
    </p>
  </form>
</body>
</html>
