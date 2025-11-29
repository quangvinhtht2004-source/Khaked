document.addEventListener("DOMContentLoaded", function() {
    // 1. Lấy thông tin user từ localStorage (đã lưu lúc đăng nhập)
    const userJson = localStorage.getItem("user");
    
    // 2. Lấy các phần tử trên DOM
    const authButtons = document.getElementById("authButtonsContainer"); // Nút Đăng nhập/Đăng ký
    const userInfo = document.getElementById("userInfoContainer");       // Khu vực hiển thị tên user
    const userNameDisplay = document.getElementById("userName");         // Chỗ điền tên
    const userAvatar = document.getElementById("userAvatar");            // Chỗ điền avatar (chữ cái đầu)
    const logoutBtn = document.getElementById("logoutBtn");              // Nút đăng xuất

    if (userJson) {
        // --- TRƯỜNG HỢP ĐÃ ĐĂNG NHẬP ---
        const user = JSON.parse(userJson);

        // 1. Ẩn nút đăng nhập/đăng ký
        if(authButtons) authButtons.style.display = "none";

        // 2. Hiện thông tin user
        if(userInfo) userInfo.style.display = "flex";

        // 3. Cập nhật tên người dùng
        if(userNameDisplay) userNameDisplay.textContent = user.HoTen; // 'HoTen' là key từ database trả về

        // 4. Tạo Avatar từ chữ cái đầu của tên
        if(userAvatar && user.HoTen) {
            const firstLetter = user.HoTen.charAt(0).toUpperCase();
            userAvatar.textContent = firstLetter;
        }

    } else {
        // --- TRƯỜNG HỢP CHƯA ĐĂNG NHẬP (KHÁCH) ---
        if(authButtons) authButtons.style.display = "flex";
        if(userInfo) userInfo.style.display = "none";
    }

    // --- XỬ LÝ ĐĂNG XUẤT ---
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function(e) {
            e.preventDefault();
            
            // Xóa thông tin khỏi localStorage
            localStorage.removeItem("user");

            // Load lại trang để reset giao diện về trạng thái chưa đăng nhập
            window.location.reload(); 
            // Hoặc chuyển về trang đăng nhập: window.location.href = 'dangnhap.html';
        });
    }

    // --- XỬ LÝ DROPDOWN MENU (Bấm vào tên thì hiện menu con) ---
    const userInfoClick = document.getElementById("userInfo");
    const userMenu = document.getElementById("userMenu");

    if (userInfoClick && userMenu) {
        userInfoClick.addEventListener("click", function(e) {
            e.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
            // Toggle class 'active' hoặc đổi display
            if (userMenu.style.display === "block") {
                userMenu.style.display = "none";
            } else {
                userMenu.style.display = "block";
            }
        });

        // Bấm ra ngoài thì đóng menu
        document.addEventListener("click", function() {
            userMenu.style.display = "none";
        });
    }
});