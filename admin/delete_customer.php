<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php';

// Lấy dữ liệu từ phần thân yêu cầu POST
$data = json_decode(file_get_contents("php://input"));

// Xóa khách hàng khỏi cơ sở dữ liệu
$sql = "DELETE FROM KhachHang WHERE MaKhachHang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $data->MaKhachHang);
if ($stmt->execute()) {
    echo "Khách hàng đã được xóa thành công.";
} else {
    echo "Có lỗi xảy ra khi xóa khách hàng: " . $conn->error;
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>
