<?php
session_start();
require_once "db.php";

// Admin session check
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    die("Unauthorized access! <a href='admin_login.php'>Login</a>");
}

// Handle approve/reject form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $scheduled_date = !empty($_POST['scheduled_date']) ? $_POST['scheduled_date'] : null;

    // Get student_unique_id for this request
    $stmt = $conn->prepare("SELECT student_unique_id FROM certificate_requests WHERE request_id=?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $student_unique_id = $row['student_unique_id'] ?? null;

    if ($student_unique_id) {
        // Update certificate request status
        $stmt = $conn->prepare("UPDATE certificate_requests SET status=?, scheduled_date=? WHERE request_id=?");
        $stmt->bind_param("ssi", $status, $scheduled_date, $request_id);
        $stmt->execute();

        // Insert notification for the student
        $message = "Your request (ID: $request_id) has been $status.";
        if ($status === 'Approved' && $scheduled_date) {
            $message .= " Scheduled date: $scheduled_date.";
        }

        $stmt_notif = $conn->prepare("INSERT INTO notifications (student_unique_id, message, status) VALUES (?, ?, 'unread')");
        $stmt_notif->bind_param("ss", $student_unique_id, $message);
        $stmt_notif->execute();
    }
}

// Fetch all certificate/transcript requests
$result = $conn->query("SELECT cr.*, si.email 
                        FROM certificate_requests cr
                        JOIN s_info si ON cr.student_unique_id = si.student_code
                        ORDER BY cr.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin: Certificate/Transcript Requests</title>
<style>
body { font-family: Arial; background: #f5f7fa; padding: 20px; }
h2 { color: #2e5dd7; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
table th, table td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
table th { background: #2e5dd7; color: white; }
form { display: inline; margin: 0; }
input[type="datetime-local"] { padding: 5px; border-radius: 5px; border: 1px solid #ccc; }
button { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; margin-left: 5px; }
button.approve { background: green; color: white; }
button.reject { background: red; color: white; }
.status-Pending { color: orange; font-weight: 600; }
.status-Approved { color: green; font-weight: 600; }
.status-Rejected { color: red; font-weight: 600; }
</style>
</head>
<body>

<h2>Certificate / Transcript Requests</h2>

<table>
<tr>
    <th>Student Email</th>
    <th>Student Code</th>
    <th>Program</th>
    <th>Type</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Scheduled Date</th>
    <th>Action</th>
</tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['student_unique_id'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['program'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['type'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['reason'] ?? ''); ?></td>
    <td class="status-<?php echo htmlspecialchars($row['status'] ?? ''); ?>">
        <?php echo htmlspecialchars($row['status'] ?? ''); ?>
    </td>
    <td><?php echo htmlspecialchars($row['scheduled_date'] ?? '-'); ?></td>
    <td>
        <!-- Approve Form -->
        <form method="POST" style="display:inline-block">
            <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
            <input type="datetime-local" name="scheduled_date" value="<?php echo $row['scheduled_date'] ?? ''; ?>" required>
            <button type="submit" name="status" value="Approved" class="approve">Approve</button>
        </form>

        <!-- Reject Form -->
        <form method="POST" style="display:inline-block">
            <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
            <button type="submit" name="status" value="Rejected" class="reject">Reject</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
