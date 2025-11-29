<?php
require_once "../../config/Database.php";
require_once "../../model/Review.php";
require_once "../../helper/response.php";

class ReviewController {
    private $model;

    public function __construct() {
        $db = (new Database())->connect();
        $this->model = new Review($db);
    }

    public function add() {
        $data = json_decode(file_get_contents("php://input"), true);
        jsonResponse(true, "Đã đánh giá", $this->model->add($data));
    }

    public function list() {
        $sachId = $_GET["sach"] ?? 0;
        jsonResponse(true, "Danh sách đánh giá", $this->model->getBySach($sachId));
    }
}

$controller = new ReviewController();
$action = $_GET["action"] ?? "";

switch ($action) {
    case "add": $controller->add(); break;
    case "list": $controller->list(); break;
    default: jsonResponse(false, "API không hợp lệ");
}
