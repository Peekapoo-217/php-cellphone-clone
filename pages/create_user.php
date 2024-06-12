<?php
    // Thực hiện kết nối đến cơ sở dữ liệu
    include '../connect.php';

    // Lấy dữ liệu từ form
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Mã hóa mật khẩu
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra và xử lý nếu ngày sinh không được cung cấp
    if (empty($birthday)) {
        $birthday = NULL;
    }

    // Chuẩn bị câu lệnh SQL
    $sql = "INSERT INTO KhachHang (TenDangNhap, MatKhau, HoTen, SoDienThoai, DiaChi, Email, NgaySinh)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Sử dụng prepared statement để tránh SQL injection
    $stmt = $conn->prepare($sql);
    // Sử dụng kiểu "s" cho chuỗi và kiểu "s" cho NULL
    $stmt->bind_param("sssssss", $username, $password, $fullname, $phone, $address, $email, $birthday);

    // Thực thi câu lệnh và kiểm tra lỗi
    if ($stmt->execute()) {
        // Kiểm tra xem có bản ghi nào được thêm vào không
        if ($stmt->affected_rows > 0) {
            // Đóng kết nối
            $stmt->close();
            $conn->close();

            // Chuyển hướng sang trang login.php
            header("Location: login.php?registration=success");
            exit();
        } else {
            echo "Error: Invalid data or no records inserted";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
?>
