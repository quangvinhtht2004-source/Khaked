<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/GioHang.php";
require_once __DIR__ . "/../../models/GioHangItem.php";
require_once __DIR__ . "/../../helper/response.php";

class GioHangController {
    private $db;
    private $gioHangModel;
    private $itemModel;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->gioHangModel = new GioHang($this->db);
        $this->itemModel = new GioHangItem($this->db);
    }

    public function get() {
        $userId = $_GET["user"] ?? 0;
        if ($userId == 0) {
            jsonResponse(false, "Chưa đăng nhập");
            return;
        }

        $cart = $this->gioHangModel->getOrCreate($userId);
        $items = $this->itemModel->getItems($cart['GioHangID']);
        jsonResponse(true, "Giỏ hàng", $items);
    }

    public function add() {
        $data = json_decode(file_get_contents("php://input"), true);
        $userId = $data['KhachHangID'] ?? 0;
        $sachId = $data['SachID'] ?? 0;
        $soLuong = $data['SoLuong'] ?? 1;

        if ($userId == 0 || $sachId == 0) {
            jsonResponse(false, "Dữ liệu không hợp lệ");
            return;
        }

        $cart = $this->gioHangModel->getOrCreate($userId);
        
        if ($this->itemModel->addItem($cart['GioHangID'], $sachId, $soLuong)) {
            jsonResponse(true, "Đã thêm vào giỏ");
        } else {
            jsonResponse(false, "Lỗi thêm giỏ hàng");
        }
    }

    public function remove() {
        $data = json_decode(file_get_contents("php://input"), true);
        $itemId = $data['ItemID'] ?? 0;

        if ($this->itemModel->removeItem($itemId)) {
            jsonResponse(true, "Đã xóa sản phẩm");
        } else {
            jsonResponse(false, "Lỗi khi xóa");
        }
    }
}
?>