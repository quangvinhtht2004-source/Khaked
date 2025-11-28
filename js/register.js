document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("registerForm");

    form.addEventListener("submit", async (event) => {
        event.preventDefault(); // Không reload trang

        // Lấy dữ liệu từ form
        const fullName = document.getElementById("fullName").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirmPassword").value.trim();

        // KIỂM TRA HỢP LỆ
        if (!fullName || !email || !phone || !password || !confirmPassword) {
            showAlert("Vui lòng nhập đầy đủ thông tin.", "error");
            return;
        }

        if (password !== confirmPassword) {
            showAlert("Mật khẩu xác nhận không khớp!", "error");
            return;
        }

        // URL API CHUẨN NHẤT
        const apiURL = "http://localhost/frontend/api/khachhang/register.php";

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
                showAlert("Đăng ký thành công!", "success");

                // Điều hướng sau 1s
                setTimeout(() => {
                    window.location.href = "../html/dangnhap.html";
                }, 1000);

            } else {
                showAlert(data.message || "Đăng ký thất bại.", "error");
            }

        } catch (err) {
            console.error(err);
            showAlert("Không kết nối được server.", "error");
        }

    });

});


// ===================== HIỂN THỊ THÔNG BÁO =======================

function showAlert(message, type = "error") {
    let box = document.querySelector(".auth-desc");

    if (!box) return;

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
