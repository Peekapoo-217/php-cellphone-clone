<?php
require_once '../connect.php';

$action = $_POST['action'] ?? '';
switch($action){
    case 'getStatusOfVariation':
        $productId = $_POST['productId'];
        $color = $_POST['color'];
        $memory = $_POST['memory'];
        echo getStatusOfVariation($productId, $color, $memory);
        break;
}

function getStatusOfVariation($productId, $color, $memory){
    global $conn;

    $sql = 'SELECT SoLuong 
            FROM GiaSanPham gs 
            JOIN Colors cl ON gs.MaMau = cl.MaMau
            JOIN RAM_ROM_Option rro ON gs.MaRam = rro.MaRam
            WHERE gs.MaSanPham = ? AND cl.TenMau = ? AND rro.KichThuoc = ?';
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $productId, $color, $memory);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $quantity = $row['SoLuong'];
        return ($quantity > 0) ? 'Còn hàng ✔' : 'Hết hàng ✘';
    } else {
        return 'Hết hàng ✘';
    }
}


?>
