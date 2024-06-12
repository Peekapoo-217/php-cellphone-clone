<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/reister.css">
</head>

<body>
    <div class="main-reister">
        <h1 class="reister-title">REGISTER</h1>
        <div class="reister-main">
            <form id="registration-form" action="create_user.php" method="POST" onsubmit="return validateForm(event)">
                <label class="text-reister" for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" placeholder="họ và tên" required>
 
                <label class="text-reister" for="username">Tên đăng nhập</label>
                <span id="username-error" style="color:red; font-size: 12px;"></span>
                <input type="text" id="username" name="username" placeholder="tên đăng nhập" required>

                <label class="text-reister" for="phone">Số điện thoại</label>
                <span id="phone-error" style="color:red; font-size: 12px;"></span>
                <input type="text" id="phone" name="phone" placeholder="số điện thoại" required>

                <label class="text-reister" for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="mật khẩu" required>

                <label class="text-reister" for="confirmPassword">Xác nhận mật khẩu</label>
                <span id="password-error" style="color:red; font-size: 12px;"></span>
                <input type="password" id="confirmPassword" placeholder="xác nhận mật khẩu" required>

                <label class="text-reister" for="birthday">Sinh nhật</label>
                <input type="date" id="birthday" name="birthday">

                <label class="text-reister" for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" placeholder="địa chỉ" required>

                <label class="text-reister" for="email">Email</label>
                <span id="emailname-error" style="color:red; font-size: 12px;"></span>
                <input type="email" id="email" name="email" placeholder="email" required>

                <input type="submit" value="REGISTER">
            </form>
            <script src="../js/create_user.js"></script>
        </div>
    </div>
</body>

</html>
