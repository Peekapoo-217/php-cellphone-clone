<?php

if (!isset($_SESSION['username'])) {
    header("Location: pages/login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu
include('connect.php');



$username = trim($_SESSION['username'], ' ');

// Thực hiện truy vấn SQL để lấy dữ liệu từ bảng GioHang
$sql = "SELECT * FROM giohang WHERE tendangnhap = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0; // Biến tổng giá tiền
?>


<section class="cart"  >
    <h1>Giỏ Hàng <span class="highlight" style="display: flex; justify-content: center;">Vui Lòng Kiểm Tra Địa Chỉ Trước Khi Đặt Hàng !!!</span></h1>

    <form action="server-endpoint" method="post">
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Xóa</th>
                    <th>Chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Lấy thông tin của từng sản phẩm
                        $product_id = $row['MaSanPham'];
                        $product_name = $row['TenSanPham'];
                        $color = $row['MauSac'];
                        $memory = $row['KichThuoc'];
                        $image = $row['DiaChiAnh'];
                        $price = $row['GiaMoi'];
                        $quantity = $row['Soluong'];

                        // Hiển thị thông tin sản phẩm trong giỏ hàng
                ?>
                        <tr>
                            <td style="display: flex; align-items: center;">
                                <img style="width: 70px;" src="./<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product_name); ?>">
                                <span><?php echo htmlspecialchars($product_name) . ' - ' . htmlspecialchars($color) . ' - ' . htmlspecialchars($memory); ?></span>
                            </td>
                            <td>
                                <p><span><?php echo number_format($price, 0, ',', '.'); ?></span></p>
                            </td>
                            <td>
                                <input type="number" class="quantity-input" data-product-id="<?php echo $product_id; ?>" value="<?php echo $quantity; ?>" min="1" max="10">
                            </td>
                            <td>
                                <button class="delete-button" data-product-id="<?php echo $product_id; ?>" data-color="<?php echo $color; ?>" data-memory="<?php echo $memory; ?>">Xóa</button>
                            </td>


                            <td>
                                <input type="checkbox" class="product-checkbox" data-product-id="<?php echo $product_id; ?>" data-price="<?php echo $price; ?>" data-quantity="<?php echo $quantity; ?>">
                            </td>
                        </tr>
                <?php
                        $total_price += $price * $quantity; // Tính tổng giá tiền
                    }
                } else {
                    echo '<tr><td colspan="5">Giỏ hàng của bạn trống.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <div style="text-align: right;" class="price-total">
            <p style="font-weight: bold;">Tổng tiền: <span id="total-price"><?php echo number_format($total_price, 0, ',', '.'); ?></span></p>
            <button type="button" class="order-button" onclick="goHome()">Quay về trang chủ</button>
            <button type="button" class="order-button" onclick="checkout()">Đặt hàng ngay</button>
        </div>
    </form>
</section>

<script src="./js/cart.js"></script>