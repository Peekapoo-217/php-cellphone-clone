const viewMore = document.querySelectorAll(".js-viewmore__detail");
const modal = document.querySelector(".js-modal");
const modalClose = document.querySelector(".js-modal .js-modal-close");

function showViewMore() {
  modal.classList.add("open");
}

for (const vm of viewMore) {
  vm.addEventListener("click", showViewMore);
}

if (modalClose && modal) {
  modalClose.addEventListener("click", function () {
    modal.classList.remove("open");
  });
} else {
  console.error(
    "Không thể thêm sự kiện vào modalClose hoặc modal vì một hoặc cả hai không tồn tại."
  );
}

/// hàm lấy ảnh
function changeImage(element) {
  // Lấy đường dẫn ảnh từ thuộc tính 'data-src' của phần tử được click
  var imageUrl = element.getAttribute("data-src");

  // Tạo một thẻ img mới với đường dẫn ảnh
  var newImg = document.createElement("img");
  newImg.src = imageUrl;
  newImg.alt = "Image";

  // Lấy phần video container
  var videoContainer = document.getElementById("videoContainer");

  // Xóa nội dung hiện tại của video container
  videoContainer.innerHTML = "";

  // Thêm hình ảnh mới vào video container
  videoContainer.appendChild(newImg);
}
///// Hàm lấy video lên ô
function changeVideo(element) {
  // Lấy đường dẫn video từ thuộc tính 'data-src' của phần tử được click
  var videoSrc = element.getAttribute("data-src");

  // Lấy phần video container
  var videoContainer = document.getElementById("videoContainer");

  // Tạo một thẻ iframe mới với đường dẫn video
  var newVideo = document.createElement("iframe");
  newVideo.src = videoSrc;
  newVideo.width = "100%";
  newVideo.height = "500px";
  newVideo.frameBorder = "0";
  newVideo.allowFullscreen = true;
  newVideo.autoplay = true;
  newVideo.muted = true;

  // Xóa nội dung hiện tại của video container
  videoContainer.innerHTML = "";

  // Thêm video mới vào video container
  videoContainer.appendChild(newVideo);
}

////////////////

var selectedColor = "";
var selectedMemory = "";
var selectedImage = "";
var selectedPrice = "";
var selectedName = " ";

document.addEventListener("DOMContentLoaded", function () {
  let selectedColorElement = null;
  let selectedMemoryElement = null;

  window.selectColor = function (element) {
    if (selectedColorElement) {
      selectedColorElement.querySelector(".color__product").style.border =
        "1px solid #ccc";
    }
    selectedColorElement = element;
    selectedColorElement.querySelector(".color__product").style.border =
      "2px solid red";
    document.getElementById("selectedColor").value =
      selectedColorElement.dataset.color;
    document.getElementById("selectedImage").value =
      selectedColorElement.dataset.image;
    document.getElementById("selectedName").value =
      selectedColorElement.dataset.name;
  };
  window.selectMemory = function (element) {
    if (selectedMemoryElement) {
      selectedMemoryElement.style.border = "1px solid #ccc";
    }
    selectedMemoryElement = element;
    selectedMemoryElement.style.border = "2px solid red";
    document.getElementById("selectedMemory").value =
      selectedMemoryElement.dataset.memory;
    document.getElementById("selectedPrice").value =
      selectedMemoryElement.dataset.price;
  };
});

 

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("buy-now").addEventListener("click", function () {
    submitForm();
    alert("Đã thêm sản phẩm vào giỏ hàng!");
    });






  // Xử lý khi nhấn "Thêm vào giỏ" 
  document
    .getElementById("add-cart-now").addEventListener("click", function () {
      submitForm_addCart();
      alert("Đã thêm sản phẩm vào giỏ hàng!");
    if(confirm("Đã thêm vào giỏ hàng! Bạn có muốn thanh toán ngay không?")) {
      
      window.location.href = "index.php?cart";
    } else {
      // Do something if the user clicks Cancel
      // Ví dụ: window.location.href = "index.php"; để chuyển hướng đến trang chính.
    }

    });


function submitForm() {
  var selectedColor = document.getElementById("selectedColor").value;
  var selectedMemory = document.getElementById("selectedMemory").value;
  var selectedImage = document.getElementById("selectedImage").value;
  var selectedPrice = document.getElementById("selectedPrice").value;
  var selectedName = document.getElementById("selectedName").value;
  var selectedID = document.getElementById("selectedID").value;

  if (selectedColor && selectedMemory) {
    purchaseProduct(
      selectedID,
      selectedImage,
      selectedName,
      selectedColor,
      selectedMemory,
      selectedPrice
    );
  } else {
    alert("Vui lòng chọn màu sắc và phiên bản bộ nhớ.");
  }
}



/// hàm thêm sản phẩm vào sql
function purchaseProduct(productId, image, productName, color, size, price) {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "./purchase_product.php?product_id=" +
      productId +
      "&image=" +
      encodeURIComponent(image) +
      "&product_name=" +
      encodeURIComponent(productName) +
      "&color=" +
      encodeURIComponent(color) +
      "&size=" +
      encodeURIComponent(size) +  
      "&price=" +
      price,
    true
  );
  
  xhr.onload = function () {
    if (xhr.status === 200) {
      window.location.href = "index.php?cart";
    } else {
     // alert("Có lỗi xảy ra. Vui lòng thử lại.");
    }
  };
  xhr.send();

}

function purchaseProduct_addCart(productId, image, productName, color, memory, price) {
  window.location.href =
    "?product_id=" +
    productId +
    "&image=" +
    image +
    "&product_name=" +
    productName +
    "&color=" +
    color +
    "&memory=" +
    memory +
    "&price=" +
    price;
   // 
}

function submitForm_addCart() {
  var selectedColor = document.getElementById("selectedColor").value;
  var selectedMemory = document.getElementById("selectedMemory").value;
  var selectedImage = document.getElementById("selectedImage").value;
  var selectedPrice = document.getElementById("selectedPrice").value;
  var selectedName = document.getElementById("selectedName").value;
  var selectedID = document.getElementById("selectedID").value;
  if (selectedColor && selectedMemory) {
    purchaseProduct_addCart(
      selectedID,
      selectedImage,
      selectedName,
      selectedColor,
      selectedMemory,
      selectedPrice
    );
    
  } else {
    alert("Vui lòng chọn màu sắc và phiên bản bộ nhớ.");
  }
}