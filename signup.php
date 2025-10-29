
<?php
if(isset($_POST['Name'])){
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $database   = "ice_info";

    // Connect to database using mysqli
    $con = mysqli_connect($servername, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get form data and escape special characters
    $Name          = mysqli_real_escape_string($con, $_POST['Name']);
    $f_name        = mysqli_real_escape_string($con, $_POST['f_name']);
    $m_name        = mysqli_real_escape_string($con, $_POST['m_name']);
    $dob           = mysqli_real_escape_string($con, $_POST['dob']);
    $mobile        = mysqli_real_escape_string($con, $_POST['mobile']);
    $g_name        = mysqli_real_escape_string($con, $_POST['g_name']);
    $g_mobile      = mysqli_real_escape_string($con, $_POST['g_mobile']);
    $hall          = mysqli_real_escape_string($con, $_POST['hall']);
    $blood_group   = mysqli_real_escape_string($con, $_POST['blood_group']);
    $religion      = mysqli_real_escape_string($con, $_POST['religion']);
    $nationality   = mysqli_real_escape_string($con, $_POST['nationality']);
    $nid_birth     = mysqli_real_escape_string($con, $_POST['nid_birth']);
    $gender        = isset($_POST['gender']) ? mysqli_real_escape_string($con, $_POST['gender']) : '';
    $marital_status= isset($_POST['marital_status']) ? mysqli_real_escape_string($con, $_POST['marital_status']) : '';
    $living_at     = isset($_POST['living_at']) ? mysqli_real_escape_string($con, $_POST['living_at']) : '';
    $division      = mysqli_real_escape_string($con, $_POST['division']);
    $district      = mysqli_real_escape_string($con, $_POST['district']);
    $upazilla      = mysqli_real_escape_string($con, $_POST['upazilla']);
    $villlage      = mysqli_real_escape_string($con, $_POST['villlage']);
    $post_offfice  = mysqli_real_escape_string($con, $_POST['post_offfice']);
    $house_no      = mysqli_real_escape_string($con, $_POST['house_no']);
    $house_name    = mysqli_real_escape_string($con, $_POST['house_name']);
    $raod_no       = mysqli_real_escape_string($con, $_POST['raod_no']);
    $department    = mysqli_real_escape_string($con, $_POST['department']);
    $faculty       = mysqli_real_escape_string($con, $_POST['faculty']);
    $level         = mysqli_real_escape_string($con, $_POST['level']);
    $session       = mysqli_real_escape_string($con, $_POST['session']);
    $year_term     = mysqli_real_escape_string($con, $_POST['year_term']);
    $student_code  = mysqli_real_escape_string($con, $_POST['student_code']);
    $student_image = mysqli_real_escape_string($con, $_POST['student_image']);
    $email         = mysqli_real_escape_string($con, $_POST['email']);
    $password      = mysqli_real_escape_string($con, $_POST['password']);
    
    // Hash password for security
    //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = "SELECT email FROM s_info WHERE email = '$email'";
    $result = mysqli_query($con, $check_email);
    
    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.history.back();</script>";
        exit;
    }

    // Corrected SQL query (removed database name prefix and fixed column name)
    $sql = "INSERT INTO s_info (Name, f_name, m_name, dob, mobile, 
    g_name, g_mobile, hall, blood_group, religion, nationality, nid_birth, 
    gender, marital_status, living_at, division, district, upazilla,
    villlage, post_offfice, house_no, house_name, raod_no, department, 
    faculty, level, session, year_term, student_code, student_image,
    email, password, c_date) 
    VALUES ('$Name','$f_name','$m_name','$dob','$mobile', '$g_name',
    '$g_mobile','$hall','$blood_group','$religion','$nationality','$nid_birth','$gender','$marital_status',
    '$living_at','$division','$district','$upazilla','$villlage','$post_offfice','$house_no',
    '$house_name','$raod_no','$department','$faculty','$level','$session','$year_term','$student_code',
    '$student_image','$email','$password', NOW())";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Registration successful! ✅'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "'); window.history.back();</script>";
    }

    mysqli_close($con);
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
  </style>
