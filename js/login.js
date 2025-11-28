document.addEventListener("DOMContentLoaded", () => {

    // Lấy form đăng nhập theo ID
    const form = document.getElementById("loginForm");

    if (!form) return; // Nếu không tìm thấy form thì dừng lại

    form.addEventListener("submit", async (event) => {
        event.preventDefault(); // Không reload trang

        // 1. LẤY DỮ LIỆU TỪ FORM
        // Lưu ý: Kiểm tra kỹ id trong file HTML của bạn có đúng là 'emailOrPhone' và 'password' không
        const emailOrPhone = document.getElementById("emailOrPhone").value.trim();
        const password = document.getElementById("password").value.trim();
        
        // Kiểm tra xem có checkbox 'Ghi nhớ đăng nhập' không (nếu có)
        const rememberMe = document.getElementById("rememberMe")?.checked;

        // 2. KIỂM TRA HỢP LỆ (VALIDATION)
        if (!emailOrPhone || !password) {
            showAlert("Vui lòng nhập Email/SĐT và Mật khẩu.", "error");
            return;
        }

        // 3. URL API CHUẨN (Tuyệt đối để tránh lỗi 405)
        const apiURL = "http://localhost/frontend/api/khachhang/login.php";

        try {
            // 4. GỌI API
            const response = await fetch(apiURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    Email: emailOrPhone, // Backend PHP đang chờ key là "Email"
                    MatKhau: password    // Backend PHP đang chờ key là "MatKhau"
                })
            });

            // Nếu server trả về lỗi 404 hoặc 500
            if (!response.ok) {
                // Thử đọc lỗi chi tiết nếu có
                const errorData = await response.json().catch(() => ({})); 
                if (response.status === 405) {
                    showAlert("Lỗi 405: Sai đường dẫn API hoặc sai phương thức.", "error");
                } else {
                    showAlert(errorData.message || "Lỗi kết nối server.", "error");
                }
                return;
            }

            const data = await response.json();

            // 5. XỬ LÝ KẾT QUẢ
            if (data.status === true) {
                showAlert("Đăng nhập thành công! Đang chuyển hướng...", "success");

                // Lưu thông tin User vào localStorage để các trang khác sử dụng
                // Nếu chọn "Ghi nhớ" thì lưu lâu dài, không thì lưu theo phiên (Session)
                if (rememberMe) {
                    localStorage.setItem("currentUser", JSON.stringify(data.user));
                } else {
                    sessionStorage.setItem("currentUser", JSON.stringify(data.user));
                }

                // Điều hướng về trang chủ sau 1s
                setTimeout(() => {
                    window.location.href = "../html/index.html";
                }, 1000);

            } else {
                // Sai tài khoản hoặc mật khẩu
                showAlert(data.message || "Đăng nhập thất bại.", "error");
            }

        } catch (err) {
            console.error(err);
            showAlert("Không thể kết nối đến Server.", "error");
        }
    });

});


// ===================== HIỂN THỊ THÔNG BÁO (GIỐNG FILE ĐĂNG KÝ) =======================

function showAlert(message, type = "error") {
    // Tìm phần tử hiển thị lỗi (thường là class .auth-desc hoặc tạo 1 div id="alertBox")
    // Ở file đăng ký bạn dùng class .auth-desc, nên ở đây tôi giữ nguyên
    let box = document.querySelector(".auth-desc");

    // Nếu không tìm thấy class .auth-desc trong trang login, thử tìm id khác
    if (!box) {
        // Fallback: Tìm hoặc tạo 1 div tạm để hiện lỗi nếu HTML bên Login khác bên Register
        box = document.getElementById("alert-message"); 
    }

    if (!box) return; // Nếu vẫn không có chỗ hiển thị thì thôi

    box.style.display = "block"; // Đảm bảo nó hiện lên
    box.style.padding = "10px";
    box.style.borderRadius = "6px";
    box.style.marginBottom = "12px";
    box.style.textAlign = "center";
    box.style.fontWeight = "600";

    if (type === "error") {
        box.style.background = "#f8d7da";
        box.style.color = "#842029";
        box.style.border = "1px solid #f5c2c7";
    } else {
        box.style.background = "#d1e7dd";
        box.style.color = "#0f5132";
        box.style.border = "1px solid #badbcc";
    }

    box.innerText = message;
}