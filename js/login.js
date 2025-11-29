document.addEventListener("DOMContentLoaded", function() {
    // 1. Xử lý Đăng nhập
    const loginForm = document.getElementById("loginForm");

    if (loginForm) {
        loginForm.addEventListener("submit", async function(e) {
            e.preventDefault(); // Ngăn chặn trang web tự load lại

            // Lấy đúng ID từ file HTML bạn vừa gửi
            const emailOrPhone = document.getElementById("emailOrPhone").value.trim();
            const password = document.getElementById("password").value.trim();

            if (!emailOrPhone || !password) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            // Chuẩn bị dữ liệu gửi đi
            // Lưu ý: Backend PHP đang chờ key là "Email", ta gán giá trị nhập vào đó
            const data = {
                Email: emailOrPhone,
                MatKhau: password
            };

            try {
                // Thay đổi đường dẫn này nếu cần thiết
                const res = await fetch("http://localhost/frontend/api/khachhang/login.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                console.log("Server trả về:", result);

                if (result.status) {
                    alert("Đăng nhập thành công!");

                    // Lưu thông tin user vào localStorage
                    localStorage.setItem("user", JSON.stringify(result.data));

                    // Chuyển hướng về trang chủ
                    window.location.href = "../html/index.html"; 
                } else {
                    alert(result.message || "Tên đăng nhập hoặc mật khẩu không đúng");
                }

            } catch (error) {
                console.error("Lỗi:", error);
                alert("Không thể kết nối đến server. Vui lòng kiểm tra lại API.");
            }
        });
    }

    // 2. Xử lý Ẩn/Hiện mật khẩu (Bắt sự kiện vào icon con mắt)
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function() {
            // Kiểm tra trạng thái hiện tại
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            
            // Đổi icon (Mở mắt <-> Nhắm mắt)
            // Giả sử bạn dùng FontAwesome, ta toggle class
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
});