<?php
session_start();
include 'db.php';  // database connection

// Check if CR is logged in
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    exit("Unauthorized");
}

// Get query params
$year     = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$course   = $_GET['course'] ?? '';

// Fetch study materials
$stmt = $conn->prepare("SELECT id, title, file_path FROM study_materials 
                        WHERE year=? AND semester=? AND course=? 
                        ORDER BY uploaded_at DESC");
$stmt->bind_param("sss", $year, $semester, $course);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>No materials uploaded yet.</p>";
    exit;
}

echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
echo "<tr><th>Title</th><th>File</th><th>Actions</th></tr>";

while ($row = $result->fetch_assoc()) {
    $fileName = basename($row['file_path']);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
    echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>$fileName</a></td>";
    echo "<td>
            <button onclick=\"deleteMaterial('$fileName')\">Delete</button>
          </td>";
    echo "</tr>";
}
echo "</table>";
?>
