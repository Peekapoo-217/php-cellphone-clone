<?php
session_start();






include '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra xem các trường có trống không
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty_fields&username=" . urlencode($username));
        exit();
    }

    // Kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
    $sql = "SELECT * FROM KhachHang WHERE TenDangNhap = '$username' AND MatKhau = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Đăng nhập thành công
        $_SESSION['username'] = $username;
        header("Location: ../index.php"); // Chuyển hướng đến trang chào mừng sau khi đăng nhập thành công
        exit();
    } else {
        // Đăng nhập thất bại, chuyển hướng lại login.php với thông tin đã nhập và thông báo lỗi
        $queryString = http_build_query([
            'error' => 'invalid_credentials',
            'username' => $username
        ]);
        header("Location: login.php?$queryString");
        exit();
    }
}

$conn->close();
?>
