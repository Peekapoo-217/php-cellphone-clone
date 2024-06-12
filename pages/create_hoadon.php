<?php
session_start();
// Kết nối đến cơ sở dữ liệu
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_SESSION['username'], ' ');
    $currentDate = date('Y-m-d');
    $status = "Chưa xác nhận";
    $totalPrice = $_POST['totalPrice'];

    $insertOrderQuery = "INSERT INTO HoaDon (TenDangNhap, NgayLap, TongTien, TrangThai) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bind_param('ssds', $username, $currentDate, $totalPrice, $status);

    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;
        $productsData = json_decode($_POST['productsData'], true);
        $success = true;
        $errorMessage = '';

        foreach ($productsData as $product) {
            $productId = $product['productId'];
            $quantity = $product['quantity'];
            $productQuery = "SELECT MaSanPham, TenSanPham, MauSac, KichThuoc, GiaMoi FROM giohang WHERE MaSanPham = ? AND TenDangNhap = ?";
            $stmt = $conn->prepare($productQuery);
            $stmt->bind_param('is', $productId, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $productName = $row['TenSanPham'];
                $color = $row['MauSac'];
                $size = $row['KichThuoc'];
                $price = $row['GiaMoi'];
                $totalPrice = $price * $quantity;

                $insertDetailQuery = "INSERT INTO ChiTietHoaDon (MaHoaDon, MaSanPham, TenMau, KichThuoc, SoLuong, ThanhTien) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertDetailQuery);
                $stmt->bind_param('iissid', $orderId, $productId, $color, $size, $quantity, $totalPrice);

                if (!$stmt->execute()) {
                    $success = false;
                    $errorMessage = 'Failed to insert order details for product ID: ' . $productId;
                    break;
                }
            } else {
                $success = false;
                $errorMessage = 'Failed to fetch product data for product ID: ' . $productId;
                break;
            }
        }

        if ($success) {
            // Delete specific products from giohang table
            foreach ($productsData as $product) {
                $productId = $product['productId'];
                $productQuery = "SELECT MauSac, KichThuoc FROM giohang WHERE MaSanPham = ? AND TenDangNhap = ?";
                $stmt = $conn->prepare($productQuery);
                $stmt->bind_param('is', $productId, $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $color = $row['MauSac'];
                    $size = $row['KichThuoc'];

                    $deleteSql = "DELETE FROM giohang WHERE MaSanPham = ? AND MauSac = ? AND KichThuoc = ? AND TenDangNhap = ?";
                    $stmt = $conn->prepare($deleteSql);
                    $stmt->bind_param('isss', $productId, $color, $size, $username);
                    $stmt->execute();
                }
            }

            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => $errorMessage));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to create order"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
