<?php
// Start session
session_start();
include'../connect.php';
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    
    <?php
    $sql = "SELECT HoTen FROM admin_inf WHERE TenDangNhap like '". $_SESSION['username']."'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $tenad = $row['HoTen'];
    
    echo '<h1>Welcome, '. $tenad. '!</h1>';
    }
    ?>
    <a href="logout.php">Logout</a>

</header>

<div class="nav">
    <a href="index.php?qlkh">Quản lý Khách Hàng</a>
    <a href="index.php?qlhd">Quản lý hóa đơn</a>
</div>

<?php
if(isset($_GET['qlhd']))
include 'hoadon.php';
else
    include 'khachhang.php';
?>

</body>
</html>
