<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_role'])) {
  header("Location: forgot_pass.php");
  exit;
}

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "ice_info";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['reset_email'];
$role  = $_SESSION['reset_role'];

if ($role == "student") {
  $table = "s_info";
} elseif ($role == "teacher") {
  $table = "teacher";
} elseif ($role == "cr") {
  $table = "CR";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $new_pass = trim($_POST['new_pass']);
  $confirm_pass = trim($_POST['confirm_pass']);

  if (!empty($new_pass) && !empty($confirm_pass)) {
    if ($new_pass === $confirm_pass) {
      // Hash password only for students
      if ($role == "student") {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $query = "UPDATE $table SET password='$hashed' WHERE email='$email'";
      } else {
        $query = "UPDATE $table SET password='$new_pass' WHERE email='$email'";
      }

      if ($conn->query($query)) {
        echo "<script>alert('Password updated successfully'); window.location='login.php';</script>";
        session_unset();
        session_destroy();
        exit;
      } else {
        echo "<script>alert('Error updating password');</script>";
      }
    } else {
      echo "<script>alert('Passwords do not match');</script>";
    }
  } else {
    echo "<script>alert('Please fill all fields');</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f4f7fe;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: Arial, sans-serif;
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
    .password-container {
      position: relative;
      width: 100%;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    .password-container input {
      padding-right: 40px;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #666;
    }
    button {
      margin-top: 20px;
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
    <h2>Reset Your Password</h2>
    <p style="color:#555;">Account: <b><?php echo htmlspecialchars($email); ?></b></p>

    <div class="password-container">
      <input type="password" name="new_pass" id="new_pass" placeholder="Enter new password" required>
      <i class="fa fa-eye toggle-password" id="toggleNewPass"></i>
    </div>

    <div class="password-container">
      <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm new password" required>
      <i class="fa fa-eye toggle-password" id="toggleConfirmPass"></i>
    </div>

    <button type="submit">Update Password</button>
  </form>

  <script>
    const toggleNewPass = document.getElementById('toggleNewPass');
    const newPassField = document.getElementById('new_pass');
    const toggleConfirmPass = document.getElementById('toggleConfirmPass');
    const confirmPassField = document.getElementById('confirm_pass');

    toggleNewPass.addEventListener('click', function() {
      const type = newPassField.getAttribute('type') === 'password' ? 'text' : 'password';
      newPassField.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    toggleConfirmPass.addEventListener('click', function() {
      const type = confirmPassField.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassField.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
