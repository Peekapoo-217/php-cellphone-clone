<?php
include '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NgaySinh = $_POST['NgaySinh'];
    $SoDienThoai = $_POST['SoDienThoai'];
    $DiaChi = $_POST['DiaChi'];
    $TenDangNhap = $_POST['TenDangNhap'];

    // Truy vấn cơ sở dữ liệu để lấy giá trị hiện tại của người dùng
    $sql = "SELECT NgaySinh, SoDienThoai, DiaChi FROM KHACHHANG WHERE TenDangNhap = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $TenDangNhap);
        $stmt->execute();
        $stmt->bind_result($currentNgaySinh, $currentSoDienThoai, $currentDiaChi);
        $stmt->fetch();
        $stmt->close();
    }

    // Kiểm tra nếu có thay đổi
    if ($NgaySinh != $currentNgaySinh) {
        $sql = "UPDATE KHACHHANG SET NgaySinh = ? WHERE TenDangNhap = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $NgaySinh, $TenDangNhap);
            if ($stmt->execute()) {
                echo "Cập nhật Ngày Sinh thành công!";
            } else {
                echo "Lỗi khi cập nhật Ngày Sinh: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi khi chuẩn bị câu lệnh cập nhật Ngày Sinh: " . $conn->error;
        }
    }
    
    if ($SoDienThoai != $currentSoDienThoai) {
        $sql = "UPDATE KHACHHANG SET SoDienThoai = ? WHERE TenDangNhap = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $SoDienThoai, $TenDangNhap);
            if ($stmt->execute()) {
                echo "Cập nhật Số Điện Thoại thành công!";
            } else {
                echo "Lỗi khi cập nhật Số Điện Thoại: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi khi chuẩn bị câu lệnh cập nhật Số Điện Thoại: " . $conn->error;
        }
    }
    
    if ($DiaChi != $currentDiaChi) {
        $sql = "UPDATE KHACHHANG SET DiaChi = ? WHERE TenDangNhap = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $DiaChi, $TenDangNhap);
            if ($stmt->execute()) {
                echo "Cập nhật Địa Chỉ thành công!";
            } else {
                echo "Lỗi khi cập nhật Địa Chỉ: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Lỗi khi chuẩn bị câu lệnh cập nhật Địa Chỉ: " . $conn->error;
        }
    } 
    $conn->close();
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
