<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/DonHang.php";
require_once __DIR__ . "/../../models/ChiTietDonHang.php";
require_once __DIR__ . "/../../models/GioHang.php";
require_once __DIR__ . "/../../models/GioHangItem.php";
require_once __DIR__ . "/../../helper/response.php";

class DonHangController {
    private $db;
    private $donHangModel;
    private $chiTietModel;
    private $gioHangModel;
    private $itemModel;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->donHangModel = new DonHang($this->db);
        $this->chiTietModel = new ChiTietDonHang($this->db);
        $this->gioHangModel = new GioHang($this->db);
        $this->itemModel    = new GioHangItem($this->db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Sửa: Kiểm tra dữ liệu đầu vào chặt chẽ hơn
        if (!isset($data['KhachHangID'])) {
            jsonResponse(false, "Thiếu ID khách hàng");
            return;
        }
        
        $userId = $data['KhachHangID'];

        // 1. Lấy thông tin giỏ hàng
        $cart = $this->gioHangModel->getOrCreate($userId);
        $items = $this->itemModel->getItems($cart['GioHangID']);

        if (empty($items)) {
            jsonResponse(false, "Giỏ hàng trống");
            return;
        }

        // 2. Tính tổng tiền
        $tongTien = 0;
        foreach ($items as $item) {
            $tongTien += $item['Gia'] * $item['SoLuong'];
        }

        // 3. Tạo đơn hàng
        $orderData = [
            'KhachHangID'  => $userId,
            'DiaChiGiao'   => $data['DiaChiGiao'] ?? '',
            'SoDienThoai'  => $data['SoDienThoai'] ?? '',
            'PhuongThucTT' => $data['PhuongThucTT'] ?? 'COD',
            'TongTien'     => $tongTien
        ];
        
        $donHangID = $this->donHangModel->create($orderData);

        if ($donHangID) {
            // 4. Copy sang ChiTietDonHang
            foreach ($items as $item) {
                $this->chiTietModel->add($donHangID, $item['SachID'], $item['SoLuong'], $item['Gia']);
            }

            // 5. Xóa giỏ hàng
            $this->gioHangModel->clearCart($cart['GioHangID']);

            jsonResponse(true, "Đặt hàng thành công", ['DonHangID' => $donHangID]);
        } else {
            jsonResponse(false, "Lỗi tạo đơn hàng");
        }
    }

    public function list() {
        $userId = $_GET["user"] ?? 0;
        $orders = $this->donHangModel->getByKhachHang($userId);
        jsonResponse(true, "Danh sách đơn hàng", $orders);
    }

    public function detail() {
        $donHangID = $_GET['id'] ?? 0;
        $order = $this->donHangModel->getById($donHangID);
        
        if ($order) {
            $items = $this->chiTietModel->getByDonHang($donHangID);
            $order['ChiTiet'] = $items;
            jsonResponse(true, "Chi tiết đơn hàng", $order);
        } else {
            jsonResponse(false, "Không tìm thấy đơn hàng");
        }
    }
}
?>