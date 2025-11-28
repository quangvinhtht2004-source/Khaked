<?php
require_once __DIR__ . "/../config/config.php";

class DonHang {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function createOrder($khachHangID, $diaChi, $soDienThoai, $phuongThucTT, $tongTien) {
        $sql = "INSERT INTO DonHang (KhachHangID, DiaChiGiao, SoDienThoai, PhuongThucTT, TongTien, TrangThai)
                VALUES (:kh, :dc, :sdt, :pt, :tt, 'Pending')";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":kh", $khachHangID);
        $stmt->bindParam(":dc", $diaChi);
        $stmt->bindParam(":sdt", $soDienThoai);
        $stmt->bindParam(":pt", $phuongThucTT);
        $stmt->bindParam(":tt", $tongTien);

        return $stmt->execute();
    }
}
?>
