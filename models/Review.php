<?php
require_once __DIR__ . "/../core/Model.php";

class Review extends Model {

    public function addReview($data) {
        $sql = "INSERT INTO Review (KhachHangID, SachID, SoSao, BinhLuan)
                VALUES (:KhachHangID, :SachID, :SoSao, :BinhLuan)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getBySach($SachID) {
        $stmt = $this->db->prepare("
            SELECT r.*, k.HoTen
            FROM Review r
            JOIN KhachHang k ON r.KhachHangID = k.KhachHangID
            WHERE SachID = ?");
        $stmt->execute([$SachID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
