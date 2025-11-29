document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");

    if (loginForm) {
        loginForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            const emailOrPhone = document.getElementById("emailOrPhone").value.trim();
            const password = document.getElementById("password").value.trim();

            if (!emailOrPhone || !password) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            const data = {
                Email: emailOrPhone,
                MatKhau: password
            };

            try {
    // SỬA ĐƯỜNG DẪN TẠI ĐÂY:
    const res = await fetch("http://localhost/frontend/api/index.php?controller=KhachHang&action=login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    });

    const result = await res.json();
    // ... phần xử lý kết quả giữ nguyên ...

                if (result.status) {
                    alert("Đăng nhập thành công!");
                    localStorage.setItem("user", JSON.stringify(result.data));
                    window.location.href = "../html/index.html"; 
                } else {
                    alert(result.message || "Đăng nhập thất bại");
                }

            } catch (error) {
                console.error("Lỗi:", error);
                alert("Không thể kết nối đến server API.");
            }
        });
    }
    
    // ... Phần ẩn hiện mật khẩu giữ nguyên ...
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            // Giả sử icon là thẻ <i>
            const icon = this.querySelector('i');
            if(icon) {
                 icon.classList.toggle('fa-eye');
                 icon.classList.toggle('fa-eye-slash');
            }
        });
    }
});