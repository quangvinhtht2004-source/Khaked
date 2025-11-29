<?php
require_once __DIR__ . "/../core/Model.php";

class DonHang extends Model {

    public function create($data) {
        // $data bao gồm: KhachHangID, DiaChiGiao, SoDienThoai, PhuongThucTT, TongTien
        $sql = "INSERT INTO DonHang 
                (KhachHangID, DiaChiGiao, SoDienThoai, PhuongThucTT, TrangThai, ThanhToan, TongTien) 
                VALUES 
                (:KhachHangID, :DiaChiGiao, :SoDienThoai, :PhuongThucTT, 'ChoXacNhan', 0, :TongTien)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function getByKhachHang($KhachHangID) {
        $stmt = $this->db->prepare("SELECT * FROM DonHang WHERE KhachHangID = ? ORDER BY NgayTao DESC");
        $stmt->execute([$KhachHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM DonHang WHERE DonHangID = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($DonHangID, $TrangThai) {
        $stmt = $this->db->prepare("UPDATE DonHang SET TrangThai = ? WHERE DonHangID = ?");
        return $stmt->execute([$TrangThai, $DonHangID]);
    }
}