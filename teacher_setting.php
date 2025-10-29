<?php
session_start();

// Check if the user is logged in as teacher
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'teacher') {
    die("Unauthorized access");
}

include 'db.php'; // Your database connection file

// Check DB connection
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $new_password = trim($_POST['new_password'] ?? '');

    // Validation
    if ($new_password === '') {
        die("Password cannot be empty.");
    }

    if (strlen($new_password) < 3) {
        die("Password must be at least 3 characters long.");
    }

   

    // Update teacher password (plain text)
    $stmt = $conn->prepare("UPDATE teacher SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $_SESSION['email']);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Password updated successfully!";
        } else {
            echo "No rows updated. Make sure your email matches the teacher table and the new password is different from the old one.";
        }
    } else {
        echo "Failed to update password: " . $stmt->error;
    }
} else {
    // Show form if not POST
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Teacher Settings</title>
        <style>
            /* Same styling as your original code */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f6f8;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .container {
                background-color: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                width: 350px;
                text-align: center;
            }

            h2 {
                color: #2c3e50;
                margin-bottom: 20px;
            }

            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0 20px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            button {
                background-color: #3498db;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }

            button:hover {
                background-color: #2980b9;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Update Password</h2>
            <form method="POST">
                <input type="password" name="new_password" placeholder="Enter new password" required>
                <button type="submit">Update</button>
            </form>
        </div>
    </body>

    </html>
<?php
}
?>