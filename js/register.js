document.getElementById("registerForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const fullName = document.getElementById("fullName").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const password = document.getElementById("password").value.trim();
    
    // Không cần lấy address nữa

    const data = {
        HoTen: fullName,
        Email: email,
        DienThoai: phone, 
        MatKhau: password
        // Không gửi DiaChi
    };

    try {
        const res = await fetch("http://localhost/frontend/api/index.php?controller=KhachHang&action=register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();
        
        if (result.status) {
            alert(result.message);
            window.location.href = "dangnhap.html"; 
        } else {
            alert(result.message); // Sẽ hiện: "Số điện thoại này đã được đăng ký!" nếu trùng
        }

    } catch (error) {
        console.error(error);
        alert("Lỗi kết nối server API");
    }
});