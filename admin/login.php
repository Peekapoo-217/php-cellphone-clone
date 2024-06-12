<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="login.js" defer></script>
</head>

<body>

    <div class="login_adm">
    <?php
            if (isset($_GET['error']) && $_GET['error'] == 1) {
              echo'  <div class="alert">
                    <p>Tên Đăng Nhập hoặc Mật Khẩu không đúng</p>
                </div>';
            }
            ?>
        <h1>Đăng nhập admin</h1>
        <form action="check_login.php" method="POST" class="login_adm" id="loginForm">
            <label for="username">Tên Đăng Nhập</label>
            <input type="text" name="username" id="username" placeholder="Tên Đăng Nhập" required>
            <label for="password">Mật Khẩu</label>
            <input type="password" name="password" id="password" placeholder="Mật Khẩu" required>

            <input type="submit" value="Đăng Nhập">
        </form>
        <div id="errorMessage" style="color: red;">

        </div>
    </div>

</body>



</html>

