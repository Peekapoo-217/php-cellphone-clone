<?php
// Kết nối đến cơ sở dữ liệu
include('connect.php');

// Lấy mã sản phẩm từ URL và kiểm tra hợp lệ
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
if ($product_id == 0) {
  die('ID sản phẩm không hợp lệ.');
}
// Câu truy vấn SQL
$sql = "SELECT * From SanPham Where MaSanPham = $product_id";
// Lấy video trailer dựa vào mã sản phẩm
$sql_video = "SELECT DiaChiVideo FROM Video WHERE masanpham = $product_id";
// Hiển thị box image dưới trailer
$sql_boxicon = "SELECT DISTINCT sp.MaSanPham, i.DiaChiAnh, c.TenMau, sp.TenSanPham
FROM Image i
JOIN SanPham AS sp ON sp.MaSanPham = i.MaSanPham
JOIN Colors c ON i.MaMau = c.MaMau WHERE i.MaSanPham = $product_id;
";
// Lấy 5 sản phẩm ngẫu nhiên
$sql_related_products = "
    SELECT MaSanPham, TenSanPham, GiaCu, GiaMoi, DiaChiAnh, ChipSet, Pin, TheSim
    FROM (
        SELECT sp.MaSanPham, sp.TenSanPham, gsp.GiaCu, gsp.GiaMoi, img.DiaChiAnh, CTSP.ChipSet, CTSP.Pin, CTSP.TheSim,
               ROW_NUMBER() OVER (PARTITION BY sp.MaSanPham ORDER BY gsp.GiaMoi ASC) AS RowNum
        FROM SanPham sp 
        JOIN ChiTietSanPham CTSP ON sp.MaSanPham = CTSP.MaSanPham
        JOIN RAM_ROM_Option rr ON sp.MaSanPham = rr.MaSanPham
        JOIN Image img ON sp.MaSanPham = img.MaSanPham
        JOIN GiaSanPham gsp ON sp.MaSanPham = gsp.MaSanPham
    ) AS Subquery
    WHERE RowNum = 1
    ORDER BY RAND()
    LIMIT 5;
";
/// Hiển thị thông tin ram và rom 
$sql_option_memory = "SELECT DISTINCT
rro.KichThuoc AS DungLuongBoNho,
 cl.TenMau,
gs.GiaMoi,gs.GiaCu,
gs.SoLuong
FROM 
ram_rom_option AS rro
JOIN 
GiaSanPham gs ON rro.MaRam = gs.MaRam
JOIN colors AS cl ON cl.MaMau = gs.MaMau
WHERE 
gs.MaSanPham = $product_id
GROUP by 
rro.KichThuoc DESC;";

$result_related_products = $conn->query($sql_related_products);
// Hiển thị chi tiết sản phẩm
$sql_chitiet = "SELECT * FROM chitietsanpham AS ct 
INNER JOIN ram_rom_option AS rr ON ct.MaSanPham = rr.MaSanPham
WHERE ct.MaSanPham = $product_id;";
//// Hiển thị đánh giá sản phẩm 
$sql_rating = "SELECT MaSanPham, AVG(SoSao) AS SoSaoTrungBinh, COUNT(*) AS SoDanhGia
FROM Feedback
WHERE MaSanPham = $product_id
GROUP BY MaSanPham;";
$sql_reviews= "SELECT * FROM Feedback WHERE MaSanPham = $product_id";
?>

<!-- Content -->
<div id="content">
  <!-- header detail product -->
  <div class="header-detail-product">
    <div class="product-name">
      <?php
      // Thực thi truy vấn SQL
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        // Lấy dữ liệu từ kết quả truy vấn
        $row  = $result->fetch_assoc();
        // Hiển thị tên sản phẩm trong thẻ div
        echo '<div class="product-name">' . htmlspecialchars($row["TenSanPham"]) . '</div>';
      } else {
        echo "Không có sản phẩm nào được tìm thấy";
      }
      $result->close();
      ?>
    </div>
    <?php
    $result = $conn->query($sql_rating);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $sosao = intval($row['SoSaoTrungBinh']);
      $soDG = $row['SoDanhGia'];
    } else {
      $sosao = 5;
      $soDG = 0;
    }
    echo '<div class="detail-rate">';
    echo '<p style="color: #f3f313">';
    for ($i = 1; $i <= $sosao; $i++) {
      echo '<i class="fa-solid fa-star fa-star_fix"></i>';
    }
    echo'    </p>
    <p class="detail-rate-total">'.$soDG.'<span> đánh giá</span></p>';
    $result->close();
    ?>



  </div>
