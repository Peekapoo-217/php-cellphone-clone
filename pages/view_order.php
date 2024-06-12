<?php

if (!isset($_SESSION['username'])) {
    header("Location: pages/login.php");
    exit();
}

?>
<body>
    <div class="box-myorder">
        <div class="box-myorder__title">
            <h3>Xem thông tin đơn hàng</h3>
        </div>
        <div class="box-myorder__content">
            <?php
            // session_start();
            require_once 'connect.php';

          
            $username = $_SESSION['username'];

            $sql = "SELECT hd.MaHoaDon, cthd.TenMau, cthd.KichThuoc, kh.HoTen, hd.TenDangNhap, kh.DiaChi, hd.NgayLap, sp.TenSanPham, cthd.SoLuong, cthd.ThanhTien, hd.TrangThai, hd.TongTien, sp.MaSanPham,
                kh.MaKhachHang, hd.TongTien
                    FROM hoadon AS hd
                    JOIN chitiethoadon AS cthd ON hd.MaHoaDon = cthd.MaHoaDon
                    JOIN khachhang AS kh ON kh.TenDangNhap = hd.TenDangNhap
                    JOIN sanpham AS sp ON sp.MaSanPham = cthd.MaSanPham
                    WHERE hd.TenDangNhap = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            $orders = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $orders[$row["MaHoaDon"]]["details"][] = $row;
                    $orders[$row["MaHoaDon"]]["info"] = [
                        "TenDangNhap" => $row["TenDangNhap"],
                        "HoTen" => $row["HoTen"],
                        "DiaChi" => $row["DiaChi"],
                        "NgayLap" => $row["NgayLap"],
                        "TrangThai" => $row["TrangThai"],
                        "TongTien" => $row["TongTien"],
                        "MaKhachHang" => $row["MaKhachHang"],
                        "TenMau" => $row["TenMau"],
                        "KichThuoc" => $row["KichThuoc"],
                        "TongTien" => $row["TongTien"],
                        "MaHoaDon" => $row["MaHoaDon"]
                    ];
                }

                foreach ($orders as $maHoaDon => $order) {
                    echo '<div class="container--order" id="order-' . htmlspecialchars($maHoaDon) . '">';
                    echo '<div class="box-order__detail">';
                    
                    echo '<div class="myorder__title">Mã đơn hàng:</div>';
                    echo '<div class="myorder__text">' . htmlspecialchars($maHoaDon) . '</div>';
                    echo '</div>';
                
                    echo '<div class="box-order__detail">';
                    echo '<div class="myorder__title">Tên khách hàng:</div>';
                    echo '<div class="myorder__text">' . htmlspecialchars($order["info"]["HoTen"]) . '</div>';
                    echo '</div>';
                
                    echo '<div class="box-order__detail">';
                    echo '<div class="myorder__title">Địa chỉ:</div>';
                    echo '<div class="myorder__text">' . htmlspecialchars($order["info"]["DiaChi"]) . '</div>';
                    echo '</div>';
                
                    echo '<div class="box-order__detail">';
                    echo '<div class="myorder__title">Ngày tạo đơn:</div>';
                    echo '<div class="myorder__text">' . htmlspecialchars($order["info"]["NgayLap"]) . '</div>';
                    echo '</div>';
                
                    
                
                    echo '<div class="box-bill-detail">';
                    echo '<div class="container-detail">';
                    echo '<div class="box-bill-detail__content">';
                    echo '<div class="detail-product">Sản phẩm</div>';
                    echo '<div class="quantity-product">Số Lượng</div>';
                    echo '<div class="price-total">Giá tiền</div>';
                    echo '<div class="status-transport">Trạng thái hàng</div>';
                    echo '</div>';
                    echo '</div>';
                
                    foreach ($order["details"] as $detail) {
                        echo '<div class="container-detail">';
                        echo '<div class="box-bill-detail__content">';
                        echo '<div class="detail-product">' . htmlspecialchars($detail["TenSanPham"]) . ' - ' . htmlspecialchars($detail["TenMau"]) . ' - ' . htmlspecialchars($detail["KichThuoc"]) . '</div>';
                        echo '<div class="quantity-product">' . htmlspecialchars($detail["SoLuong"]) . '</div>';
                        echo '<div class="price-total">' . number_format($detail["ThanhTien"], 0, ',', '.') . ' đ</div>';
                        echo '<div class="status-transport">' . htmlspecialchars($order["info"]["TrangThai"]) . '</div>';
                        echo '</div>';
                
                        if ($order["info"]["TrangThai"] === "Đã giao hàng") {
                            echo '<div class="box-product-review">';
                            echo '<form action="pages/submit_review.php" method="POST">'; 
                            echo '<input type="hidden" name="MaSanPham" value="' . htmlspecialchars($detail["MaSanPham"]) . '">';
                            echo '<input type="hidden" name="TenDangNhap" value="' . htmlspecialchars($username) . '">';
                            echo '<input type="hidden" name="MaKhachHang" value="' . htmlspecialchars($order["info"]["MaKhachHang"]) . '">';
                            echo '<input type="hidden" name="HoTen" value="' . htmlspecialchars($order["info"]["HoTen"]) . '">';
                            echo '<input type="hidden" name="MaHoaDon" value="' . htmlspecialchars($order["info"]["MaHoaDon"]) . '">';

                            echo '<input type="text" name="BinhLuan" placeholder="Hãy đánh giá sản phẩm của chúng tôi">';
                            echo '<label for="SoSao">Số sao: </label>';
                            echo '<select name="SoSao">';
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            echo '</select>';
                            echo '<button type="submit">Gửi đánh giá</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '<div class= "box-price__delete" >';
                    echo '<div class="total-price">' . 'Tổng tiền hóa đơn: ' . number_format($order["info"]["TongTien"], 0, ',', '.') . ' đ</div>';
                    // Thêm nút xóa nếu đơn hàng chưa xác nhận
                    if ($order["info"]["TrangThai"] === "Chưa xác nhận") {
                        echo '<button class="delete-order-btn" data-order-id="' . htmlspecialchars($maHoaDon) . '">Hủy đơn hàng</button>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
                
            } else {
                echo '<div class="box-order__detail">Không có đơn hàng nào.</div>';
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
    <script>
        document.querySelectorAll('.delete-order-btn').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
                    fetch('pages/delete_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'MaHoaDon=' + orderId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Đơn hàng đã được xóa thành công.');
                            document.getElementById('order-' + orderId).remove();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>