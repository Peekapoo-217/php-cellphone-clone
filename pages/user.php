<div class="wrapper">
    <div class="wp-content">
        <?php 
            include 'sidebar_User.php';
            if(isset($_GET['cart'])) {
                include 'content_Cart.php';
            } else {
                include 'content_User.php';
            }
            
        ?>
    </div>
    <script src="pages/editName.js"></script>
</div>

<script>
    // JavaScript code here (if any)
</script>
