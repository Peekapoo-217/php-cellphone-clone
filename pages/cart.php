<html>
<head>
    
    <link rel="stylesheet" href="./css/user-css.css">
    <link rel="stylesheet" type="text/css" href="./css/cart.css">
</head>
<body>
    <div class="wrapper">
        <div class="wp-content">
            <?php 
                include 'sidebar_User.php';

                echo'<div class="content-right">';
                include 'content_Cart.php';
                echo'</div>';
                
            //    include 'purchase_product.php';
            ?>
        </div>
        <script src="../js/cart.js"></script>
    </div>
</body>
</html>

