<!DOCTYPE html>
<html>

<head>
    <title>Danh sách khách hàng</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
    <script>
        function editCustomer(id) {
            const row = document.getElementById('row-' + id);
            const cells = row.getElementsByTagName('td');

            // Tạo input chỉ cho các trường địa chỉ, số điện thoại và ngày sinh
            cells[4].innerHTML = `<input type='text' value='${cells[4].innerText}' />`; // Địa chỉ
            cells[3].innerHTML = `<input type='text' value='${cells[3].innerText}' />`; // Số điện thoại
            cells[6].innerHTML = `<input type='date' value='${cells[6].innerText}' />`; // Ngày sinh

            // Hiển thị nút lưu
            cells[9].innerHTML = `<button onclick='saveCustomer(${id})'>Lưu</button>`;
        }

        function saveCustomer(id) {
            const row = document.getElementById('row-' + id);
            const cells = row.getElementsByTagName('td');

            // Tạo một đối tượng data để chứa thông tin khách hàng cần cập nhật
            let data = {
                MaKhachHang: id,
                DiaChi: cells[4].getElementsByTagName('input')[0].value,
                SoDienThoai: cells[3].getElementsByTagName('input')[0].value,
                NgaySinh: cells[6].getElementsByTagName('input')[0].value
            };

            // Gửi yêu cầu AJAX để cập nhật thông tin khách hàng
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_customer.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    location.reload(); // Tải lại trang sau khi cập nhật thành công
                }
            };
            xhr.send(JSON.stringify(data));
        }

        function deleteCustomer(id) {
            if (confirm("Bạn có chắc chắn muốn xóa khách hàng này?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_customer.php", true);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload(); // Tải lại trang sau khi xóa thành công
                    }
                };
                xhr.send(JSON.stringify({
                    MaKhachHang: id
                }));
            }
        }
    </script>
</head>

<body>
    <h2>Danh sách khách hàng</h2>
    <table>
        <tr>
            <th>Mã khách hàng</th>
            <th>Tên đăng nhập</th>
            <th>Họ tên</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Email</th>
            <th>Ngày sinh</th>
            <th>Tổng tiền thanh toán</th>
            <th>Hạng thành viên</th>
            <th>Chức Năng</th>
        </tr>
        <?php
        // Kết nối đến cơ sở dữ liệu
        include '../connect.php';

        // Lấy danh sách khách hàng từ cơ sở dữ liệu
        $sql = "SELECT * FROM KhachHang";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr id='row-" . $row['MaKhachHang'] . "'>";
                echo "<td>" . $row['MaKhachHang'] . "</td>";
                echo "<td>" . $row['TenDangNhap'] . "</td>";
                echo "<td>" . $row['HoTen'] . "</td>";
                echo "<td>" . $row['SoDienThoai'] . "</td>";
                echo "<td>" . $row['DiaChi'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['NgaySinh'] . "</td>";
                echo "<td>" . $row['TongTienThanhToan'] . "</td>";
                echo "<td>" . $row['HangThanhVien'] . "</td>";
                echo "<td>
                        <button onclick='editCustomer(" . $row['MaKhachHang'] . ")'>Sửa</button>
                        <button onclick='deleteCustomer(" . $row['MaKhachHang'] . ")'>Xóa</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>Không có khách hàng nào</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>

</html>
