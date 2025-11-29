<?php
require_once __DIR__ . "/../core/Model.php";

class DonHang extends Model {

    public function create($data) {
        $sql = "INSERT INTO DonHang
                (KhachHangID, DiaChiGiao, SoDienThoai, PhuongThucTT, TrangThai, ThanhToan, TongTien)
                VALUES
                (:KhachHangID, :DiaChiGiao, :SoDienThoai, :PhuongThucTT, :TrangThai, :ThanhToan, :TongTien)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    public function getByUser($KhachHangID) {
        $stmt = $this->db->prepare("SELECT * FROM DonHang WHERE KhachHangID = ?");
        $stmt->execute([$KhachHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
