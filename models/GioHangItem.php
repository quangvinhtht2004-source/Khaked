<?php
require_once __DIR__ . "/../core/Model.php";

class GioHangItem extends Model {

    public function addItem($data) {
        $sql = "INSERT INTO GioHangItem (GioHangID, SachID, SoLuong)
                VALUES (:GioHangID, :SachID, :SoLuong)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateItem($data) {
        $sql = "UPDATE GioHangItem
                SET SoLuong = :SoLuong
                WHERE GioHangID = :GioHangID AND SachID = :SachID";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getItems($GioHangID) {
        $stmt = $this->db->prepare("
            SELECT gh.*, s.TenSach, s.Gia, s.AnhBia
            FROM GioHangItem gh
            JOIN Sach s ON gh.SachID = s.SachID
            WHERE GioHangID = ?");
        $stmt->execute([$GioHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
