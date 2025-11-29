<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/KhachHang.php";
require_once __DIR__ . "/../../helper/response.php";

class KhachHangController {
    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new KhachHang($this->db);
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Kiểm tra các trường bắt buộc (bao gồm DienThoai)
        if (empty($data['HoTen']) || empty($data['Email']) || empty($data['MatKhau']) || empty($data['DienThoai'])) {
            jsonResponse(false, "Vui lòng nhập đầy đủ: Họ tên, Email, SĐT, Mật khẩu");
            return;
        }

        // Validate Email
        if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
            jsonResponse(false, "Email không hợp lệ");
            return;
        }

        // Validate SĐT (Cơ bản: phải là số)
        if (!preg_match('/^[0-9]{10,11}$/', $data['DienThoai'])) {
             jsonResponse(false, "Số điện thoại không hợp lệ (phải từ 10-11 số)");
             return;
        }

        $result = $this->model->register($data);
        jsonResponse($result['status'], $result['message']);
    }

    // ... (Giữ nguyên hàm login) ...
    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = $data['Email'] ?? '';
        $pass  = $data['MatKhau'] ?? '';
        $result = $this->model->login($email, $pass);
        
        if ($result['status']) {
            jsonResponse(true, "Đăng nhập thành công", $result['data']);
        } else {
            jsonResponse(false, $result['message']);
        }
    }
}
?>