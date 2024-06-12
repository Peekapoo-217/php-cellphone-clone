<?php include 'connect.php'; ?>

<div id="content-right">
    <div class="user-infor-content">
        <div class="user-avt-name">
            <div class="user-infor-avatar">
                <img src="img/CellPhoneK_Mascot.jpg" alt="User Avatar">
            </div>
        </div>
        <div class="form">
            <?php
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $stmt = $conn->prepare("SELECT * FROM KHACHHANG WHERE TenDangNhap = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    echo '<div class="field">';
                    echo' <label for="">Họ Tên:</label>';
                    echo '  <input id="name" type="text" disabled value="' . $row["HoTen"] . '" class="group-items js-input--fullname">';
                    echo '</div>';

                    echo '<div class="field">';
                    echo' <label for="">Email:</label>';
                    echo '  <input type="email" id="email" disabled value="' . $row["Email"] . '" class="group-items js-input--email">';
                    echo '</div>';

                    echo '<div class="field">';
                    echo' <label for="">Ngày Sinh:</label>';
                    echo '  <div class="group-items-img">  
                                <img src="img/edit-pen.png" onclick="editField(\'birthday\')">
                            </div>';
                    echo '  <input type="date" id="birthday" disabled value="' . $row["NgaySinh"] . '" class="group-items js-input--birthday">';
                    echo '</div>';

                    echo '<div class="field">';
                   echo' <label for="">Số Điện Thoại:</label>';
                    echo '  <div class="group-items-img">  
                                <img src="img/edit-pen.png" onclick="editField(\'phoneNumber\')">
                            </div>';
                    echo '  <input id="phoneNumber" type="text" disabled value="' . $row["SoDienThoai"] . '" class="group-items js-input--phoneNumber">';
                    echo '</div>';

                    echo '<div class="field">';
                    echo' <label for="">Địa chỉ:</label>';
                    echo '  <div class="group-items-img">  
                                <img src="img/edit-pen.png" onclick="editField(\'address\')">
                            </div>';
                    echo '  <input id="address" type="text" disabled value="' . $row["DiaChi"] . '" class="group-items js-input--address">';
                    echo '</div>';

                    echo '<div class="field">';
                    echo '  <div class="btn-update js-btn-update">Cập nhật thông tin</div>';
                    echo '</div>';
                } else {
                    echo '<p style="width: 100%; display: flex; justify-content: center; font-size:20px; padding-left: 30px;">Không tìm thấy thông tin khách hàng !!!</p>';
                }
            } else {
                echo '<p style="width: 100%; display: flex; justify-content: center; font-size:20px; padding-left: 30px;">Vui Lòng Đăng Nhập !!!</p>';
            }
            ?>
        </div>
    </div>
</div>
<script src="./js/editName.js"></script>
<script>
    <?php
        echo 'var username = "' . $_SESSION['username'] . '";';
    ?>

    function editField(fieldId) {
        document.getElementById(fieldId).disabled = false;
    }

    $(document).ready(function(){
        $('.js-btn-update').click(function(event){
            event.preventDefault();

            var birthday = $('.js-input--birthday').val();
            var phoneNumber = $('.js-input--phoneNumber').val();
            var address = $('.js-input--address').val();

            $.ajax({
                url: '../pages/update-customer.php',
                type: 'post',
                data: {
                    TenDangNhap: username,
                    NgaySinh: birthday,
                    SoDienThoai: phoneNumber,
                    DiaChi: address
                },
                success: function(response) {
                    alert(response); // Thông báo kết quả
                    location.reload(); // Tải lại trang sau khi cập nhật thành công
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi cập nhật thông tin.');
                }
            });
        });
    });


    
</script>
