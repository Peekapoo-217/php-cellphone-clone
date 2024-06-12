document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function(event) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        if (!username || !password) {
            document.getElementById('errorMessage').innerText = 'Tên Đăng Nhập và Mật Khẩu là bắt buộc';
            event.preventDefault();
        }
    });
});
