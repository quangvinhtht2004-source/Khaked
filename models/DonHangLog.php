<?php
require_once __DIR__ . "/../core/Model.php";

class DonHangLog extends Model {

    public function addLog($data) {
        $sql = "INSERT INTO DonHangLog (DonHangID, NhanVienID, TrangThaiMoi, GhiChu)
                VALUES (:DonHangID, :NhanVienID, :TrangThaiMoi, :GhiChu)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getLogs($DonHangID) {
        $stmt = $this->db->prepare("
            SELECT l.*, nv.HoTen AS TenNhanVien
            FROM DonHangLog l
            JOIN NhanVien nv ON l.NhanVienID = nv.NhanVienID
            WHERE DonHangID = ?");
        $stmt->execute([$DonHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
