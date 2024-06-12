async function checkExistence(type, value) {
    try {
        const response = await fetch('check_exited.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type: type, value: value })
        });
        const data = await response.json();
        return data.exists;
    } catch (error) {
        console.error('Error:', error);
        return false; // Giả định là không tồn tại trong trường hợp có lỗi để không chặn đăng ký
    }
}

async function validateForm(event) {
    event.preventDefault(); // Ngăn chặn việc gửi biểu mẫu ngay lập tức

    var fullname = document.getElementById("fullname").value;
    var username = document.getElementById("username").value;
    var phone = document.getElementById("phone").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var birthday = document.getElementById("birthday").value;
    var address = document.getElementById("address").value;
    var email = document.getElementById("email").value;

    var usernameError = document.getElementById("username-error");
    var phoneError = document.getElementById("phone-error");
    var passwordError = document.getElementById("password-error");
    var emailError = document.getElementById("emailname-error");

    // Reset thông báo lỗi
    usernameError.textContent = "";
    phoneError.textContent = "";
    passwordError.textContent = "";
    emailError.textContent = "";

    // Kiểm tra tên đăng nhập
    var usernameRegex = /^[a-zA-Z0-9]+$/;
    if (!usernameRegex.test(username)) {
        usernameError.textContent = "Tên đăng nhập không hợp lệ";
        return false;
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    if (await checkExistence('username', username)) {
        usernameError.textContent = "Tên đăng nhập đã tồn tại";
        return false;
    }

    // Kiểm tra số điện thoại
    var phoneRegex = /^0\d{9}$/;
    if (!phoneRegex.test(phone)) {
        phoneError.textContent = "Số điện thoại không hợp lệ";
        return false;
    }

    // Kiểm tra xem số điện thoại đã tồn tại chưa
    if (await checkExistence('phone', phone)) {
        phoneError.textContent = "Số điện thoại đã tồn tại";
        return false;
    }

    // Kiểm tra mật khẩu (8-24 ký tự, ít nhất một chữ cái và một số)
    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,24}$/;
    if (!passwordRegex.test(password)) {
        passwordError.textContent = "Mật khẩu phải có độ dài từ 8 đến 24 ký tự và bao gồm cả số và chữ cái.";
        return false;
    }

    // Kiểm tra khớp mật khẩu
    if (password !== confirmPassword) {
        passwordError.textContent = "Mật khẩu không khớp";
        return false;
    }

    // Kiểm tra email
    if (!email) {
        emailError.textContent = "Email không được để trống";
        return false;
    }

    // Kiểm tra xem email đã tồn tại chưa
    if (await checkExistence('email', email)) {
        emailError.textContent = "Email đã tồn tại";
        return false;
    }

    // Gửi biểu mẫu nếu không có lỗi
    document.getElementById("registration-form").submit();
}

document.getElementById('registration-form').onsubmit = validateForm;
