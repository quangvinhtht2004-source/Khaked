<?php
require_once "../../config/Database.php";
require_once "../../model/NhanVien.php";
require_once "../../helper/response.php";

class NhanVienController {
    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new NhanVien($this->db);
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) jsonResponse(false, "Không có dữ liệu JSON");

        $email = $data["Email"] ?? "";
        $password = $data["MatKhau"] ?? "";

        $nv = $this->model->login($email);

        if ($nv && password_verify($password, $nv["MatKhau"])) {
            unset($nv["MatKhau"]);
            jsonResponse(true, "Đăng nhập thành công", $nv);
        }

        jsonResponse(false, "Sai thông tin đăng nhập");
    }
}

$controller = new NhanVienController();
$action = $_GET["action"] ?? "";
($action === "login") ? $controller->login() : jsonResponse(false, "API không hợp lệ");
