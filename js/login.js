// Xử lý đăng nhập
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const rememberMe = document.getElementById('rememberMe');

    // Xử lý submit form
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Lấy dữ liệu form
            const emailOrPhone = document.getElementById('emailOrPhone').value.trim();
            const password = document.getElementById('password').value;

            // Validation
            if (!emailOrPhone) {
                authManager.showMessage('Vui lòng nhập email hoặc số điện thoại', 'error');
                return;
            }

            if (!password) {
                authManager.showMessage('Vui lòng nhập mật khẩu', 'error');
                return;
            }

            // Gửi request đăng nhập
            try {
                const response = await fetch('../api/khachhang/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        Email: emailOrPhone,
                        MatKhau: password
                    })
                });

                const result = await response.json();

                if (result.status === true && result.user) {
                    // Lưu thông tin user
                    authManager.setUser(result.user);
                    
                    // Lưu remember me nếu được chọn
                    if (rememberMe && rememberMe.checked) {
                        localStorage.setItem('rememberMe', 'true');
                    }

                    authManager.showMessage('Đăng nhập thành công! Đang chuyển hướng...', 'success');
                    
                    setTimeout(() => {
                        // Chuyển về trang chủ hoặc trang trước đó
                        const returnUrl = sessionStorage.getItem('returnUrl') || '../html/index.html';
                        sessionStorage.removeItem('returnUrl');
                        window.location.href = returnUrl;
                    }, 1000);
                } else {
                    authManager.showMessage(result.message || 'Email hoặc mật khẩu không đúng', 'error');
                }
            } catch (error) {
                authManager.showMessage('Lỗi kết nối. Vui lòng thử lại sau.', 'error');
                console.error('Login error:', error);
            }
        });
    }

    // Kiểm tra nếu đã đăng nhập thì chuyển về trang chủ
    if (authManager.isLoggedIn()) {
        window.location.href = '../html/index.html';
    }
});

