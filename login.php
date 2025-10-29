<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "ice_info";

// Connect
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Handle login
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $email    = trim($conn->real_escape_string($_POST['email']));
  $password = trim($conn->real_escape_string($_POST['password']));
  $role     = trim($conn->real_escape_string($_POST['role']));

  if (!empty($email) && !empty($password) && !empty($role)) {
    if ($role == "student") {
      $query = "SELECT * FROM s_info WHERE email='$email' LIMIT 1";
    } elseif ($role == "teacher") {
      $query = "SELECT * FROM teacher WHERE email='$email' LIMIT 1";
    } elseif ($role == "cr") {
      $query = "SELECT * FROM CR WHERE email='$email' LIMIT 1";
    } else {
      echo "<script>alert('Invalid role selected');</script>";
      exit;
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);

      // Verify password differently depending on role
      $isPasswordValid = false;

      if ($role == "student") {
        // students table uses hashed passwords
        $isPasswordValid = password_verify($password, $user_data['password']);
      } else {
        // teacher and CR tables use plain text passwords
        $isPasswordValid = ($password === $user_data['password']);
      }

      if ($isPasswordValid) {
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['role']  = $role;

        if ($role == "student") {
          $_SESSION['student_unique_id'] = $user_data['student_code'];
          $_SESSION['student_name'] = $user_data['Name'];
          header("Location: student_dashboard.php");
        } elseif ($role == "teacher") {
          header("Location: teachers_dashboard.html");
        } elseif ($role == "cr") {
          $_SESSION['cr_unique_id'] = $user_data['id']; 
          $_SESSION['cr_name'] = $user_data['Name'];    
          header("Location: CRs_dashboard.php");
        }
        exit;
      } else {
        echo "<script>alert('Wrong email or password');</script>";
      }
    } else {
      echo "<script>alert('Wrong email or password');</script>";
    }
  } else {
    echo "<script>alert('Please fill all fields');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background-color: #f4f7fe;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
      position: relative;
    }

    .login-box h2 {
      margin-bottom: 20px;
    }

    .login-box select,
    .login-box input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    .password-container {
      position: relative;
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

    .login-btn {
      margin-top: 20px;
      padding: 10px 25px;
      background-color: #0a69ff;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .login-btn:hover {
      background-color: #0953cc;
    }
  </style>
</head>

<body>
  <form class="login-box" action="login.php" method="POST">
    <h2>University Login</h2>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required>

    <label for="role">Role</label>
    <select id="role" name="role" required>
      <option value="">Select your role</option>
      <option value="student">Student</option>
      <option value="teacher">Teacher</option>
      <option value="cr">Class Representative</option>
    </select>

    <label for="password">Password</label>
    <div class="password-container">
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
      <i class="fa fa-eye toggle-password" id="togglePassword"></i>
    </div>

    <button type="submit" class="login-btn">Login</button>

    <!-- Forgot Password link -->
    <p style="margin-top: 15px;">
      <a href="forgot_pass.php" style="color:#0a69ff; text-decoration:none;">Forgot Password?</a>
    </p>
  </form>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);

      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>

</html>
