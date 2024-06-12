<?php
require_once '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maHoaDon = $_POST["MaHoaDon"];

    // Bắt đầu giao dịch
    $conn->begin_transaction();

    try {
        // Xóa chi tiết hóa đơn
        $sqlDeleteChiTiet = "DELETE FROM ChiTietHoaDon WHERE MaHoaDon = ?";
        $stmtDeleteChiTiet = $conn->prepare($sqlDeleteChiTiet);
        $stmtDeleteChiTiet->bind_param("i", $maHoaDon);
        $stmtDeleteChiTiet->execute();

        // Xóa hóa đơn
        $sqlDeleteHoaDon = "DELETE FROM HoaDon WHERE MaHoaDon = ?";
        $stmtDeleteHoaDon = $conn->prepare($sqlDeleteHoaDon);
        $stmtDeleteHoaDon->bind_param("i", $maHoaDon);
        $stmtDeleteHoaDon->execute();

        // Commit giao dịch
        $conn->commit();

        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        // Rollback giao dịch nếu có lỗi xảy ra
        $conn->rollback();

        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    $stmtDeleteChiTiet->close();
    $stmtDeleteHoaDon->close();
    $conn->close();
}
?>
