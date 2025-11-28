document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    if (!form) return;

    // Toggle password visibility
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    
    if (togglePassword && passwordInput) {
        // Sử dụng onclick trực tiếp để đảm bảo hoạt động
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
        
        // Thêm style để đảm bảo có thể click
        togglePassword.style.cursor = "pointer";
        togglePassword.style.pointerEvents = "auto";
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        // Lấy dữ liệu từ form
        const emailOrPhone = document.getElementById("emailOrPhone").value.trim();
        const password = document.getElementById("password").value.trim();
        const rememberMe = document.getElementById("rememberMe")?.checked || false;

        // Validation
        if (!emailOrPhone || !password) {
            showAlert("Vui lòng nhập đầy đủ thông tin.", "error");
            return;
        }

        // Validate định dạng (email hoặc số điện thoại)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9]{10,11}$/;
        
        if (!emailRegex.test(emailOrPhone) && !phoneRegex.test(emailOrPhone)) {
            showAlert("Vui lòng nhập đúng định dạng email hoặc số điện thoại.", "error");
            return;
        }

        // Disable button khi đang xử lý
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = "Đang đăng nhập...";

        // URL API - tự động detect môi trường
        const apiURL = window.AppConfig 
            ? window.AppConfig.getAPIURL('khachhang/login.php')
            : "../api/khachhang/login.php";

        try {
            const response = await fetch(apiURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    Email: emailOrPhone,
                    MatKhau: password
                })
            });

            const data = await response.json();

            if (data.status === true) {
                showAlert("Đăng nhập thành công! Đang chuyển hướng...", "success");

                // Lưu thông tin user - sử dụng authManager nếu có
                const userData = {
                    MaKH: data.user.MaKH,
                    HoTen: data.user.HoTen,
                    Email: data.user.Email,
                    DienThoai: data.user.DienThoai,
                    DiaChi: data.user.DiaChi || ""
                };

                if (rememberMe) {
                    localStorage.setItem("vkdbookstore_user", JSON.stringify(userData));
                    localStorage.setItem("currentUser", JSON.stringify(userData));
                } else {
                    sessionStorage.setItem("vkdbookstore_user", JSON.stringify(userData));
                    sessionStorage.setItem("currentUser", JSON.stringify(userData));
                }

                // Trigger custom event để header có thể cập nhật
                window.dispatchEvent(new CustomEvent('userLoggedIn', { detail: userData }));

                // Điều hướng về trang chủ
                setTimeout(() => {
                    window.location.href = "../html/index.html";
                }, 1000);

            } else {
                showAlert(data.message || "Email/SĐT hoặc mật khẩu không chính xác.", "error");
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