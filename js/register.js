// Xử lý đăng ký
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const phoneInput = document.getElementById('phone');

    // Validation số điện thoại Việt Nam
    function validatePhone(phone) {
        const phoneRegex = /^(0|\+84)[3|5|7|8|9][0-9]{8}$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }

    // Validation email
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Validation mật khẩu (ít nhất 6 ký tự)
    function validatePassword(password) {
        return password.length >= 6;
    }

    // Format số điện thoại
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('84')) {
            value = '0' + value.substring(2);
        }
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        e.target.value = value;
    });

    // Xử lý submit form
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Lấy dữ liệu form
            const formData = {
                HoTen: document.getElementById('fullName').value.trim(),
                DienThoai: document.getElementById('phone').value.trim(),
                Email: document.getElementById('email').value.trim(),
                MatKhau: document.getElementById('password').value,
                XacNhanMatKhau: document.getElementById('confirmPassword').value
            };

            // Validation
            if (!formData.HoTen) {
                authManager.showMessage('Vui lòng nhập họ và tên', 'error');
                return;
            }

            if (!formData.DienThoai) {
                authManager.showMessage('Vui lòng nhập số điện thoại', 'error');
                return;
            }

            if (!validatePhone(formData.DienThoai)) {
                authManager.showMessage('Số điện thoại không hợp lệ', 'error');
                return;
            }

            if (!formData.Email) {
                authManager.showMessage('Vui lòng nhập email', 'error');
                return;
            }

            if (!validateEmail(formData.Email)) {
                authManager.showMessage('Email không hợp lệ', 'error');
                return;
            }

            if (!formData.MatKhau) {
                authManager.showMessage('Vui lòng nhập mật khẩu', 'error');
                return;
            }

            if (!validatePassword(formData.MatKhau)) {
                authManager.showMessage('Mật khẩu phải có ít nhất 6 ký tự', 'error');
                return;
            }

            if (formData.MatKhau !== formData.XacNhanMatKhau) {
                authManager.showMessage('Mật khẩu xác nhận không khớp', 'error');
                return;
            }

            // Gửi request đăng ký
            try {
                const response = await fetch('../api/khachhang/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        HoTen: formData.HoTen,
                        Email: formData.Email,
                        MatKhau: formData.MatKhau,
                        DienThoai: formData.DienThoai
                    })
                });

                const result = await response.json();

                if (result.status === true) {
                    authManager.showMessage('Đăng ký thành công! Đang chuyển đến trang đăng nhập...', 'success');
                    setTimeout(() => {
                        window.location.href = '../html/dangnhap.html';
                    }, 2000);
                } else {
                    authManager.showMessage(result.message || 'Đăng ký thất bại', 'error');
                }
            } catch (error) {
                authManager.showMessage('Lỗi kết nối. Vui lòng thử lại sau.', 'error');
                console.error('Register error:', error);
            }
        });
    }
});