</div>
<!-- Body-product -->
<div class="body-product">
  <div class="product-detail-left">
    <div class="box-pr-product ">
      <div class="video_container">
        <?php
        $result = $conn->query($sql_video);
        if ($result->num_rows > 0) {
          // Lấy đường dẫn video từ kết quả truy vấn
          $row_video = $result->fetch_assoc();
          $video_path = $row_video['DiaChiVideo'];

          // Chuyển đổi liên kết Google Drive thành định dạng nhúng
          $embed_link = str_replace("view?usp=drive_link", "preview", $video_path);

          // Hiển thị video trên trang web bằng iframe
          echo '<div class="video_container" id="videoContainer">
                            <iframe src="' . htmlspecialchars($embed_link) . '" width="100%" height="500px" frameborder="0" allowfullscreen autoplay muted></iframe>
                          </div>';
        } else {
          // Hiển thị thông báo nếu không tìm thấy video
          echo "Không tìm thấy video cho sản phẩm này.";
        }
        ?>
      </div>
      <!----  Lấy video ---->
      <!-- Tab scrolling -->
      <div class="box-tab-scrolling">
        <!-- tab1 -->
        <div class="box-tab-img" onclick="changeVideo(this)" data-src="<?php echo htmlspecialchars($embed_link); ?>">
          <div class="item_border">
            <i class="fa-brands fa-youtube" style="font-size: 23px;"></i>
          </div>
          <p class="tab-scrolling_title">Video</p>
        </div>

        <!---- Chang image --->
        <?php
        $result = $conn->query($sql_boxicon);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<div class="box-tab-img" onclick="changeImage(this)" data-src="' . $row["DiaChiAnh"] . '">';
            echo '<div class="item_border">';
            echo '<img src="' . $row["DiaChiAnh"] . '" alt="" />';
            echo '</div>';
            echo '<p class = "tab-scrolling_title">' . $row["TenMau"] . '</p>';
            echo '</div>';
          }
        } else {
          echo "0 results";
        }
        ?>
        <div class="box-tab-img js-viewmore__detail">
          <div class="item_border">
            <i class="fa-solid fa-box-open  fontsize-icon"></i>
          </div>
          <p class="tab-scrolling_title">Thông số kỹ thuật</p>
        </div>
      </div>
    </div>
    <!--  -->
    <div class="box-policy-buy">
      <ul class="list-policy">
        <li>
          <i class="fa-solid fa-repeat fontsize-icon edit__margin-icon"></i>
          <p class="list-policy_text">
            Hư gì đổi nấy <strong>12 tháng</strong> tại 3158 siêu thị toàn
            quốc (miễn phí tháng đầu)
            <a href="#"> Xem chi tiết</a>
          </p>
        </li>
        <li>
          <i class="fa-solid fa-shield fontsize-icon edit__margin-icon"></i>
          <p class="list-policy_text">
            Bảo hành <strong>chính hãng điện thoại 1 năm</strong> tại các
            trung tâm bảo hành
            <a href="#"> Xem địa chỉ bảo hành</a>
          </p>
        </li>
        <li>
          <i class="fa-solid fa-box-archive fontsize-icon edit__margin-icon"></i>
          <p class="list-policy_text">
            Bộ sản phẩm gồm: Hộp, Sách hướng dẫn, Cây lấy sim, Cáp Type C
          </p>
        </li>
      </ul>
    </div>

    <!-- related acc needbuy -->
    <div class="related-acc-needbuy">
      <div class="realated-ttl">
        <span>Các sản phẩm tương tự</span>
        <div class="icon-help-needbuy"><span>?</span></div>
      </div>
      <div class="realated-needbuy-container">
        <?php
        $result_related_products = $conn->query($sql_related_products);
        if ($result_related_products->num_rows > 0) {
          while ($row = $result_related_products->fetch_assoc()) {
            echo '<div class="item-need-buy">';
            echo '<img src="' . ($row["DiaChiAnh"]) . '" alt="' . ($row["TenSanPham"]) . '" class="img-product-needbuy">';
            echo '<h3 class="item-name-needbuy">
              <a href="index.php?product_id=' . $row["MaSanPham"] . '">' . ($row["TenSanPham"]) . '</a></h3>';
            echo '<div class="box-price-percent">';
            echo '<p class="price-oldblack">' . number_format($row["GiaCu"], 0, ',', '.') . '</p>';
            echo '</div>';
            echo '<div class="price-needbuy">' . number_format($row["GiaMoi"], 0, ',', '.') . '</div>';
            echo '</div>';
          }
        } else {
          echo "Không có sản phẩm nào được tìm thấy";
        }
        ?>
      </div>
    </div>
    <div class="customer-review">
      <div class="review-header">
        <h3>Đánh giá từ khách hàng</h3>
      </div>
      <div class="review-content">
        <ul class="review-list">
          <?php
          // Giả sử bạn đã kết nối với cơ sở dữ liệu và có biến $conn
          $result_reviews = $conn->query($sql_reviews);
          if ($result_reviews->num_rows > 0) {
            while ($row = $result_reviews->fetch_assoc()) {
              echo '<li class="review-list_item">';
              echo '<div class="review-user">';
              echo '<p class="review-user_name_date">' . htmlspecialchars($row["HoTen"]) . " " . htmlspecialchars($row["Ngay"]) . '</p>';
              echo '<p class="review-user_star">' . renderStars(htmlspecialchars($row["SoSao"])) . '</p>';
              echo '</div>';
              echo '<div class="review-text">';
              echo '<p>' . htmlspecialchars($row["BinhLuan"]) . '</p>';
              echo '</div>';
              echo '</li>';
            }
          } else {
            echo "Không có đánh giá nào cho sản phẩm";
          }
          $result_reviews->close();

          function renderStars($rating)
          {
            $fullStars = str_repeat('★', $rating); // Số ngôi sao đầy đủ
            $emptyStars = str_repeat('☆', 5 - $rating); // Số ngôi sao trống
            return $fullStars . $emptyStars; // Ghép ngôi sao đầy đủ và ngôi sao trống
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
  <!-- Form nhận thông sản phẩm -->
  <div class="product-detail-right">
    <form id="simple-buy-product" action="." name="simple-buy-product" method="GET">
      <div class="status_quantity">
        Trình trạng:
        <span class="status_product"></span>

      </div>
      <!--- Kiểm tra trình trạng hàng  --->
      <?php
      $product_id = isset($_GET['product_id']);
      $color = isset($_GET['data-color']);
      $memory = isset($_GET['data-memory']) ? $_GET['memory'] : '';
      ?>


      <div style="color: red; font-size: 18px; margin-top: 5px;">
        <strong>Vui lòng chọn màu sắc và phiên bản bộ nhớ:</strong>
      </div>
      <div class="choice-option__text">Chọn Màu Sắc:</div>
      <div class="choice_colors">
        <?php
        $result = $conn->query($sql_boxicon);

        if ($result->num_rows > 0) {

          while ($row = $result->fetch_assoc()) {
            echo '<div class="box-tab-img2 " onclick="selectColor(this)" data-color="' . htmlspecialchars($row["TenMau"])
              . '" data-image="' . htmlspecialchars($row["DiaChiAnh"]) . '" data-name="' . htmlspecialchars($row["TenSanPham"]) . '"data-id="' . htmlspecialchars($row["MaSanPham"]) . '">';
            echo '<div class="option_colors_product">';

            echo '<div class="color__product">';
            echo '<div class="img-color-product">';
            echo '<img src="' . htmlspecialchars($row["DiaChiAnh"]) . '" alt="" />';
            echo '</div>';
            echo '<div class="info_color_price">';
            echo '<div class="name__color">' . htmlspecialchars($row["TenMau"]) . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }
        } else {
          echo "0 results";
        }
        ?>
      </div>

      <div class="choice-option__text">Chọn Phiên Bản Bộ nhớ:</div>
      <div class="choice-memory">
        <div class="options-memory">
          <?php
          $result = $conn->query($sql_option_memory);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo '<div class="in_sock" onclick="selectMemory(this)"  data-memory="' . htmlspecialchars($row['DungLuongBoNho']) . '" data-price="' . htmlspecialchars($row['GiaMoi']) . '">';
              echo '<div class="extend_name">' . htmlspecialchars($row['DungLuongBoNho']) . '</div>';
              echo '<div class="extend_price">' . number_format($row['GiaMoi'], 0, ',', '.') . '</div>';
              echo '</div>';
            }
          } else {
            echo '<div>Không có dữ liệu</div>';
          }
          ?>
        </div>
      </div>
      <?php
      $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
      ?>
      <!-- Các trường ẩn lấy dữ liệu -->
      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
      <input type="hidden" name="color" id="selectedColor" value="">
      <input type="hidden" name="memory" id="selectedMemory" value="">
      <input type="hidden" name="image" id="selectedImage" value="">
      <input type="hidden" name="price" id="selectedPrice" value="">
      <input type="hidden" name="product_name" id="selectedName" value="">
      <input type="hidden" name="id_product" id="selectedID" value="">

      <!--  -->
      <div class="frame_dt_promotion">
        <div class="box-text-promotion">
          <i class="fa-solid fa-gift fontsize-icon " style="margin: 10px 5px 0 5px;; color: #fff"></i>
          <h3 style="margin: 10px 0 10px 0;">Khuyến Mãi</h3>
        </div>
        <div class="box-content-promotion">
          <div class="content-promottion">
            <div class="icon-tick-check"><i class="fa-solid fa-check fontsize-icon"></i></i></div>
            <strong>Tặng combo: Dán cường lực + Ốp lưng cao cấp trị giá 300k</strong>
          </div>

          <div class="content-promottion">
            <div class="icon-tick-check"><i class="fa-solid fa-check fontsize-icon"></i></i></div>
            <strong>Duy nhất tại CellPhoneK: iPhone chính hãng VN/A rẻ như hàng cũ</strong>
          </div>

          <div class="content-promottion">
            <div class="icon-tick-check"><i class="fa-solid fa-check fontsize-icon"></i></i></div>
            Trợ giá mua củ sạc nhanh 33W PD chính hãng chỉ 250k
          </div>

          <div class="content-promottion">
            <div class="icon-tick-check"><i class="fa-solid fa-check fontsize-icon"></i></i></div>
            Trả góp nhanh, lãi suất 0% qua thẻ tín dụng
          </div>

          <div class="content-promottion">
            <div class="icon-tick-check"><i class="fa-solid fa-check fontsize-icon"></i></i></div>
            Mua Online: Giao hàng tận nhà- Nhận hàng thanh toán
          </div>
        </div>

      </div>

      <div class="box-buy-product">
        <div class="btn-buy-product" id="add-cart-now">
          <div class="cart-text-icon">
            <i class="fa-solid fa-cart-shopping fontsize-icon"></i>
            Mua Ngay
          </div>
          <span class="text__small">Giao tận nhà(COD) hoặc nhận tại cửa hàng</span>
        </div>
        <div class="cart-add-detail" id="buy-now">
          <a href="pages/cart.php"></a>
          <div class="text-cart-auto">
            <div class="icon-cart"><i class="fa-solid fa-cart-arrow-down fontsize-icon"></i></i></div>
            <span style="font-size: 14px;">Thêm vào giỏ</span>
          </div>
        </div>
      </div>
    </form>
    <!--  -->
    <?php
    $result = $conn->query($sql_chitiet);
    if ($result->num_rows > 0) {

      $rowtt  = $result->fetch_assoc();
    }
    ?>
    <div class="box-specifications">
      <div class="box-title-paramater">
        Thông số kỹ thuật
      </div>
      <table class="table-parammater">
        <!--  -->
        <tr class="box-content-paramater backgound-grey ">
          <td class="title_charactestic  ">
            Thẻ SIM:
          </td>
          <td class="content_charactestic ">
            <p><?php echo $rowtt['TheSim'] ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater">
          <td class="title_charactestic">
            Màn hình:
          </td>
          <td class="content_charactestic">
            <p><?php echo $rowtt['KichThuocManHinh'] . ', ' . $rowtt['CongNgheManHinh'] . ', ' . $rowtt['TinhNangManHinh']; ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic">
            Độ phân giải:
          </td>
          <td class="content_charactestic">
            <p><?php echo $rowtt['DoPhanGiaiManHinh']; ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater">
          <td class="title_charactestic">
            CPU:
          </td>
          <td class="content_charactestic">
            <p><?php echo $rowtt['ChipSet'] ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic">
            Bộ nhớ:
          </td>
          <td class="content_charactestic">
            <p><?php echo $rowtt['KichThuoc'] ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic">
            Camera sau:
          </td>
          <td class="content_charactestic">
            <p><?php echo $rowtt['CameraSau'] . ', ' . $rowtt['QuayVideoSau'] ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic">
            Camera trước:
          </td>
          <td class="content_charactestic">

            <p><?php echo $rowtt['CameraTruoc'] . ', ' . $rowtt['QuayVideoTruoc'] ?></p>
          </td>
        </tr>


        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic">
            Pin:
          </td>
          <td class="content_charactestic">
            <p><?php echo $rowtt['Pin'] . ', ' . $rowtt['CongNgheSac'] ?></p>
          </td>
        </tr>
        </tr>
      </table>

      <div class="box-viewmore-detail">
        <button class="js-viewmore__detail">Xem thêm thông tin</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<div class="modal js-modal">
  <div class="box-detail-config">
    <div class="box-paramater">
      <div class="title-paramater">
        THÔNG SỐ KỸ THUẬT
      </div>
      <div class="box-paramater__closing js-modal-close">
        <i class="fa-solid fa-xmark" style="font-size: 25px;"></i>
      </div>
    </div>
    <div class="box-table-view-detail">
      <table class="table-parammater">
        <!-- -->
        <tr class="box-content-paramater backgound-grey ">
          <td class="title_charactestic  ">
            Kích thước màn hình
          </td>
          <td class="content_charactestic fix-td__margin">
            <p><?php echo $rowtt['KichThuocManHinh'] ?></p>
          </td>
        </tr>

        <tr class="box-content-paramater">
          <td class="title_charactestic  ">
            Công Nghệ Màn Hình
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['CongNgheManHinh'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey ">
          <td class="title_charactestic  ">
            Độ Phân Giải Màn Hình
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['DoPhanGiaiManHinh'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Tần số quét màn hình
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['TinhNangManHinh'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Camera Sau
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['CameraSau'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Quay Video Camera Sau
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['QuayVideoSau'] ?>
          </td>
        </tr>
        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Camera Trước
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['CameraTruoc'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Quay Video Camera Trước
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['QuayVideoTruoc'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            ChipSet
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['ChipSet'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Ram & Lưu Trữ
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['KichThuoc'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Pin
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['Pin'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Công Nghệ Sạc
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['CongNgheSac'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Thẻ sim
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['TheSim'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Hệ Điều Hành
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['HeDieuHanh'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Hỗ trợ mạng
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['HoTroMang'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Wifi
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['Wifi'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Bluetooth
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['Bluetooth'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            GPS
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['Gps'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater backgound-grey">
          <td class="title_charactestic  ">
            Chuẩn Kháng Nước Bụi
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['KhangNuocBui'] ?>
          </td>
        </tr>

        <tr class="box-content-paramater ">
          <td class="title_charactestic  ">
            Công Nghệ Âm Thanh
          </td>
          <td class="content_charactestic">
            <?php echo $rowtt['CongNgheAmThanh'] ?>
          </td>
        </tr>

      </table>
    </div>

  </div>

</div>

<script>
  <?php
  echo 'var productId = ' . $_GET['product_id'] . ';';
  ?>
  var color = '';
  var memory = '';
  var image = '';
  var price = '';
  var product_name = '';
  var id_product = '';

  $(document).ready(function() {
    $('.box-tab-img2').click(function() {
      color = $(this).attr('data-color');
      image = $(this).attr('data-image');
      product_name = $(this).attr('data-name');
      id_product = $(this).attr('data-id');
      $('#selectedColor').val(color);
      $('#selectedImage').val(image);
      $('#selectedName').val(product_name);
      $('#selectedID').val(id_product);

      fetch(id_product);
      getStatusOfVariation();
    });

    $('.in_sock').click(function() {
      memory = $(this).attr('data-memory');
      price = $(this).attr('data-price');
      $('#selectedMemory').val(memory);
      $('#selectedPrice').val(price);
      getStatusOfVariation();
    });

    $('#buy-now').click(function() {
      if (color !== '' && memory !== '') {
        $('#simple-buy-product').submit();
      }
    });

    function getStatusOfVariation() {
      if (color !== '' && memory !== '') {
        var action = 'getStatusOfVariation';

        $.ajax({
          type: 'post',
          url: './php/ajax_response.php',
          dataType: 'text',
          data: {
            action,
            productId,
            color,
            memory
          }
        }).done(function(response) {
          $('.status_product').text(response);
        })
      }
    }
  })
</script>

<!-- End content -->