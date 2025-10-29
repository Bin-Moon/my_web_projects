<?php
session_start();
include 'db.php'; // your DB connection file

// Ensure only CRs access this page
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    header("Location: login.php");
    exit;
}

// Handle search if submitted
$search = '';
if(isset($_GET['search'])){
    $search = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM s_info WHERE Name LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $query = "SELECT * FROM s_info";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top:20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        input[type=text] { padding: 6px; width: 250px; }
        button { padding: 6px 12px; margin-left: 4px; }
    </style>
</head>
<body>
<h2>Manage Students</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>
    <?php if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){ ?>
            <tr>
                <td><?= $row['student_code'] ?></td>
                <td><?= htmlspecialchars($row['Name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
            </tr>
    <?php } } else { ?>
        <tr><td colspan="3">No students found.</td></tr>
    <?php } ?>
</table>

</body>
</html>

