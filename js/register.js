document.getElementById("registerForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const fullName = document.getElementById("fullName").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const password = document.getElementById("password").value.trim();

    // Dữ liệu gửi đi (Lưu ý: key là DienThoai hay SoDienThoai phụ thuộc vào DB của bạn đã sửa ở bước trước)
    const data = {
        HoTen: fullName,
        Email: email,
        DienThoai: phone, 
        MatKhau: password
    };

    try {
        const res = await fetch("http://localhost/frontend/api/khachhang/register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();
        console.log("Server response:", result);

        if (result.status) {
            // Thông báo ngắn gọn
            alert("Đăng ký thành công! Đang chuyển hướng...");
            
            // --- ĐOẠN CODE CHUYỂN TRANG ---
            // Bạn hãy thay đổi đường dẫn bên dưới cho đúng với file đăng nhập của bạn
            // Ví dụ 1: Nếu dùng Controller như file bạn gửi lúc đầu:
            window.location.href = "dangnhap.html?action=login"; 
            
            // Ví dụ 2: Nếu file đăng nhập nằm ngay cùng thư mục:
            // window.location.href = "login.php";
            // -------------------------------
            
        } else {
            alert(result.message || "Đăng ký thất bại");
        }

    } catch (error) {
        console.error(error);
        alert("Không thể kết nối server");
    }
});