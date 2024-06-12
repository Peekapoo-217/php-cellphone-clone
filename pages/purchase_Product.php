<?php

if(isset($_SESSION['username']))
$username = $_SESSION['username'];
 

require_once 'connect.php';

if (isset($_GET['product_id']) && isset($_GET['image']) && isset($_GET['product_name']) && isset($_GET['color']) && isset($_GET['memory']) && isset($_GET['price'])) {
    $product_id = $_GET['product_id'];
    $image = $_GET['image'];
    $product_name = $_GET['product_name'];
    $color = $_GET['color'];
    $size = $_GET['memory'];
    $price = (float)$_GET['price'];
    $sl = 1;

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng hay chưa
    $sql_check = "SELECT SoLuong FROM GioHang WHERE TenDangNhap = ? AND MaSanPham = ? AND MauSac = ? AND KichThuoc = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ssss", $username, $product_id, $color, $size);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Sản phẩm đã tồn tại, tăng số lượng
        $row_check = $result_check->fetch_assoc();
        $new_quantity = $row_check['SoLuong'] + $sl;

        $sql_update = "UPDATE GioHang SET SoLuong = ? WHERE TenDangNhap = ? AND MaSanPham = ? AND MauSac = ? AND KichThuoc = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("issss", $new_quantity, $username, $product_id, $color, $size);

        if ($stmt_update->execute()) {
         
        } else {
            echo "Lỗi: " . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        // Sản phẩm chưa tồn tại, thêm sản phẩm mới
        $sql_insert = "INSERT INTO GioHang (TenDangNhap, MaSanPham, DiaChiAnh, TenSanPham, MauSac, KichThuoc, GiaMoi, SoLuong) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        
        if ($stmt_insert = $conn->prepare($sql_insert)) {
            $stmt_insert->bind_param("ssssssdi", $username, $product_id, $image, $product_name, $color, $size, $price, $sl);

            if ($stmt_insert->execute()) {
               
               
               
            } else {
                echo "Lỗi: " . $stmt_insert->error;
            }

            $stmt_insert->close();
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }

    $stmt_check->close();
} else {
    echo "";
}

?>
