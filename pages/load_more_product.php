<?php
// Đọc giá trị hiện tại của $limit từ file filter-products.php
include('filter-products.php');

// Tăng giới hạn lên 3 sản phẩm nữa
$limit += 3;

// Ghi đè giá trị mới vào file filter-products.php
$file_content = file_get_contents('filter-products.php');
$file_content = preg_replace("/\$limit = \d+;/", "\$limit = $limit;", $file_content);
file_put_contents('filter-products.php', $file_content);

echo "success";
?>
