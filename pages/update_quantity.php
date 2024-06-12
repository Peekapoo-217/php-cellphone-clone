<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_POST['total_price'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $totalPrice = $_POST['total_price'];
    
    // Thực hiện truy vấn để cập nhật số lượng và tổng tiền của sản phẩm trong cơ sở dữ liệu
    $sql = "UPDATE giohang SET SoLuong = ?, TongTien = ? WHERE MaSanPham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('idi', $quantity, $totalPrice, $productId);
    
    if ($stmt->execute()) {
        // Cập nhật thành công
        echo 'Cập nhật số lượng và tổng tiền sản phẩm thành công';
    } else {
        // Xử lý lỗi
        echo 'Lỗi khi cập nhật số lượng và tổng tiền sản phẩm';
    }
} else {
    // Yêu cầu không hợp lệ
    echo 'Yêu cầu không hợp lệ';
}
?>
