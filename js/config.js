// Cấu hình API và môi trường
const AppConfig = {
    // Tự động phát hiện môi trường
    getBaseURL: function() {
        const hostname = window.location.hostname;
        const port = window.location.port;
        const protocol = window.location.protocol;
        
        // Nếu đang chạy trên Live Server (port 5500, 3000, etc.)
        if (port && (port === '5500' || port === '3000' || port === '8080')) {
            // Trả về URL của WAMP server
            return 'http://localhost/frontend';
        }
        
        // Nếu đang chạy trên localhost (WAMP)
        if (hostname === 'localhost' || hostname === '127.0.0.1') {
            return `${protocol}//${hostname}/frontend`;
        }
        
        // Mặc định: relative path
        return '';
    },
    
    // Lấy URL API đầy đủ
    getAPIURL: function(endpoint) {
        const baseURL = this.getBaseURL();
        if (baseURL) {
            return `${baseURL}/api/${endpoint}`;
        }
        // Relative path nếu không có base URL
        return `../api/${endpoint}`;
    }
};

// Export để sử dụng
window.AppConfig = AppConfig;

