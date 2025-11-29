<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/Review.php";
require_once __DIR__ . "/../../helper/response.php";

class ReviewController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new Review($this->db);
    }

    public function add() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Sửa: Thêm validate
        if (!isset($data['KhachHangID']) || !isset($data['SachID']) || !isset($data['SoSao'])) {
             jsonResponse(false, "Thiếu thông tin đánh giá");
             return;
        }

        $input = [
            'KhachHangID' => $data['KhachHangID'],
            'SachID'      => $data['SachID'],
            'SoSao'       => $data['SoSao'],
            'BinhLuan'    => $data['BinhLuan'] ?? ''
        ];

        if ($this->model->create($input)) {
            jsonResponse(true, "Đã gửi đánh giá");
        } else {
            jsonResponse(false, "Lỗi khi lưu đánh giá");
        }
    }

    public function list() {
        $sachId = $_GET["sach"] ?? 0;
        jsonResponse(true, "Danh sách đánh giá", $this->model->getBySach($sachId));
    }
}
?>