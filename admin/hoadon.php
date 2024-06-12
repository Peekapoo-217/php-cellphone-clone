<head>
    <script>
        function editHoaDon(id){
            const row = document.getElementById('row-' + id);
            const cells = row.getElementsByTagName('td');

            cells[4].innerHTML = `<input type='text' value='${cells[4].innerText}' />`; // trạng thái
             // Hiển thị nút lưu
            cells[5].innerHTML = `<button onclick='saveHoaDon(${id})'>Lưu</button>`;
        }

        function saveHoaDon(id){
            const row = document.getElementById('row-'+ id);
            const cells = row.getElementsByTagName('td');

            let data ={
                MaHoaDon : id,
                TrangThai : cells[4].getElementsByTagName('input')[0].value
            };

            const xhr = new XMLHttpRequest();
            xhr.open("POST","update_hoadon.php",true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    location.reload(); // Tải lại trang sau khi cập nhật thành công
                }
            };
            xhr.send(JSON.stringify(data));
        }

        function deleteHoaDon(id){
            if(confirm("Bạn có chăc chắn muốn xóa hoa đơn này không ?")){
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_hoadon.php", true);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload(); // Tải lại trang sau khi xóa thành công
                    }
                };
                xhr.send(JSON.stringify({
                    MaHoaDon: id
                }));
            }
        }

        function subMitHoaDon(id){
            const row = document.getElementById('row-'+ id);
            const cells = row.getElementsByTagName('td');

            let data ={
                MaHoaDon : id,
                TrangThai : "Đã giao hàng"
            };

            const xhr = new XMLHttpRequest();
            xhr.open("POST","update_hoadon.php",true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    location.reload(); // Tải lại trang sau khi cập nhật thành công
                }
            };
            xhr.send(JSON.stringify(data));
        }
    </script>
</head>

<body>

    <h2>Danh Sách Hóa Đơn</h2>
    <table>
        <tr>
            <th>Mã Hóa Đơn</th>
            <th>Tên Đăng Nhập</th>
            <th>Ngày Lập</th>
            <th>Tổng Tiền</th>
            <th>Trạng Thái</th>
            <th>Chức Năng</th>

            <?php
            include '../connect.php';

            $sql = "SELECT * FROM HoaDon";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='row-" . $row['MaHoaDon'] . "'>";
                    echo "<td>" . $row['MaHoaDon'] . "</td>";
                    echo "<td>" . $row['TenDangNhap'] . "</td>";
                    echo "<td>" . $row['NgayLap'] . "</td>";
                    echo "<td>" . $row['TongTien'] . "</td>";
                    echo "<td>" . $row['TrangThai'] . "</td>";
                    echo "<td>
                        <button onclick='editHoaDon(" . $row['MaHoaDon'] . ")'>Sửa</button>
                        <button onclick='deleteHoaDon(" . $row['MaHoaDon'] . ")'>Xóa</button>
                        <button onclick='subMitHoaDon(" . $row['MaHoaDon'] . ")'>Hoàn Thành</button>
                         </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>Không có hóa đơn nào</td></tr>";
            }
            ?>
        </tr>
    </table>
</body>