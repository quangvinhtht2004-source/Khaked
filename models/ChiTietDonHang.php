<?php
require_once __DIR__ . "/../core/Model.php";

class ChiTietDonHang extends Model {

    public function create($data) {
        $sql = "INSERT INTO ChiTietDonHang (DonHangID, SachID, SoLuong, DonGia)
                VALUES (:DonHangID, :SachID, :SoLuong, :DonGia)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getItems($DonHangID) {
        $stmt = $this->db->prepare("
            SELECT ct.*, s.TenSach, s.AnhBia 
            FROM ChiTietDonHang ct
            JOIN Sach s ON ct.SachID = s.SachID
            WHERE DonHangID = ?");
        $stmt->execute([$DonHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
