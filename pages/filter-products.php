<?php


// Lấy tham số lọc từ yêu cầu
$hang = isset($_GET['hang']) ? $_GET['hang'] : 'all';
$price = isset($_GET['price']) ? $_GET['price'] : 'all';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 15;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'gia-tang-dan'; // Default sort

// Xây dựng câu truy vấn SQL với các bộ lọc và giới hạn
$sql = "SELECT MaSanPham, TenSanPham, GiaCu, GiaMoi, DiaChiAnh, ChipSet, Pin, TheSim
        FROM (
            SELECT sp.MaSanPham, sp.TenSanPham, gsp.GiaCu, gsp.GiaMoi, img.DiaChiAnh, CTSP.ChipSet, CTSP.Pin, CTSP.TheSim,
                   ROW_NUMBER() OVER (PARTITION BY sp.MaSanPham ORDER BY gsp.GiaMoi ASC) AS RowNum
            FROM SanPham sp 
            JOIN ChiTietSanPham CTSP ON sp.MaSanPham = CTSP.MaSanPham
            JOIN RAM_ROM_Option rr ON sp.MaSanPham = rr.MaSanPham
            JOIN Image img ON sp.MaSanPham = img.MaSanPham
            JOIN GiaSanPham gsp ON sp.MaSanPham = gsp.MaSanPham
            WHERE 1=1";

// Thêm bộ lọc hãng
if ($hang != 'all') {
    $sql .= " AND sp.Hang = '$hang'";
}

// Thêm bộ lọc giá
switch ($price) {
    case 'from_3m_to_5m':
        $sql .= " AND gsp.GiaMoi BETWEEN 3000000 AND 5000000";
        break;
    case 'from_5m_to_7m':
        $sql .= " AND gsp.GiaMoi BETWEEN 5000000 AND 7000000";
        break;
    case 'from_7m_to_10m':
        $sql .= " AND gsp.GiaMoi BETWEEN 7000000 AND 10000000";
        break;
    case 'above_10m':
        $sql .= " AND gsp.GiaMoi > 10000000";
        break;
}

// Thêm bộ lọc tìm kiếm
if (!empty($search)) {
    $sql .= " AND sp.TenSanPham LIKE '%$search%'";
}

// Thêm sắp xếp
switch ($sort) {
    case 'gia-tang-dan':
        $sql .= ' ORDER BY gsp.GiaMoi ASC';
        break;
    case 'gia-giam-dan':
        $sql .=  ' ORDER BY gsp.GiaMoi DESC';
        break;
 
}

$sql .= ") AS Subquery WHERE RowNum = 1  LIMIT $limit;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Hiển thị thông tin sản phẩm
        $product_id = $row['MaSanPham'];
        echo '<div class="product-detail back-chung">';
        echo '<a href="index.php?product_id=' . $product_id . '">';
        echo '<div class="product-img"><img src="' . $row["DiaChiAnh"] . '" alt=""></div>';
        echo '<div class="name-product">' . $row["TenSanPham"] . '</div>';
        echo '<div class="product-price-sale">' . number_format($row["GiaMoi"]) . ' vnd</div>';
        echo '<div class="product-price-old">' . number_format($row["GiaCu"]) . ' vnd</div>';
        echo '<div class="clear"></div>';
        
        $sql_rating = "SELECT MaSanPham, AVG(SoSao) AS SoSaoTrungBinh, COUNT(*) AS SoDanhGia
                       FROM Feedback
                       WHERE MaSanPham = $product_id
                       GROUP BY MaSanPham;";
        
        $result2 = $conn->query($sql_rating);
        
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $sosao = intval($row2['SoSaoTrungBinh']);
            $soDG = $row2['SoDanhGia'];
        } else {
            $sosao = 5;
            $soDG = 0;
        }
        
        echo '<div class="rating">';
        echo '<div class="rating_item">';
        echo '<ul>';
        for ($i = 1; $i <= $sosao; $i++) {
            echo '<li><i class="fas fa-star" aria-hidden="true"></i></li>'; 
        }
        echo '</ul>';
        echo '<span>' . $soDG .' Đánh giá</span>';
        
        echo '</div>';
        echo '</div>';
        echo '<div class="attributes">';
        echo '<ul>';
        echo '<li><i class="fa fa-microchip" aria-hidden="true"></i>' . $row["ChipSet"] . '</li>';
        echo '<li><i class="fa fa-mobile" aria-hidden="true"></i>' . $row["Pin"] . '</li>';
        echo '<li><i class="fa fa-battery-full" aria-hidden="true"></i>' . $row["TheSim"] . '</li>';
        echo '</ul>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo "Không có sản phẩm nào được tìm thấy.";
}

// Đóng kết nối
$conn->close();
?>
