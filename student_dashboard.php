<?php
session_start();
require_once "db.php";

// Check if student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    die("Please login first! <a href='student_login.php'>Login</a>");
}

$student_id = $_SESSION['student_unique_id'];

// Fetch notifications
$notif_result = $conn->query("SELECT * FROM notifications WHERE student_unique_id='$student_id' ORDER BY created_at DESC");

// Optional: mark all notifications as read
$conn->query("UPDATE notifications SET status='read' WHERE student_unique_id='$student_id'");
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<style>
body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
    }

    /* HEADER */
    header {
    position: sticky;   
    top: 0;            
    z-index: 1000;      
    background-color: white;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #e0e0e0;
    flex-wrap: wrap;
}

    .logo-section {
        display: flex;
        align-items: center;
    }
    .logo-section img {
        height: 50px;
        margin-right: 12px;
    }
    .university-name {
        font-weight: 600;
        font-size: 20px;
        color: #2e5dd7;
        letter-spacing: 1px;
    }
    .contact-info {
        font-size: 14px;
        text-align: right;
        color: #333;
    }

    /* LAYOUT */
    .container {
        display: flex;
        min-height: calc(100vh - 70px);
    }

    /* SIDEBAR */
    .sidebar {
        background-color: #1f2937;
        padding: 20px;
        width: 260px;
        color: white;
        flex-shrink: 0;
    }
    .profile {
        text-align: center;
        margin-bottom: 20px;
    }
    .profile img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
    }
    .profile h3 {
        margin: 10px 0 5px;
        font-size: 18px;
    }
    .profile p {
        color: lightgray;
        font-size: 14px;
        margin: 0;
    }
    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar ul li {
        padding: 12px;
        cursor: pointer;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sidebar ul li:hover {
        background-color: rgba(255,255,255,0.1);
    }

    /* MAIN */
    .main {
        flex: 1;
        padding: 20px;
    }
    .main h2 {
        text-align: center;
        margin-bottom: 20px;
        background: white;
        padding: 12px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        color: #2e5dd7;
        font-weight: 600;
    }

    /* CARD GRID */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
    .card {
        background-color: white;
        border-radius: 12px;
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .card-inner {
        width: 100%;
        height: 100%;
        padding: 20px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        color: white;
        text-align: center;
    }
    .card-inner i {
        font-size: 36px;
        margin-bottom: 10px;
        color: white;
    }

    /* GRADIENT COLORS */
    .blue { background: linear-gradient(135deg, #2e5dd7, #4e8df7); }
    .teal { background: linear-gradient(135deg, #00897b, #00c4b4); }
    .green { background: linear-gradient(135deg, #43a047, #66bb6a); }
    .orange { background: linear-gradient(135deg, #fb8c00, #ffb74d); }
    .red { background: linear-gradient(135deg, #e53935, #ef5350); }
    .purple { background: linear-gradient(135deg, #7b1fa2, #ab47bc); }
    .pink { background: linear-gradient(135deg, #d81b60, #ec407a); }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        .sidebar {
            width: 100%;
            border-bottom: 2px solid #e0e0e0;
        }
    }
</style>
<!-- ICONS -->
<script src="https://kit.fontawesome.com/yourfontawesomekey.js" crossorigin="anonymous"></script>
</head>
<body>

<header>
    <div class="logo-section">
        <img src="main-logo.png" alt="NSTU Logo">
    </div>
    <div class="contact-info">
        📞 02534496922 | 📞 69027751052 <br>
        ✉ registrar@office.nstu.edu.bd
    </div>
</header>





<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="profile">
         
            
           <?php
// Fetch student name and profile picture from database using student_unique_id
$student_query = $conn->prepare("SELECT name, student_image FROM s_info WHERE student_code = ?");
$student_query->bind_param("s", $student_id);
$student_query->execute();
$student_result = $student_query->get_result();
$student_data = $student_result->fetch_assoc();

$student_name = $student_data ? $student_data['name'] : "Student";
$profile_pic = $student_data && !empty($student_data['student_image']) ? $student_data['student_image'] : "https://via.placeholder.com/80";
?>
<img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile">
<h3><?php echo htmlspecialchars($student_name); ?></h3>
<p>Student</p>



        </div>
        <ul>
    
    <li onclick="window.location.href='student_dashboard.php'">My Dashboard</li>
    <li onclick="window.location.href='notice.php'">Notice</li>
    <li onclick="window.location.href='student_notification.php'">Notifications</li>
    <li onclick="window.location.href='student_routine.php'">Class Routine</li>
    <li onclick="window.location.href='student_schedule.php'">Class Schedule</li>
    <li onclick="window.location.href='s_official_notice.php'">Official Notice</li>
    
    <li onclick="logout()">Logout</li>
</ul>

    </div>

    <!-- MAIN -->
    <div class="main">
        <h2>Student Dashboard</h2>

        

        <div class="card-grid">
             
            <div class="card" onclick="window.location.href='notice.php'">
   <div class="card-inner blue"><i class="fas fa-bullhorn"></i> 📢Notice</div>
            </div>
<div class="card" onclick="window.location.href='student_notification.php'">
    <div class="card-inner blue">
        <i class="fas fa-bell"></i> 🔔 Notifications
    </div>
</div>
             <div class="card" onclick="window.location.href='s_official_notice.php'"><div class="card-inner purple"><i class="fas fa-book"></i>📘Official Notice</div></div>
             <div class="card" onclick="window.location.href='student_courses.php'"><div class="card-inner green"><i class="fas fa-book-open"></i>📚Courses</div></div>
             <div class="card" onclick="window.location.href='s_study_material.php'"><div class="card-inner teal"><i class="fas fa-book-open"></i>📚Study Materials</div></div>
             <div class="card" onclick="window.location.href='student_routine.php'"><div class="card-inner orange"><i class="fas fa-calendar-alt"></i>🗓️Class Routine</div></div>
              <div class="card"  onclick="window.location.href='student_schedule.php'"><div class="card-inner purple"><i class="fas fa-calendar-alt"></i>🗓️Class Schedule</div></div>
             <div class="card" onclick="window.location.href='student_exm.php'"><div class="card-inner red"><i class="fas fa-calendar-day"></i>📆 Term Final Routine</div></div>
             <div class="card" onclick="window.location.href='student_id.php'"><div class="card-inner teal"><i class="fas fa-id-card"></i>🪪Student ID</div></div>
             <div class="card" onclick="window.location.href='student_assignment.php'"><div class="card-inner pink"><i class="fas fa-envelope"></i>Assignment</div></div>
             <div class="card" onclick="window.location.href='hall_admission.php'"><div class="card-inner green"><i class="fas fa-school"></i>🏫 Hall Admission</div></div>
             <div class="card" onclick="window.location.href='s_certificate.php'"><div class="card-inner orange"><i class="fas fa-file-alt"></i>📄 Certificate & Transcript</div></div>
             <div class="card" onclick="window.location.href='student_result.php'"><div class="card-inner red"><i class="fas fa-chart-bar"></i>📊Result</div></div>
             <div class="card" onclick="window.location.href='student_chat.php'"><div class="card-inner red"><i class="fas fa-comments"></i>Chat</div></div>
        </div>
    </div>
</div>

<script>
function logout() {
    window.location.href = "myproject.html"; 
}
</script>

</body>
</html>
