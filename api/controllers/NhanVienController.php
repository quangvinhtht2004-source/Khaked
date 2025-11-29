<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/NhanVien.php";
require_once __DIR__ . "/../../helper/response.php";

class NhanVienController {
    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new NhanVien($this->db);
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            jsonResponse(false, "Không có dữ liệu gửi lên");
            return;
        }

        $email = $data["Email"] ?? "";
        $password = $data["MatKhau"] ?? "";

        $nv = $this->model->login($email, $password);

        if ($nv) {
            jsonResponse(true, "Đăng nhập thành công", $nv);
        } else {
            jsonResponse(false, "Sai email, mật khẩu hoặc tài khoản bị khóa");
        }
    }
}
?>