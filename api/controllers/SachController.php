<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/Sach.php";
require_once __DIR__ . "/../../helper/response.php";

class SachController {
    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new Sach($this->db);
    }

    public function list() {
        $list = $this->model->search(""); 
        jsonResponse(true, "Danh sách sách", $list);
    }

    public function detail() {
        $id = $_GET["id"] ?? 0;
        $sach = $this->model->getById($id);
        if ($sach) {
            jsonResponse(true, "Chi tiết sách", $sach);
        } else {
            jsonResponse(false, "Không tìm thấy sách");
        }
    }

    public function search() {
        $keyword = $_GET["keyword"] ?? "";
        $result = $this->model->search($keyword);
        jsonResponse(true, "Kết quả tìm kiếm", $result);
    }
    
    public function newArrivals() {
        $result = $this->model->getNewArrivals();
        jsonResponse(true, "Sách mới về", $result);
    }
}
?>