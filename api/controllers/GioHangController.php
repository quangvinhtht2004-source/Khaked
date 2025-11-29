<?php
require_once "../../config/Database.php";
require_once "../../model/GioHang.php";
require_once "../../helper/response.php";

class GioHangController {
    private $model;

    public function __construct() {
        $db = (new Database())->connect();
        $this->model = new GioHang($db);
    }

    public function get() {
        $userId = $_GET["user"] ?? 0;
        jsonResponse(true, "Giỏ hàng", $this->model->get($userId));
    }

    public function add() {
        $data = json_decode(file_get_contents("php://input"), true);
        jsonResponse(true, "Đã thêm", $this->model->add($data));
    }

    public function remove() {
        $data = json_decode(file_get_contents("php://input"), true);
        jsonResponse(true, "Đã xóa", $this->model->remove($data));
    }
}

$controller = new GioHangController();
$action = $_GET["action"] ?? "";

switch ($action) {
    case "get": $controller->get(); break;
    case "add": $controller->add(); break;
    case "remove": $controller->remove(); break;
    default: jsonResponse(false, "API không hợp lệ");
}
