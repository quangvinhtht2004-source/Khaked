// Cập nhật header dựa trên trạng thái đăng nhập
document.addEventListener('DOMContentLoaded', function() {
    const userInfoContainer = document.getElementById('userInfoContainer');
    const authButtonsContainer = document.getElementById('authButtonsContainer');
    const userAvatar = document.getElementById('userAvatar');
    const userName = document.getElementById('userName');
    const userInfo = document.getElementById('userInfo');
    const userMenu = document.getElementById('userMenu');
    const logoutBtn = document.getElementById('logoutBtn');

    // Kiểm tra đăng nhập
    function updateHeader() {
        const user = authManager.getUser();
        
        if (user) {
            // Đã đăng nhập - hiển thị thông tin user
            if (userInfoContainer) userInfoContainer.style.display = 'block';
            if (authButtonsContainer) authButtonsContainer.style.display = 'none';
            
            // Cập nhật thông tin
            if (userName) {
                userName.textContent = user.HoTen || user.Email || 'Người dùng';
            }
            
            if (userAvatar) {
                // Lấy chữ cái đầu của tên
                const firstLetter = (user.HoTen || user.Email || 'U').charAt(0).toUpperCase();
                userAvatar.textContent = firstLetter;
            }
        } else {
            // Chưa đăng nhập - hiển thị nút đăng nhập/đăng ký
            if (userInfoContainer) userInfoContainer.style.display = 'none';
            if (authButtonsContainer) authButtonsContainer.style.display = 'flex';
        }
    }

    // Toggle user menu
    if (userInfo) {
        userInfo.addEventListener('click', function(e) {
            e.stopPropagation();
            if (userMenu) {
                userMenu.classList.toggle('active');
            }
        });
    }

    // Đóng menu khi click bên ngoài
    document.addEventListener('click', function(e) {
        if (userMenu && !userInfo.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.remove('active');
        }
    });

    // Xử lý đăng xuất
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                authManager.logout();
            }
        });
    }

    // Cập nhật header khi trang load
    updateHeader();

    // Lắng nghe sự kiện storage để cập nhật khi đăng nhập/đăng xuất ở tab khác
    window.addEventListener('storage', function(e) {
        if (e.key === 'vkdbookstore_user') {
            updateHeader();
        }
    });
});

