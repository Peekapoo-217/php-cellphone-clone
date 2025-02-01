<?php

// Thực hiện kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Thay đổi thành tên máy chủ MySQL của bạn
$username = "root"; // Thay đổi thành tên người dùng MySQL của bạn
$password = "123456"; // Thay đổi thành mật khẩu MySQL của bạn
$dbname = "CellPhone_K"; // Thay đổi thành tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname) or  die ("Không kết nối được với mySQL");


?>