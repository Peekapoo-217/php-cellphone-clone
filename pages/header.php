<?php
ob_start(); // Bắt đầu thu thập đầu ra đến khi được gửi
// Code của bạn ở đây
?>



<div class="header">
    <div class="topweb">
        <img src="img/main/HD_banner.png" alt="">
    </div>
    <div class="bottomweb">
        <div class="header-menu">
            <!-- left -->
            <div class="logo_search">
                <a href="index.php"><img src="img/main/Menu_logo.png" alt=""></a>
                <form action="." method="GET" style="display: flex;">
                    <input type="text" name="search" placeholder="Bạn tìm gì..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>


            <!-- right -->
            <div class="nav">
                <!-- dien thoai -->
                <?php
                include 'connect.php';

                $sql = "SELECT Hang FROM SanPham
                GROUP BY Hang";

                $result = $conn->query($sql);

                echo '<div class="nav-item">';
                echo '<a href="#">';
                echo '<div class="img">';
                echo '<img src="img/main/phone.png" alt="">';
                echo '</div>';
                echo '<div class="name">Điện Thoại</div>';
                echo '<div class="sub-menu">';
                echo '<ul>';

                if ($result->num_rows > 0)
                    while ($row = $result->fetch_assoc()) {
                        echo '<li><a href="index.php?hang='.$row['Hang'].'&price=all" title="">' . $row['Hang'] . '</a></li>';
                    }

                echo '</ul>';
                echo '</div>';
                echo '</a>';
                echo '</div>';

                echo '<div class="nav-item">';
                echo '<a href="#">';
                echo '<div class="img">';
                echo '<img src="img/main/samsung.png" alt="">';
                echo '</div>';
                echo '<div class="name">SamSung</div>';
                echo '<div class="sub-menu">';
                echo '<ul>';

                $sql = "SELECT *
                FROM SanPham
                WHERE Hang like 'Samsung'
                ORDER BY NgayNhap DESC
                LIMIT 8";

                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['MaSanPham'];
                        echo '<li><a href="index.php?product_id=' . $product_id . '" title="">' . $row['TenSanPham'] . '</a></li>';
                    }


                echo '</ul>';
                echo '</div>';
                echo '</a>';
                echo '</div>';

                echo '<div class="nav-item">';
                echo '<a href="#">';
                echo '<div class="img">';
                echo '<img src="img/main/xiaomi.png" alt="">';
                echo '</div>';
                echo '<div class="name">Xiaomi</div>';
                echo '<div class="sub-menu">';
                echo '<ul>';
                $sql = "SELECT *
                FROM SanPham
                WHERE Hang like 'Xiaomi'
                ORDER BY NgayNhap DESC
                LIMIT 8";

                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['MaSanPham'];
                        echo '<li><a href="index.php?product_id=' . $product_id . '" title="">' . $row['TenSanPham'] . '</a></li>';
                    }
                echo '</div>';
                echo '</a>';
                echo '</div>';

                echo '<div class="nav-item">';
                echo '<a href="#">';
                echo '<div class="img">';
                echo '<img src="img/main/oppo.png" alt="">';
                echo '</div>';
                echo '<div class="name">Oppo</div>';
                echo '<div class="sub-menu">';
                echo '<ul>';
                $sql = "SELECT *
                FROM SanPham
                WHERE Hang like 'Oppo'
                ORDER BY NgayNhap DESC
                LIMIT 8";

                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['MaSanPham'];
                        echo '<li><a href="index.php?product_id='.$product_id.'" title="">'.$row['TenSanPham'].'</a></li>';
                    }
                echo '</ul>';
                echo '</div>';
                echo '</a>';
                echo '</div>';

                echo '<div class="nav-item">';
                echo '<a href="#">';
                echo '<div class="img">';
                echo '<img src="img/main/realme.jpg" alt="">';
                echo '</div>';
                echo '<div class="name">Realme</div>';
                echo '<div class="sub-menu">';
                echo '<ul>';
                $sql = "SELECT *
                FROM SanPham
                WHERE Hang like 'Realme'
                ORDER BY NgayNhap DESC
                LIMIT 8";

                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['MaSanPham'];
                        echo '<li><a href="index.php?product_id=' . $product_id . '" title="">' . $row['TenSanPham'] . '</a></li>';
                    }
                echo '</ul>';
                echo '</div>';
                echo '</a>';
                echo '</div>';
                ?>

                <!-- Giỏ hàng -->
                <div class="nav-item">
                    <a href="<?php echo isset($_SESSION['username']) ? 'index.php?cart' : 'pages/login.php'; ?>">
                        <div class="img">
                        <i class="fa-solid fa-cart-shopping" style="margin-top: 12px; color: white;  "></i>
                        </div>
                        <div class="name">Giỏ hàng</div>
                    </a>
                </div>

                <!-- user -->

                <?php

                $tenKH = 'User';
                if (isset($_SESSION['username'])) {
                    $sql = "SELECT HoTen FROM KhachHang WHERE TenDangNhap = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $_SESSION['username']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $tenKH = $row['HoTen'];
                    }
                }


                ?>
                <div class="nav-item">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<a href="index.php?tenKH"  >';
                    } else
                        echo '<a href="pages/login.php">';
                    ?>
                    <div class="img">
                        <i class="fa-solid fa-user" style="margin-top: 12px; color: white;  "></i>
                    </div>
                    <?php
                    echo '<div class="name">' . $tenKH . '</div>';
                    ?>
                    </a>
                </div>
            </div>
        </div>
    </div>