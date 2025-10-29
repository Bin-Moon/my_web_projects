<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'student') {
    die("Please login first! <a href='student_login.php'>Login</a>");
}

require_once "db.php";

// Fetch notices uploaded by admin (from teac_notices table)
$result = $conn->query("SELECT * FROM teac_notices ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<title>Official Notices</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f5f7fa;
  padding: 20px;
}
h2 {
  text-align: center;
  color: #2e5dd7;
}
.notice-table {
  width: 90%;
  margin: 20px auto;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.notice-table th, .notice-table td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
}
.notice-table th {
  background: #2e5dd7;
  color: white;
}
.notice-table tr:hover {
  background: #f1f5ff;
}
a.download-btn {
  text-decoration: none;
  background: #2e5dd7;
  color: white;
  padding: 6px 12px;
  border-radius: 5px;
}
a.download-btn:hover {
  background: #1b45a3;
}
</style>
</head>
<body>

<h2>📘 Official Notices</h2>
<table class="notice-table">
  <tr>
    <th>Title</th>
    <th>Date</th>
    <th>Action</th>
  </tr>
  <?php while($row = $result->fetch_assoc()) { ?>
  <tr>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo date("d M Y, h:i A", strtotime($row['upload_date'] ?? 'now')); ?></td>
    <td>
      <a href="uploads/notices/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank" class="download-btn">View</a>
    </td>
  </tr>
  <?php } ?>
</table>

</body>
</html>
