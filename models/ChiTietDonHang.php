<?php
require_once __DIR__ . "/../core/Model.php";

class ChiTietDonHang extends Model {

    public function add($DonHangID, $SachID, $SoLuong, $DonGia) {
        $sql = "INSERT INTO ChiTietDonHang (DonHangID, SachID, SoLuong, DonGia) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$DonHangID, $SachID, $SoLuong, $DonGia]);
    }

    public function getByDonHang($DonHangID) {
        $sql = "SELECT ct.*, s.TenSach, s.AnhBia 
                FROM ChiTietDonHang ct
                LEFT JOIN Sach s ON ct.SachID = s.SachID
                WHERE ct.DonHangID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$DonHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}