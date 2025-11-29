<?php
// Tắt báo lỗi hiển thị ra màn hình để tránh làm hỏng JSON
error_reporting(0); 
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // Lấy raw JSON body
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Dữ liệu JSON không hợp lệ");
    }

    if (empty($data)) {
        throw new Exception("Không nhận được dữ liệu");
    }

    // Kiểm tra các trường bắt buộc
    if (empty($data['HoTen']) || empty($data['Email']) || empty($data['MatKhau'])) {
        throw new Exception("Vui lòng điền đầy đủ thông tin");
    }

    // Nhúng file (Đảm bảo đường dẫn đúng với cấu trúc thư mục của bạn)
    // Lưu ý: Dựa vào ảnh bạn gửi, file này nằm trong api/khachhang/
    // Nên đường dẫn require phải đi ra 2 cấp cha
    require_once "../../config/Database.php";
    require_once "../../models/KhachHang.php";

    $database = new Database();
    $db = $database->connect();
    
    if(!$db) {
        throw new Exception("Lỗi kết nối CSDL");
    }

    $kh = new KhachHang($db);
    $result = $kh->register($data);

    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
?>