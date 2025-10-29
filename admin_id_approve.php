<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    die("Access denied. Please login as admin.");
}

// Handle status update
if (isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['status'];

    // Update the status in student_id_applications
    $update = $conn->prepare("UPDATE student_id_applications SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $application_id);

    if ($update->execute()) {
        // Fetch student_unique_id for this application
        $stmt = $conn->prepare("SELECT student_unique_id FROM student_id_applications WHERE id = ?");
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $student_unique_id = $row['student_unique_id'];

        // Insert notification for the student
        $message = "Your Student ID request (ID: $application_id) has been $new_status.";
        $notif = $conn->prepare("INSERT INTO notifications (student_unique_id, message, status, created_at) VALUES (?, ?, 'unread', NOW())");
        $notif->bind_param("ss", $student_unique_id, $message);
        $notif->execute();

        $msg = "<p style='color:green; text-align:center;'>Status updated and notification sent!</p>";
    } else {
        $msg = "<p style='color:red; text-align:center;'>Error updating status!</p>";
    }
}

// Fetch all applications
$sql = "SELECT * FROM student_id_applications ORDER BY applied_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Student ID Requests</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #2e5dd7;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2e5dd7;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        form {
            display: inline-block;
        }

        select,
        button {
            padding: 6px 8px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
        }

        select {
            background-color: #f0f0f0;
        }

        button {
            background-color: #2e5dd7;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #2049b5;
        }

        .status-Approved {
            color: green;
            font-weight: bold;
        }

        .status-Rejected {
            color: red;
            font-weight: bold;
        }

        .status-Pending {
            color: orange;
            font-weight: bold;
        }

        .msg {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>📋 Student ID Card Applications</h2>

    <div class="msg">
        <?php if (isset($msg)) echo $msg; ?>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Student Unique ID</th>
            <th>Name</th>
            <th>Blood Group</th>
            <th>Department</th>
            <th>Hall Name</th>
            <th>Status</th>
            <th>Applied At</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_unique_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['hall_name']); ?></td>
                    <td class="status-<?php echo $row['status']; ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['applied_at']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if ($row['status'] == "Pending") echo "selected"; ?>>Pending</option>
                                <option value="Approved" <?php if ($row['status'] == "Approved") echo "selected"; ?>>Approved</option>
                                <option value="Rejected" <?php if ($row['status'] == "Rejected") echo "selected"; ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">No applications found.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>