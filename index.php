<?php
session_start();

// Kiểm tra nếu session đã được khởi tạo
if (isset($_SESSION['CREATED'])) {
  // Nếu session đã tồn tại hơn 300 giây
  if (time() - $_SESSION['CREATED'] > 300) {
      // Hủy session và khởi động lại
      session_unset();
      session_destroy();
      session_start();
      $_SESSION['CREATED'] = time();
  }
} else {
  // Nếu session chưa được khởi tạo, thiết lập thời gian tạo
  $_SESSION['CREATED'] = time();
}
if (isset($_GET['message']) && $_GET['message'] == 'logout') {
  echo '<script>alert("Bạn đã đăng xuất thành công!");</script>';
}
?>
<!DOCTYPE html>
<html lang="VN-vi">

<head>


  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CellPhoneK</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="icon" href="img/main/icon_logo.png">


    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/product_detail.css">
    <link rel="stylesheet" href="css/tintuc.css">
    <link rel="stylesheet" href="css/user-css.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

     
</head>

<body>
    <div class="wrapper">
        <?php
        include 'pages/header.php';
      
        if (isset($_GET['product_id'])) {
          include 'pages/product_detail.php';
          include 'pages/purchase_Product.php';
        }
        else if (isset($_GET['tenKH']) && isset($_SESSION['username'])) {
          include 'pages/user.php';
         
        }
        else if (isset($_GET['cart'])) {
          // include 'pages/create_hoadon.php';
          // include 'pages/sidebar_User.php';
          // include 'pages/content_Cart.php';
          include 'pages/cart.php';                  
        }
        else if (isset($_GET['donmua'])){

          include 'pages/myorder.php';                  

        }
        else {
          // Nếu không có product_id, include trang main.php
          include 'pages/main-choose.php';
          include 'pages/main.php';
        }
        include 'pages/footer.php';       
     ?>

  </div>
      <!-- Xử lý javaScrip -->
  <script src="./js/product_detail.js"></script> 
</body>

</html>