<?php

require_once '../connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maSanPham = $_POST["MaSanPham"];
    $tenDangNhap = $_POST["TenDangNhap"];
    $soSao = $_POST["SoSao"];
    $binhLuan = $_POST["BinhLuan"];
    $maKhachHang = $_POST["MaKhachHang"];
    $hoTen = $_POST["HoTen"];
    $maHoaDon = $_POST["MaHoaDon"];

    // Kiểm tra xem sản phẩm đã được đánh giá chưa
    $sqlCheckReview = "SELECT DISTINCT * FROM Feedback WHERE MaSanPham = ? AND MaKhachHang = ? AND MaHoaDon = ?";
    $stmtCheckReview = $conn->prepare($sqlCheckReview);
    $stmtCheckReview->bind_param("iii", $maSanPham, $maKhachHang, $maHoaDon);
    $stmtCheckReview->execute();
    $resultCheckReview = $stmtCheckReview->get_result();

    if ($resultCheckReview->num_rows == 0) {
        $sqlGetMaKhachHang = "SELECT MaKhachHang FROM KhachHang WHERE TenDangNhap = ?";
        $stmtGetMaKhachHang = $conn->prepare($sqlGetMaKhachHang);
        $stmtGetMaKhachHang->bind_param("s", $tenDangNhap);
        $stmtGetMaKhachHang->execute();
        $resultGetMaKhachHang = $stmtGetMaKhachHang->get_result();
        $maKhachHang = $resultGetMaKhachHang->fetch_assoc()["MaKhachHang"];
        $stmtGetMaKhachHang->close();

        if ($maKhachHang) {
            $sqlInsertFeedback = "INSERT INTO Feedback (MaKhachHang, MaHoaDon, HoTen, MaSanPham, SoSao, BinhLuan) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsertFeedback = $conn->prepare($sqlInsertFeedback);
            $stmtInsertFeedback->bind_param("iisiss", $maKhachHang, $maHoaDon, $hoTen, $maSanPham, $soSao, $binhLuan);
            $stmtInsertFeedback->execute();
            $stmtInsertFeedback->close();

            // Hiển thị cửa sổ thông báo và hỏi người dùng có muốn về trang chủ không
            echo "<script>";
            echo "alert('Đánh giá thành công!');";
            echo "if (confirm('Bạn có muốn về trang chủ không?')) {";
            echo "  window.location.href = '../index.php';";
            echo "} else {";
            echo "  window.history.back();";
            echo "}";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Có lỗi xảy ra, vui lòng thử lại sau.');";
            echo "window.history.back();";
            echo "</script>";
        }
    } else {
        echo "<script>";
        echo "alert('Bạn đã đánh giá sản phẩm này trước đó.');";
        echo "window.history.back();";
        echo "</script>";
    }
}

?>
