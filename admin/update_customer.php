<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php';

// Lấy dữ liệu từ phần thân yêu cầu POST
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem các biến cần thiết có tồn tại không
if (isset($data->MaKhachHang) && isset($data->DiaChi) && isset($data->SoDienThoai) && isset($data->NgaySinh)) {
    // Cập nhật thông tin khách hàng trong cơ sở dữ liệu
    $sql = "UPDATE KhachHang SET DiaChi = ?, SoDienThoai = ?, NgaySinh = ? WHERE MaKhachHang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $data->DiaChi, $data->SoDienThoai, $data->NgaySinh, $data->MaKhachHang);

    if ($stmt->execute()) {
        echo "Thông tin khách hàng đã được cập nhật thành công.";
    } else {
        echo "Có lỗi xảy ra khi cập nhật thông tin khách hàng: " . $stmt->error;
    }

    // Đóng câu lệnh
    $stmt->close();
} else {
    echo "Dữ liệu không hợp lệ.";
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>
