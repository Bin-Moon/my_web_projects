<?php
session_start();
include 'db.php'; // your database connection file

// check login + role
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'cr'){
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $due_date    = mysqli_real_escape_string($conn, $_POST['due_date']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $year        = mysqli_real_escape_string($conn, $_POST['year']);
    $semester    = mysqli_real_escape_string($conn, $_POST['semester']);

    $sql = "INSERT INTO tasks (title, description, due_date, year, semester) 
            VALUES ('$title', '$description', '$due_date', '$year', '$semester')";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('✅ Task created successfully!'); window.location.href='cr_dashboard.php';</script>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>
