document.addEventListener('click', function(event) {
    if (event.target.classList.contains('delete-button')) {
        event.preventDefault();
        var productId = event.target.dataset.productId;
        var color = event.target.dataset.color;
        var memory = event.target.dataset.memory;
        deleteProduct(productId, color, memory);
    }
});

function deleteProduct(productId, color, memory) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../pages/delete_product.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.reload(); // Xóa sản phẩm xong, làm mới trang
            } else {
                console.error('Lỗi khi xóa sản phẩm');
            }
        };
        var data = 'product_id=' + productId + '&color=' + color + '&memory=' + memory;
        xhr.send(data);
    }
}
function checkout() {
    var selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    if (selectedProducts.length === 0) {
        alert("Vui lòng chọn ít nhất một sản phẩm để đặt hàng.");
    } else {
        var totalPrice = calculateTotalPrice(selectedProducts);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'pages/create_hoadon.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            console.log('Response Text:', xhr.responseText);
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log('Parsed Response:', response);
                    if (response.success) {
                        alert('Đặt hàng thành công!');
                        window.location.reload(); // Đặt hàng thành công, làm mới trang
                    } else {
                        alert('Đã xảy ra lỗi khi tạo hóa đơn: ' + response.message);
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    console.error('Response received:', xhr.responseText);
                    alert('Có lỗi xảy ra');
                }
            } else {
                alert('Đã xảy ra lỗi khi đặt hàng. Mã lỗi: ' + xhr.status);
            }
        };
        var data = 'totalPrice=' + totalPrice + '&productsData=' + JSON.stringify(getProductsData(selectedProducts));
        xhr.send(data);
    }
}

function calculateTotalPrice(selectedProducts) {
    var totalPrice = 0;
    selectedProducts.forEach(function(product) {
        var price = parseFloat(product.dataset.price);
        var quantity = parseInt(document.querySelector('.quantity-input[data-product-id="' + product.dataset.productId + '"]').value);
        totalPrice += price * quantity;
    });
    return totalPrice;
}

function getProductsData(selectedProducts) {
    var productsData = [];
    selectedProducts.forEach(function(product) {
        var productId = product.dataset.productId;
        var quantity = parseInt(document.querySelector('.quantity-input[data-product-id="' + productId + '"]').value);
        productsData.push({
            productId: productId,
            quantity: quantity
        });
    });
    return productsData;
}

function goHome() {
    window.location.href = './index.php';
}

function updateTotalPrice() {
    var totalPrice = 0;
    var products = document.querySelectorAll('.product-checkbox:checked');
    products.forEach(function(product) {
        var price = parseFloat(product.dataset.price);
        var quantity = parseInt(document.querySelector('.quantity-input[data-product-id="' + product.dataset.productId + '"]').value);
        totalPrice += price * quantity;
    });
    document.getElementById('total-price').textContent = totalPrice.toFixed(2);
}

document.addEventListener('change', function(event) {
    if (event.target.classList.contains('product-checkbox') || event.target.classList.contains('quantity-input')) {
        updateTotalPrice();
    }
});

window.addEventListener('load', function() {
    updateTotalPrice();
});
