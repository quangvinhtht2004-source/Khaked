<?php
require_once __DIR__ . "/../core/Model.php";

class GioHang extends Model {

    public function getByUser($KhachHangID) {
        $stmt = $this->db->prepare("SELECT * FROM GioHang WHERE KhachHangID = ?");
        $stmt->execute([$KhachHangID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($KhachHangID) {
        $stmt = $this->db->prepare("INSERT INTO GioHang (KhachHangID) VALUES (?)");
        return $stmt->execute([$KhachHangID]);
    }
}
