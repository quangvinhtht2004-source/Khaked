<?php
require_once "../../config/Database.php";
require_once "../../model/DonHang.php";
require_once "../../helper/response.php";

class DonHangController {
    private $model;

    public function __construct() {
        $db = (new Database())->connect();
        $this->model = new DonHang($db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->model->create($data);
        jsonResponse($result["status"], $result["message"]);
    }

    public function list() {
        $userId = $_GET["user"] ?? 0;
        jsonResponse(true, "Danh sách đơn", $this->model->listByUser($userId));
    }
}

$controller = new DonHangController();
$action = $_GET["action"] ?? "";

switch ($action) {
    case "create": $controller->create(); break;
    case "list": $controller->list(); break;
    default: jsonResponse(false, "API không hợp lệ");
}
