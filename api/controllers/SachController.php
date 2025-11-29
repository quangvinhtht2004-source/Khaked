<?php
require_once "../../config/Database.php";
require_once "../../model/Sach.php";
require_once "../../helper/response.php";

class SachController {
    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new Sach($this->db);
    }

    public function list() {
        $list = $this->model->getAll();
        jsonResponse(true, "Danh sách sách", $list);
    }

    public function detail() {
        $id = $_GET["id"] ?? 0;
        $sach = $this->model->getDetail($id);
        $sach ? jsonResponse(true, "Chi tiết sách", $sach)
              : jsonResponse(false, "Không tìm thấy sách");
    }

    public function search() {
        $keyword = $_GET["keyword"] ?? "";
        $result = $this->model->search($keyword);
        jsonResponse(true, "Kết quả tìm kiếm", $result);
    }
}

$controller = new SachController();
$action = $_GET["action"] ?? "";

switch ($action) {
    case "list": $controller->list(); break;
    case "detail": $controller->detail(); break;
    case "search": $controller->search(); break;
    default: jsonResponse(false, "API không hợp lệ");
}
