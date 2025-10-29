<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    die("Unauthorized access");
}

include 'db.php'; // your DB connection

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $new_password = trim($_POST['new_password'] ?? '');

    if($new_password === ''){
        die("Password cannot be empty.");
    }

    if(strlen($new_password) < 3){ // optional minimal length check
        die("Password must be at least 3 characters long.");
    }

    // Update the password for this CR (plain text)
    $stmt = $conn->prepare("UPDATE CR SET password=? WHERE email=?");
    $stmt->bind_param("ss", $new_password, $_SESSION['email']);

    if($stmt->execute()){
        echo "Password updated successfully!";
    } else {
        echo "Failed to update password: " . $stmt->error;
    }

} else {
    echo "Invalid request method.";
}
?>