</head>
<body>
  <form class="signup-form" action="indexx.php" method="post" enctype="multipart/form-data">
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
    <input name="mobile" type="text" required>
    
    <label>Guardian's Name:<span class="required">*</span></label>
    <input name="g_name" type="text" required>
    
    <label>Guardian's Mobile:<span class="required">*</span></label>
    <input name="g_mobile" type="text" required>
    
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
      <label><input type="radio" name="gender" value="Male" required> Male</label>
      <label><input type="radio" name="gender" value="Female" required> Female</label>
      <label><input type="radio" name="gender" value="Others" required> Others</label>
    </div>
    
    <label>Marital Status:</label>
    <div class="radio-group">
      <label><input type="radio" name="marital_status" value="Married"> Married</label>
      <label><input type="radio" name="marital_status" value="Unmarried"> Unmarried</label>
    </div>

    <div class="section-title">2. Present Address</div>
    <label>Living At:</label>
    <div class="radio-group">
      <label><input type="radio" name="living_at" value="Hall"> Hall</label>
      <label><input type="radio" name="living_at" value="Others"> Others</label>
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
      <!-- Dhaka Division -->
      <option value="Dhaka">Dhaka</option>
      <option value="Gazipur">Gazipur</option>
      <option value="Kishoreganj">Kishoreganj</option>
      <option value="Manikganj">Manikganj</option>
      <option value="Munshiganj">Munshiganj</option>
      <option value="Narayanganj">Narayanganj</option>
      <option value="Narsingdi">Narsingdi</option>
      <option value="Tangail">Tangail</option>
      <option value="Faridpur">Faridpur</option>
      <option value="Gopalganj">Gopalganj</option>
      <option value="Madaripur">Madaripur</option>
      <option value="Rajbari">Rajbari</option>
      <option value="Shariatpur">Shariatpur</option>
      <!-- Chattogram Division -->
      <option value="Chattogram">Chattogram</option>
      <option value="Cox's Bazar">Cox's Bazar</option>
      <option value="Bandarban">Bandarban</option>
      <option value="Khagrachhari">Khagrachhari</option>
      <option value="Rangamati">Rangamati</option>
      <option value="Feni">Feni</option>
      <option value="Noakhali">Noakhali</option>
      <option value="Lakshmipur">Lakshmipur</option>
      <option value="Brahmanbaria">Brahmanbaria</option>
      <option value="Cumilla">Cumilla</option>
      <option value="Chandpur">Chandpur</option>
      <!-- Add other districts as needed -->
    </select>
    
    <label>Upazilla:</label>
    <input name="upazilla" type="text">
    
    <label>Village/Moholla:</label>
    <input name="villlage" type="text">
    
    <label>Post Office:</label>
    <input name="post_offfice" type="text">
    
    <label>House No:</label>
    <input name="house_no" type="text">
    
    <label>House Name:</label>
    <input name="house_name" type="text">
    
    <label>Road No:</label>
    <input name="raod_no" type="text">

    <div class="section-title">4. Academic Information</div>
    <label>Faculty:</label>
    <select name="faculty">
      <option value="">--Select Faculty--</option>
      <option value="Faculty of Science">Faculty of Science</option>
      <option value="Faculty of Engineering and Technology">Faculty of Engineering and Technology</option>
      <option value="Faculty of Business Administration">Faculty of Business Administration</option>
      <option value="Faculty of Social Science and Humanities">Faculty of Social Science and Humanities</option>
      <option value="Faculty of Law">Faculty of Law</option>
      <option value="Faculty of Education Science">Faculty of Education Science</option>
    </select>
    
    <label>Department:</label>
    <select name="department">
      <option value="">--Select Department--</option>
      <option value="Information and Communication Engineering (ICE)">Information and Communication Engineering (ICE)</option>
      <option value="Computer Science and Telecommunication Engineering (CSTE)">Computer Science and Telecommunication Engineering (CSTE)</option>
      <option value="Electrical and Electronic Engineering (EEE)">Electrical and Electronic Engineering (EEE)</option>
    </select>
    
    <label>Level:</label>
    <select name="level">
      <option value="">--Select Level--</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
    </select>
    
    <label>Session:</label>
    <input name="session" type="text" value="2025-2026">
    
    <label>Year, Term:</label>
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
    <input type="text" name="student_code" placeholder="Enter Your Student ID">
    
    <label>Student Image:</label>
    <input name="student_image" type="file" accept="image/*">

    <div class="section-title">5. Login Information</div>
    <label>Email:<span class="required">*</span></label>
    <input name="email" type="email" required>
    
    <label>Password:<span class="required">*</span></label>
    <input name="password" type="password" required>
    
    <label>Confirm Password:<span class="required">*</span></label>
    <input id="confirm_password" type="password" required>

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
    });
  </script>
</body>
</html>