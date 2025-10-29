<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only process if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Name'])) {
    
    // Database connection settings
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $database   = "ICE_info";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set charset to prevent encoding issues
    $conn->set_charset("utf8");

    // Collect and sanitize form data
    $Name          = $conn->real_escape_string($_POST['Name']);
    $f_name        = $conn->real_escape_string($_POST['f_name']);
    $m_name        = $conn->real_escape_string($_POST['m_name']);
    $dob           = $conn->real_escape_string($_POST['dob']);
    $mobile        = $conn->real_escape_string($_POST['mobile']);
    $g_name        = $conn->real_escape_string($_POST['g_name']);
    $g_mobile      = $conn->real_escape_string($_POST['g_mobile']);
    $hall          = $conn->real_escape_string($_POST['hall']);
    $blood_group   = $conn->real_escape_string($_POST['blood_group']);
    $religion      = $conn->real_escape_string($_POST['religion']);
    $nationality   = $conn->real_escape_string($_POST['nationality']);
    $nid_birth     = $conn->real_escape_string($_POST['nid_birth']);
    $gender        = isset($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : '';
    $marital_status= isset($_POST['marital_status']) ? $conn->real_escape_string($_POST['marital_status']) : '';
    $living_at     = isset($_POST['living_at']) ? $conn->real_escape_string($_POST['living_at']) : '';
    $division      = $conn->real_escape_string($_POST['division']);
    $district      = $conn->real_escape_string($_POST['district']);
    $upazilla      = $conn->real_escape_string($_POST['upazilla']);
    $village       = $conn->real_escape_string($_POST['village']); // Fixed typo
    $post_office   = $conn->real_escape_string($_POST['post_office']); // Fixed typo
    $house_no      = $conn->real_escape_string($_POST['house_no']);
    $house_name    = $conn->real_escape_string($_POST['house_name']);
    $road_no       = $conn->real_escape_string($_POST['road_no']); // Fixed typo
    $department    = $conn->real_escape_string($_POST['department']);
    $faculty       = $conn->real_escape_string($_POST['faculty']);
    $level         = $conn->real_escape_string($_POST['level']);
    $session       = $conn->real_escape_string($_POST['session']);
    $year_term     = $conn->real_escape_string($_POST['year_term']);
    $student_code  = $conn->real_escape_string($_POST['student_code']);
    $email         = $conn->real_escape_string($_POST['email']);
    $password      = $conn->real_escape_string($_POST['password']);
    
    // Handle file upload
    $student_image = '';
    if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['student_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $student_image = $upload_dir . uniqid() . '.' . $file_extension;
            move_uploaded_file($_FILES['student_image']['tmp_name'], $student_image);
        }
    }
    
    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = "SELECT email FROM s_info WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.history.back();</script>";
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Use prepared statements for security
    $sql = "INSERT INTO s_info (
        name, f_name, m_name, dob, mobile, g_name, g_mobile, hall, 
        blood_group, religion, nationality, nid_birth, gender, marital_status,
        living_at, division, district, upazilla, village, post_office, 
        house_no, house_name, road_no, department, faculty, level, 
        session, year_term, student_code, student_image, email, password, c_date
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
    )";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssssssssssssssssssssssssssss", 
            $Name, $f_name, $m_name, $dob, $mobile, $g_name, $g_mobile, 
            $hall, $blood_group, $religion, $nationality, $nid_birth, 
            $gender, $marital_status, $living_at, $division, $district, 
            $upazilla, $village, $post_office, $house_no, $house_name, 
            $road_no, $department, $faculty, $level, $session, $year_term, 
            $student_code, $student_image, $email, $hashed_password
        );

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! ✅'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      padding: 20px;
    }

    .signup-form {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    input, select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .section-title {
      background-color: #051732;
      color: white;
      padding: 10px;
      margin-top: 20px;
      border-radius: 5px;
      text-align: center;
      font-weight: bold;
    }

    .radio-group, .gender-group {
      display: flex;
      gap: 15px;
      margin-top: 5px;
      flex-wrap: wrap;
    }

    .radio-group label, .gender-group label {
      display: flex;
      align-items: center;
      font-weight: normal;
      margin-top: 0;
    }

    .radio-group input, .gender-group input {
      width: auto;
      margin-right: 5px;
      margin-top: 0;
    }

    .submit-btn {
      background-color: #0a69ff;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
      width: 100%;
    }

    .submit-btn:hover {
      background-color: #0856cc;
    }

    .required {
      color: red;
    }

    .error {
      color: red;
      font-size: 14px;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <form class="signup-form" action="" method="post" enctype="multipart/form-data">
    <h2>Student Registration Form</h2>
    
    <div class="section-title">1. Personal Information</div>
    <label>Name:<span class="required">*</span></label>
    <input type="text" name="Name" placeholder="Enter Your Name" required>
    
    <label>Father's Name:<span class="required">*</span></label>
    <input name="f_name" type="text" required>
    
    <label>Mother's Name:<span class="required">*</span></label>
    <input name="m_name" type="text" required>
    
    <label>Date of Birth:<span class="required">*</span></label>
    <input name="dob" type="date" required>
    
    <label>Mobile Number:<span class="required">*</span></label>
    <input name="mobile" type="tel" pattern="[0-9]{11}" placeholder="01XXXXXXXXX" required>
    
    <label>Guardian's Name:<span class="required">*</span></label>
    <input name="g_name" type="text" required>
    
    <label>Guardian's Mobile:<span class="required">*</span></label>
    <input name="g_mobile" type="tel" pattern="[0-9]{11}" placeholder="01XXXXXXXXX" required>
    
    <label>Hall:<span class="required">*</span></label>
    <select name="hall" required>
      <option value="">--Select Your Hall--</option>
      <option value="Shahid Abdus Salam Hall">Shahid Abdus Salam Hall</option>
      <option value="Shahid abdul malik Hall">Shahid abdul malik Hall</option>
      <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall</option>
      <option value="Sheikh Fazilatunnesa Mujib Hall">Sheikh Fazilatunnesa Mujib Hall</option>
    </select>
    
    <label>Blood Group:<span class="required">*</span></label>
    <select name="blood_group" required>
      <option value="">--Select Your Blood Group--</option>
      <option value="A+">A+</option>
      <option value="A-">A-</option>
      <option value="B+">B+</option>
      <option value="B-">B-</option>
      <option value="AB+">AB+</option>
      <option value="AB-">AB-</option>
      <option value="O+">O+</option>
      <option value="O-">O-</option>
    </select>
    
    <label>Religion:<span class="required">*</span></label>
    <select name="religion" required>
      <option value="">--Select Your Religion--</option>
      <option value="Islam">Islam</option>
      <option value="Hinduism">Hinduism</option>
      <option value="Buddhism">Buddhism</option>
      <option value="Christianity">Christianity</option>
      <option value="Others">Others</option>
    </select>
    
    <label>Nationality:<span class="required">*</span></label>
    <select name="nationality" required>
      <option value="">--Select Your Nationality--</option>
      <option value="Bangladeshi">Bangladeshi</option>
      <option value="Others">Others</option>
    </select>
    
    <label>NID/Birth Registration:<span class="required">*</span></label>
    <input name="nid_birth" type="text" required>
    
    <label>Gender:<span class="required">*</span></label>
    <div class="gender-group">
      <label><input type="radio" name="gender" value="male" required> Male</label>
      <label><input type="radio" name="gender" value="female" required> Female</label>
      <label><input type="radio" name="gender" value="others" required> Others</label>
    </div>
    
    <label>Marital Status: <span class="required">*</span></label>
    <div class="radio-group">
      <label><input type="radio" name="marital_status" value="married"> Married</label>
      <label><input type="radio" name="marital_status" value="unmarried"> Unmarried</label>
    </div>

    <div class="section-title">2. Present Address</div>
    <label>Living At: <span class="required">*</span></label>
    <div class="radio-group">
      <label><input type="radio" name="living_at" value="hall"> Hall</label>
      <label><input type="radio" name="living_at" value="others"> Others</label>
    </div>

    <div class="section-title">3. Permanent Address</div>
    <label>Division:</label>
    <select name="division">
      <option value="">--Select Division--</option>
      <option value="Dhaka">Dhaka</option>
      <option value="Chattogram">Chattogram</option>
      <option value="Khulna">Khulna</option>
      <option value="Rajshahi">Rajshahi</option>
      <option value="Barishal">Barishal</option>
      <option value="Sylhet">Sylhet</option>
      <option value="Rangpur">Rangpur</option>
      <option value="Mymensingh">Mymensingh</option>
    </select>
    
    <label>District:</label>
    <select name="district">
      <option value="">--Select District--</option>
      <option value="Dhaka">Dhaka</option>
      <option value="Chattogram">Chattogram</option>
      <option value="Khulna">Khulna</option>
      <option value="Rajshahi">Rajshahi</option>
      <option value="Barishal">Barishal</option>
      <option value="Sylhet">Sylhet</option>
      <option value="Rangpur">Rangpur</option>
      <option value="Mymensingh">Mymensingh</option>
    </select>
    
    <label>Upazilla:</label>
    <input name="upazilla" type="text">
    
    <label>Village/Moholla:</label>
    <input name="village" type="text">
    
    <label>Post Office:</label>
    <input name="post_office" type="text">
    
    <label>House No:</label>
    <input name="house_no" type="text">
    
    <label>House Name:</label>
    <input name="house_name" type="text">
    
    <label>Road No:</label>
    <input name="road_no" type="text">

    <div class="section-title">4. Academic Information</div>
    <label>Faculty: <span class="required">*</span></label>
    <select name="faculty">
      <option value="">--Select Faculty--</option>
      <option value="Faculty of Science">Faculty of Science</option>
      <option value="Faculty of Engineering and Technology">Faculty of Engineering and Technology</option>
      <option value="Faculty of Business Administration">Faculty of Business Administration</option>
      <option value="Faculty of Social Science and Humanities">Faculty of Social Science and Humanities</option>
      <option value="Faculty of Law">Faculty of Law</option>
      <option value="Faculty of Education Science">Faculty of Education Science</option>
    </select>
    
    <label>Department: <span class="required">*</span></label>
    <select name="department">
      <option value="">--Select Department--</option>
      <option value="Information and Communication Engineering (ICE)">Information and Communication Engineering (ICE)</option>
      <option value="Computer Science and Telecommunication Engineering (CSTE)">Computer Science and Telecommunication Engineering (CSTE)</option>
      <option value="Electrical and Electronic Engineering (EEE)">Electrical and Electronic Engineering (EEE)</option>
    </select>
    
    <label>Level: <span class="required">*</span></label>
    <select name="level">
      <option value="">--Select Level--</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
    </select>
    
    <label>Session:</label>
    <input name="session" type="text" value="2025-2026" required>
    
    <label>Year, Term: <span class="required">*</span> </label>
    <select name="year_term">
      <option value="">--Select Year, Term--</option>
      <option value="Year-1, Term-1">Year-1, Term-1</option>
      <option value="Year-1, Term-2">Year-1, Term-2</option>
      <option value="Year-2, Term-1">Year-2, Term-1</option>
      <option value="Year-2, Term-2">Year-2, Term-2</option>
      <option value="Year-3, Term-1">Year-3, Term-1</option>
      <option value="Year-3, Term-2">Year-3, Term-2</option>
      <option value="Year-4, Term-1">Year-4, Term-1</option>
      <option value="Year-4, Term-2">Year-4, Term-2</option>
    </select>
    
    <label>Student ID:</label>
    <input type="text" name="student_code" placeholder="Enter Your Student ID" required>
    
    <label>Student Image:</label>
    <input name="student_image" type="file" accept="image/*">

    <div class="section-title">5. Login Information</div>
    <label>Email:<span class="required">*</span></label>
    <input name="email" type="email" required>
    
    <label>Password:<span class="required">*</span></label>
    <input name="password" type="password" minlength="6" required>
    
    <label>Confirm Password:<span class="required">*</span></label>
    <input id="confirm_password" type="password" minlength="6" required>

    <button type="submit" class="submit-btn">Submit Registration</button>
  </form>

  <script>
    // Password confirmation validation
    document.querySelector('.signup-form').addEventListener('submit', function(e) {
      const password = document.querySelector('input[name="password"]').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
      }
      
      if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long!');
        return false;
      }
      
      // Validate mobile numbers
      const mobile = document.querySelector('input[name="mobile"]').value;
      const gMobile = document.querySelector('input[name="g_mobile"]').value;
      
      if (mobile.length !== 11 || !mobile.startsWith('01')) {
        e.preventDefault();
        alert('Mobile number must be 11 digits starting with 01');
        return false;
      }
      
      if (gMobile.length !== 11 || !gMobile.startsWith('01')) {
        e.preventDefault();
        alert('Guardian mobile number must be 11 digits starting with 01');
        return false;
      }
    });
  </script>
</body>
</html>