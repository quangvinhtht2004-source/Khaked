document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    if (!form) return;

    // Toggle password visibility cho mật khẩu
    const togglePassword = document.getElementById("togglePasswordRegister");
    const passwordInput = document.getElementById("password");
    
    if (togglePassword && passwordInput) {
        togglePassword.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const currentType = passwordInput.type || "password";
            const newType = currentType === "password" ? "text" : "password";
            passwordInput.type = newType;
            
            const icon = togglePassword.querySelector("i");
            if (icon) {
                if (newType === "password") {
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                } else {
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                }
            }
            return false;
        };
        togglePassword.style.cursor = "pointer";
        togglePassword.style.pointerEvents = "auto";
    }

    // Toggle password visibility cho xác nhận mật khẩu
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const confirmPasswordInput = document.getElementById("confirmPassword");
    
    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const currentType = confirmPasswordInput.type || "password";
            const newType = currentType === "password" ? "text" : "password";
            confirmPasswordInput.type = newType;
            
            const icon = toggleConfirmPassword.querySelector("i");
            if (icon) {
                if (newType === "password") {
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                } else {
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                }
            }
            return false;
        };
        toggleConfirmPassword.style.cursor = "pointer";
        toggleConfirmPassword.style.pointerEvents = "auto";
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        // Lấy dữ liệu từ form
        const fullName = document.getElementById("fullName").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirmPassword").value.trim();

        // Validation
        if (!fullName || !email || !phone || !password || !confirmPassword) {
            showAlert("Vui lòng nhập đầy đủ thông tin.", "error");
            return;
        }

        // Validate tên
        if (fullName.length < 2) {
            showAlert("Họ và tên phải có ít nhất 2 ký tự.", "error");
            return;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showAlert("Email không hợp lệ. Vui lòng nhập đúng định dạng email.", "error");
            return;
        }

        // Validate số điện thoại (10-11 số)
        const phoneRegex = /^[0-9]{10,11}$/;
        if (!phoneRegex.test(phone)) {
            showAlert("Số điện thoại không hợp lệ. Vui lòng nhập 10-11 chữ số.", "error");
            return;
        }

        // Validate mật khẩu
        if (password.length < 6) {
            showAlert("Mật khẩu phải có ít nhất 6 ký tự.", "error");
            return;
        }

        // Kiểm tra mật khẩu xác nhận
        if (password !== confirmPassword) {
            showAlert("Mật khẩu xác nhận không khớp!", "error");
            return;
        }

        // Disable button khi đang xử lý
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = "Đang xử lý...";

        // URL API - tự động detect môi trường
        const apiURL = window.AppConfig 
            ? window.AppConfig.getAPIURL('khachhang/register.php')
            : "../api/khachhang/register.php";

        try {
            const response = await fetch(apiURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    HoTen: fullName,
                    Email: email,
                    MatKhau: password,
                    DienThoai: phone,
                    DiaChi: ""
                })
            });

            const data = await response.json();

            if (data.status === true) {
                showAlert("Đăng ký thành công! Đang chuyển đến trang đăng nhập...", "success");
                setTimeout(() => {
                    window.location.href = "../html/dangnhap.html";
                }, 1500);
            } else {
                showAlert(data.message || "Đăng ký thất bại. Vui lòng thử lại.", "error");
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }

        } catch (err) {
            console.error("Lỗi:", err);
            showAlert("Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.", "error");
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});

// ===================== HIỂN THỊ THÔNG BÁO =======================
function showAlert(message, type = "error") {
    // Tìm hoặc tạo element thông báo
    let alertBox = document.getElementById("alert-message");
    
    if (!alertBox) {
        alertBox = document.createElement("div");
        alertBox.id = "alert-message";
        const form = document.querySelector(".auth-box form");
        if (form) {
            form.insertBefore(alertBox, form.firstChild);
        } else {
            const authBox = document.querySelector(".auth-box");
            if (authBox) {
                authBox.insertBefore(alertBox, authBox.firstChild);
            }
        }
    }

    // Reset styles
    alertBox.className = `auth-message auth-message-${type}`;
    alertBox.textContent = message;
    alertBox.style.display = "block";

    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        alertBox.style.display = "none";
    }, 5000);
}
