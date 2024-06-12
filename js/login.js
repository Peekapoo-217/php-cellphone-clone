async function validateForm(event) {
    event.preventDefault();

    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    var usernameError = document.getElementById("username-error");
    var passwordError = document.getElementById("password-error");

    usernameError.textContent = "";
    passwordError.textContent = "";

    if (!(await checkExistence('username', username))) {
        usernameError.textContent = "Tên đăng nhập không tồn tại";
        return false;
    }
    // Xác thực mật khẩu an toàn sẽ được xử lý bên server
    var passwordExists = await checkExistence('password', password);
    if (!passwordExists) {
        passwordError.textContent = "Mật khẩu không chính xác";
        return false;
    }
}

async function checkExistence(type, value) {
    try {
        const response = await fetch('../pages/check_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type: type, value: value })
        });
        const data = await response.json();
        return data.exists;
    } catch (error) {
        console.error('Lỗi:', error);
        return false;
    }
}

document.getElementById("login-form").addEventListener("submit", validateForm);
