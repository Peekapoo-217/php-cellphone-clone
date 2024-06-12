<?php
// Kết nối đến cơ sở dữ liệu
include '../connect.php';

// Lấy dữ liệu từ phần thân yêu cầu POST
$data = json_decode(file_get_contents("php://input"));

// Xóa khách hàng khỏi cơ sở dữ liệu
$sql = "DELETE FROM HoaDon WHERE MaHoaDon = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $data->MaHoaDon);
if ($stmt->execute()) {
    echo "Hóa đơn đã được xóa thành công.";
} else {
    echo "Có lỗi xảy ra khi xóa hóa đơn: " . $conn->error;
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>
