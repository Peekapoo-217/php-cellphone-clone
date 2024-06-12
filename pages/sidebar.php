<!-- sidebar -->
<div class="sidebar back-chung">
    <div class="main-title">BỘ LỌC</div>
    <form id="filterForm" method="GET" action="."> <!-- Cập nhật đường dẫn nếu cần -->
        <div class="sidebar-group">
            <div class="sidebar-title">Hãng điện thoại</div>
            <div class="sidebar-item">
                <ul>
                    <li><input type="radio" name="hang" value="all" onchange="submitFilterForm()">Tất Cả Hãng</li>
                    <?php
                    $sql = "SELECT hang from sanpham GROUP BY  hang";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo'<li><input type="radio" name="hang" value="'.$row['hang'].'" onchange="submitFilterForm()">'.$row['hang'].'</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="sidebar-group">
            <div class="sidebar-title">Lọc theo giá</div>
            <div class="sidebar-item">
                <ul>
                    <li><input type="radio" name="price" value="all" onchange="submitFilterForm()">Tất cả giá</li>
                    <li><input type="radio" name="price" value="from_3m_to_5m" onchange="submitFilterForm()">Từ 3 triệu đến 5 triệu</li>
                    <li><input type="radio" name="price" value="from_5m_to_7m" onchange="submitFilterForm()">Từ 5 triệu đến 7 triệu</li>
                    <li><input type="radio" name="price" value="from_7m_to_10m" onchange="submitFilterForm()">Từ 7 triệu đến 10 triệu</li>
                    <li><input type="radio" name="price" value="above_10m" onchange="submitFilterForm()">Trên 10 triệu</li>
                </ul>
            </div>
        </div>
    </form>
</div>
<!-- end sidebar -->

<script>
function submitFilterForm() {
    var form = document.getElementById('filterForm');
    var formData = new FormData(form);
    var params = new URLSearchParams(formData);
    var currentSort = new URLSearchParams(window.location.search).get('sort');
    if (currentSort) {
        params.set('sort', currentSort);
    }
    window.location.href = '?' + params.toString();
}

// Lưu trữ giá trị của nút radio vào localStorage khi người dùng chọn
document.addEventListener('DOMContentLoaded', function() {
    var radioButtons = document.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
            localStorage.setItem(this.name, this.value);
        });
    });
    
    // Khôi phục giá trị của nút radio từ localStorage khi trang web load lại
    radioButtons.forEach(function(radioButton) {
        var storedValue = localStorage.getItem(radioButton.name);
        if (storedValue === radioButton.value) {
            radioButton.checked = true;
        }
    });
});
</script>
