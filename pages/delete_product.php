<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['color']) && isset($_POST['memory'])) {
    $productId = $_POST['product_id'];
    $color = $_POST['color'];
    $memory = $_POST['memory'];

    // Thực hiện truy vấn để xóa sản phẩm từ cơ sở dữ liệu dựa trên mã sản phẩm và màu sắc, kích thước
    $sql = "DELETE FROM giohang WHERE MaSanPham = ? AND MauSac = ? AND KichThuoc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $productId, $color, $memory);

    if ($stmt->execute()) {
        // Xóa thành công
        echo 'Xóa sản phẩm thành công';
    } else {
        // Xử lý lỗi
        echo 'Lỗi khi xóa sản phẩm';
    }
} else {
    // Yêu cầu không hợp lệ
    echo 'Yêu cầu không hợp lệ';
}

$stmt->close();
?>
