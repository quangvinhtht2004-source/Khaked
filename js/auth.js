// Quản lý authentication và session
class AuthManager {
    constructor() {
        this.storageKey = 'vkdbookstore_user';
        this.apiBase = '../api/khachhang';
    }

    // Lưu thông tin user vào localStorage
    setUser(user) {
        // Không lưu mật khẩu
        const userData = {
            MaKH: user.MaKH,
            HoTen: user.HoTen,
            Email: user.Email,
            DienThoai: user.DienThoai,
            DiaChi: user.DiaChi
        };
        localStorage.setItem(this.storageKey, JSON.stringify(userData));
    }

    // Lấy thông tin user từ localStorage hoặc sessionStorage
    getUser() {
        // Ưu tiên localStorage (ghi nhớ đăng nhập)
        let userData = localStorage.getItem(this.storageKey);
        if (!userData) {
            // Nếu không có trong localStorage, thử sessionStorage
            userData = sessionStorage.getItem(this.storageKey);
        }
        return userData ? JSON.parse(userData) : null;
    }

    // Kiểm tra đã đăng nhập chưa
    isLoggedIn() {
        return this.getUser() !== null;
    }

    // Đăng xuất
    logout() {
        localStorage.removeItem(this.storageKey);
        sessionStorage.removeItem(this.storageKey);
        localStorage.removeItem('currentUser');
        sessionStorage.removeItem('currentUser');
        window.location.href = '../html/index.html';
    }

    // Hiển thị thông báo
    showMessage(message, type = 'error') {
        // Tạo element thông báo
        const messageDiv = document.createElement('div');
        messageDiv.className = `auth-message auth-message-${type}`;
        messageDiv.textContent = message;
        
        // Thêm vào form
        const form = document.querySelector('.auth-box form');
        if (form) {
            // Xóa thông báo cũ nếu có
            const oldMessage = form.querySelector('.auth-message');
            if (oldMessage) {
                oldMessage.remove();
            }
            
            form.insertBefore(messageDiv, form.firstChild);
            
            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    }
}

// Khởi tạo AuthManager global
const authManager = new AuthManager();

