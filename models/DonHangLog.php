<?php
require_once __DIR__ . "/../core/Model.php";

class DonHangLog extends Model {

    public function addLog($DonHangID, $NhanVienID, $TrangThaiMoi, $GhiChu) {
        $sql = "INSERT INTO DonHangLog (DonHangID, NhanVienID, TrangThaiMoi, GhiChu) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$DonHangID, $NhanVienID, $TrangThaiMoi, $GhiChu]);
    }

    public function getLogsByDonHang($DonHangID) {
        // Left Join vì NhanVienID có thể Null (nếu khách tự hủy)
        $sql = "SELECT log.*, nv.HoTen as TenNhanVien 
                FROM DonHangLog log
                LEFT JOIN NhanVien nv ON log.NhanVienID = nv.NhanVienID
                WHERE log.DonHangID = ? 
                ORDER BY log.NgayCapNhat DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$DonHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}