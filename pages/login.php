<?php

// Bắt đầu session
session_start();

// // Kiểm tra nếu session đã được khởi tạo
// if (isset($_SESSION['CREATED'])) {
//     // Nếu session đã tồn tại hơn 30 giây
//     if (time() - $_SESSION['CREATED'] > 300) {
//         // Hủy session và khởi động lại
//         session_unset();
//         session_destroy();
//         session_start();
//         $_SESSION['CREATED'] = time();
//     }
// } else {
//     // Nếu session chưa được khởi tạo, thiết lập thời gian tạo
//     $_SESSION['CREATED'] = time();
// }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <script>
        function validateForm() {
            var username = document.forms["loginForm"]["username"].value;
            var password = document.forms["loginForm"]["password"].value;
            var valid = true;
            var errorMsg = '';


            if (!valid) {
                alert(errorMsg);
            }

            return valid;
        }
    </script>
</head>
<body>
    <div class="login">
        <div class="login-top">
            <!-- Hiển thị thông báo đăng ký thành công nếu có -->
            <?php
            if (isset($_GET['registration']) && $_GET['registration'] === 'success') {
                echo '<div id="registration-success" class="alert" role="alert">
                Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.
            </div>';
            }
            // Hiển thị thông báo lỗi đăng nhập nếu có
            if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials') {
                echo '<div id="login-error" class="alert" role="alert" style="color: red;">
                Tên đăng nhập hoặc mật khẩu không đúng.
            </div>';
            }
            // Lấy lại dữ liệu đã nhập nếu có
            $username = isset($_GET['username']) ? $_GET['username'] : '';
            ?>
            <h1>Login</h1>
            <div class="login-bottom">
                <form name="loginForm" action="check_login.php" method="POST" onsubmit="return validateForm()">
                    <input type="text" placeholder="User" name="username" value="<?php echo htmlspecialchars($username); ?>" required />
                    <input type="password" placeholder="Password" name="password" required />
                    <div class="remember">
                        <span class="checkbox1">
                            <label class="checkbox"><input type="checkbox" name="" checked=""><i> </i>Remember me</label>
                        </span>
                        <div class="forgot">
                            <h6><a href="#">Forgot Password?</a></h6>
                        </div>
                        <div class="clear"> </div>
                    </div>
                    <input type="submit" value="LOGIN" name="dangnhap" id="submit">
                </form>
                <div class="or">
                    <div class="sign-up">
                        <h2>or</h2>
                    </div>
                </div>
                <div class="register">
                    <p>Don't have an account  ?</p><a href="reister.php">Register now. </a> 
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
