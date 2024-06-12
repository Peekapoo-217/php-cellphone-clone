<!-- main-content -->
<div class="main-content back-chung">
    <!-- main-title -->
    <?php
    $hang = isset($_GET['hang']) ? $_GET['hang'] : 'all';
    if ($hang == "all") {
        $hang = "Tất Cả Sản Phẩm";
    }
    echo '<div class="main-title"> ' . $hang;
    ?>

    <div class="sort back-chung">
        <p id="sortTitle">Sắp xếp theo: <span id="currentSort">Giá tăng dần</span> <i class="fa-solid fa-caret-down"></i></p>
        <ul id="sortOptions">
            <li><a href="#" onclick="changeSort('gia-tang-dan')">Giá tăng dần <i style="margin-left: 4px;" class="fa-solid fa-check"></i></a></li>
            <li><a href="#" onclick="changeSort('gia-giam-dan')">Giá giảm dần <i style="margin-left: 4px;" class="fa-solid fa-check" style="display:none;"></i></a></li>
        </ul>
    </div>
</div>
<!-- end main-title -->
<!-- main-product -->
<div class="main-product">
    <?php
    include 'filter-products.php';
    ?>
</div>
<!-- end main-product -->
<!-- view-more -->
<div class="view-more-product">
    <a href="#" onclick="loadMoreProducts()">Xem thêm sản phẩm <i class="fa-solid fa-caret-down"></i></a>
</div>

<script>
function loadMoreProducts() {
    // Lưu vị trí cuộn hiện tại vào sessionStorage
    sessionStorage.setItem('scrollPosition', window.pageYOffset);

    // Tiến hành tải lại trang
    var currentLimit = parseInt(<?php echo $limit; ?>);
    var newLimit = currentLimit + 15;
    var search = '<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>';
    var hang = '<?php echo isset($_GET['hang']) ? $_GET['hang'] : 'all'; ?>';
    var price = '<?php echo isset($_GET['price']) ? $_GET['price'] : 'all'; ?>';
    var sort = '<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'gia-giam-dan'; ?>';

    // Redirect to the same page with updated limit and existing filters
    var url = new URL(window.location.href);
    url.searchParams.set('limit', newLimit);
    if (search) url.searchParams.set('search', search);
    if (hang) url.searchParams.set('hang', hang);
    if (price) url.searchParams.set('price', price);
    if (sort) url.searchParams.set('sort', sort);

    window.location.href = url.toString();
}

// Gán hàm này cho sự kiện window.onload để đặt lại vị trí cuộn sau khi trang đã được tải lại
window.onload = function() {
    // Lấy vị trí cuộn từ sessionStorage
    var scrollPosition = sessionStorage.getItem('scrollPosition');

    // Nếu có vị trí cuộn, đặt lại vị trí đó
    if (scrollPosition !== null) {
        // Cuộn đến vị trí cuộn đã lưu, nhưng sau khi trang đã được hiển thị
        setTimeout(function() {
            window.scrollTo(0, parseInt(scrollPosition));
        }, 0); // Tăng thời gian delay lên 500 milliseconds

        // Xóa vị trí cuộn từ sessionStorage để tránh sử dụng nó lại ở lần tiếp theo
        sessionStorage.removeItem('scrollPosition');
    }
};

document.getElementById('sortTitle').addEventListener('click', function() {
    document.getElementById('sortOptions').classList.toggle('show');
});

function changeSort(sortType) {
    var currentParams = new URLSearchParams(window.location.search);
    currentParams.set('sort', sortType);

    var newUrl = window.location.pathname + '?' + currentParams.toString();
    window.location.href = newUrl;
}

document.addEventListener('DOMContentLoaded', function() {
    var sortParam = '<?php echo isset($_GET['sort']) ? $_GET['sort'] : 'gia-tang-dan'; ?>';
    var sortText = sortParam == 'gia-giam-dan' ? 'Giá giảm dần' : 'Giá tăng dần'; // Default sort text

    document.getElementById('currentSort').innerText = sortText;

    // Update the sort options to show the correct checkmark
    var sortOptions = document.querySelectorAll('#sortOptions li a');
    sortOptions.forEach(function(option) {
        var icon = option.querySelector('i.fa-check');
        if (option.getAttribute('onclick').includes(sortParam)) {
            icon.style.display = 'inline';
        } else {
            icon.style.display = 'none';
        }
    });
});
</script>
