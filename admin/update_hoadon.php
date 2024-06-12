<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php';

// Lấy dữ liệu từ phần thân yêu cầu POST
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem các biến cần thiết có tồn tại không
if (isset($data->MaHoaDon) && isset($data->TrangThai)) {
    // Cập nhật thông tin khách hàng trong cơ sở dữ liệu
    $sql = "UPDATE HoaDon SET TrangThai = ? WHERE MaHoaDon = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $data->TrangThai, $data->MaHoaDon);

    if ($stmt->execute()) {
        echo "Thông tin hóa đơn đã được cập nhật thành công.";
    } else {
        echo "Có lỗi xảy ra khi cập nhật thông tin hóa đơn: " . $stmt->error;
    }

    // Đóng câu lệnh
    $stmt->close();
} else {
    echo "Dữ liệu không hợp lệ.";
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>
