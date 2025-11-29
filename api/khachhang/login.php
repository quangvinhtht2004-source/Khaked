<?php
// Tắt báo lỗi hiển thị để tránh hỏng JSON
error_reporting(0);
ini_set('display_errors', 0);

// --- CẤP QUYỀN TRUY CẬP (CORS) ---
header("Access-Control-Allow-Origin: *"); // Cho phép mọi nguồn truy cập
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Cho phép các method này
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Xử lý request OPTIONS (trình duyệt gửi trước khi gửi POST)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // ... (Phần code kết nối DB và xử lý bên dưới giữ nguyên)
    require_once "../../config/Database.php";
    require_once "../../models/KhachHang.php";

    // Kết nối DB
    $database = new Database();
    $db = $database->connect();
    
    if(!$db) {
        throw new Exception("Lỗi kết nối cơ sở dữ liệu");
    }

    $kh = new KhachHang($db);

    // Lấy dữ liệu gửi lên
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['Email']) || empty($data['MatKhau'])) {
        throw new Exception("Vui lòng nhập Email và Mật khẩu");
    }

    // Gọi hàm login từ Model
    $result = $kh->login($data['Email'], $data['MatKhau']);

    // Trả về kết quả
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
?>