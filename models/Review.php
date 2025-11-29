<?php
require_once __DIR__ . "/../core/Model.php";

class Review extends Model {

    public function create($data) {
        // Mặc định TrangThai = 1 (Hiện luôn) hoặc 0 (Chờ duyệt) tùy chính sách
        $sql = "INSERT INTO Review (KhachHangID, SachID, SoSao, BinhLuan, TrangThai) 
                VALUES (:KhachHangID, :SachID, :SoSao, :BinhLuan, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getBySach($SachID) {
        // Chỉ lấy review Đã duyệt (TrangThai = 1)
        $sql = "SELECT r.*, k.HoTen 
                FROM Review r
                JOIN KhachHang k ON r.KhachHangID = k.KhachHangID
                WHERE r.SachID = ? AND r.TrangThai = 1
                ORDER BY r.NgayDanhGia DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$SachID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}