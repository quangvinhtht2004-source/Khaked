<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/DonHang.php";
require_once __DIR__ . "/../../models/ChiTietDonHang.php";
require_once __DIR__ . "/../../models/GioHang.php";
require_once __DIR__ . "/../../models/GioHangItem.php";
require_once __DIR__ . "/../../models/KhuyenMai.php"; // Nhớ require
require_once __DIR__ . "/../../helper/response.php";

class DonHangController {
    private $db;
    private $donHangModel;
    private $chiTietModel;
    private $gioHangModel;
    private $itemModel;
    private $kmModel;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->donHangModel = new DonHang($this->db);
        $this->chiTietModel = new ChiTietDonHang($this->db);
        $this->gioHangModel = new GioHang($this->db);
        $this->itemModel    = new GioHangItem($this->db);
        $this->kmModel      = new KhuyenMai($this->db);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['KhachHangID'])) {
            jsonResponse(false, "Thiếu ID khách hàng");
            return;
        }
        
        $userId = $data['KhachHangID'];
        $couponCode = $data['CouponCode'] ?? '';

        // 1. Lấy giỏ hàng (Model GioHangItem đã tự tính GiaBan sau khi trừ % sách)
        $cart = $this->gioHangModel->getOrCreate($userId);
        $items = $this->itemModel->getItems($cart['GioHangID']);

        if (empty($items)) {
            jsonResponse(false, "Giỏ hàng trống");
            return;
        }

        // 2. Tính TỔNG TIỀN TẠM TÍNH (Dựa trên giá sách đã giảm)
        $tamTinh = 0;
        foreach ($items as $item) {
            $tamTinh += $item['GiaBan'] * $item['SoLuong'];
        }

        // 3. Tính GIẢM GIÁ VOUCHER (Nếu có)
        $tienGiamVoucher = 0;
        $kmId = null;

        if (!empty($couponCode)) {
            $km = $this->kmModel->findByCode($couponCode);
            
            if (!$km) {
                jsonResponse(false, "Mã giảm giá không tồn tại"); return;
            }
            if ($km['SoLuong'] <= 0) {
                jsonResponse(false, "Mã đã hết lượt dùng"); return;
            }
            if ($tamTinh < $km['DonToiThieu']) {
                jsonResponse(false, "Đơn hàng chưa đủ điều kiện (Tối thiểu " . number_format($km['DonToiThieu']) . "đ)"); return;
            }

            // Tính toán
            if ($km['LoaiKM'] == 'phantram') {
                $tienGiamVoucher = $tamTinh * ($km['GiaTri'] / 100);
            } else {
                $tienGiamVoucher = $km['GiaTri'];
            }

            if ($tienGiamVoucher > $tamTinh) $tienGiamVoucher = $tamTinh;
            $kmId = $km['KhuyenMaiID'];
        }

        // 4. Chốt Tổng Tiền
        $tongTien = $tamTinh - $tienGiamVoucher;

        // 5. Lưu Đơn Hàng
        $orderData = [
            'KhachHangID'     => $userId,
            'DiaChiGiao'      => $data['DiaChiGiao'] ?? '',
            'SoDienThoai'     => $data['SoDienThoai'] ?? '',
            'PhuongThucTT'    => $data['PhuongThucTT'] ?? 'COD',
            'TongTien'        => $tongTien,
            'TienGiamVoucher' => $tienGiamVoucher,
            'KhuyenMaiID'     => $kmId
        ];
        
        $donHangID = $this->donHangModel->create($orderData);

        if ($donHangID) {
            // Trừ số lượng mã
            if ($kmId) $this->kmModel->decreaseQuantity($kmId);

            // Lưu chi tiết (Quan trọng: Lưu GiaBan thực tế)
            foreach ($items as $item) {
                $this->chiTietModel->add($donHangID, $item['SachID'], $item['SoLuong'], $item['GiaBan']);
            }

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
        $id = $_GET['id'] ?? 0;
        $order = $this->donHangModel->getById($id);
        if ($order) {
            $order['ChiTiet'] = $this->chiTietModel->getByDonHang($id);
            jsonResponse(true, "Chi tiết đơn hàng", $order);
        } else {
            jsonResponse(false, "Không tìm thấy đơn hàng");
        }
    }
}
?>