<?php
session_start();

include'../connect.php';
// Get user input
$user = $_POST['username'];
$pass = $_POST['password'];

// Sanitize user input
$user = $conn->real_escape_string($user);
$pass = $conn->real_escape_string($pass);

// Query to check login
$sql = "SELECT * FROM Admin_inf WHERE TenDangNhap = '$user' AND MatKhau = '$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login successful, set session variable
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user;
    header("Location: index.php");
    exit();
} else {
    // Login failed, redirect back with error
    header("Location: login.php?error=1");
    exit();
}

$conn->close();
?>
