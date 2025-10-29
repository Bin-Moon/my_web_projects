<?php
session_start();
require_once "db.php"; // Your DB connection

// Student session check
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'student') {
    die("Please login first! <a href='student_login.php'>Login</a>");
}

$student_email = $_SESSION['email'];
$message = "";

// Fetch student_code from s_info table
$stmt = $conn->prepare("SELECT student_code FROM s_info WHERE email=?");
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$student_code = $row['student_code'] ?? null;

if (!$student_code) {
    die("Student record not found!");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $reason = $_POST['reason'];
    $program = $_POST['program']; // NEW FIELD

    $stmt = $conn->prepare("INSERT INTO certificate_requests (student_unique_id, program, type, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $student_code, $program, $type, $reason);

    if ($stmt->execute()) {
        $message = "✅ Your request has been submitted successfully!";
    } else {
        $message = "❌ Error submitting request!";
    }
}

// Fetch all requests of this student
$result_requests = $conn->query("SELECT * FROM certificate_requests WHERE student_unique_id='$student_code' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate & Transcript Requests</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f7fa;
            padding: 20px;
        }

        h2 {
            color: #2e5dd7;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }

        form input[type="text"],
        form select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form button {
            margin-top: 15px;
            padding: 10px 20px;
            background: #2e5dd7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background: #1b3fa7;
        }

        .radio-group {
            display: flex;
            align-items: center;
            gap: 20px;
            /* space between Honors and Masters */
            margin-top: 5px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        p.message {
            font-weight: 600;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #2e5dd7;
            color: white;
        }

        .status-Pending {
            color: orange;
            font-weight: 600;
        }

        .status-Approved {
            color: green;
            font-weight: 600;
        }

        .status-Rejected {
            color: red;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <h2>Request Certificate / Transcript</h2>

    <form method="POST">
        <label>Select Program:</label>
        <div class="radio-group">
            <label><input type="radio" name="program" value="Honors" required> Honors</label>
            <label><input type="radio" name="program" value="Masters" required> Masters</label>
        </div>

        <label>Select Type:</label>
        <select name="type" required>
            <option value="Certificate">Certificate</option>
            <option value="Transcript">Transcript</option>
        </select>

        <label>Reason:</label>
        <input type="text" name="reason" required placeholder="Enter your reason">

        <button type="submit">Submit Request</button>
    </form>

    <p class="message"><?php echo $message; ?></p>

    <h2>My Requests</h2>
    <table>
        <tr>
            <th>Program</th>
            <th>Type</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Scheduled Date</th>
            <th>Requested At</th>
        </tr>
        <?php while ($row = $result_requests->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['program']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['reason']; ?></td>
                <td class="status-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></td>
                <td><?php echo $row['scheduled_date'] ?? '-'; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>