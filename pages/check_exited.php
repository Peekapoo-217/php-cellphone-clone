<?php
include '../connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$value = $data['value'];

// Xác định loại kiểm tra
if ($type === 'username') {
    $sql = "SELECT COUNT(*) AS count FROM KhachHang WHERE TenDangNhap = ?";
} elseif ($type === 'phone') {
    $sql = "SELECT COUNT(*) AS count FROM KhachHang WHERE SoDienThoai = ?";
} elseif ($type === 'email') {
    $sql = "SELECT COUNT(*) AS count FROM KhachHang WHERE email = ?";
} else {
    echo json_encode(['exists' => false]);
    exit();
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $value);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['exists' => $row['count'] > 0]);

$stmt->close();
$conn->close();
?>
