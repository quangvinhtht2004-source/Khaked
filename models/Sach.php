<?php
require_once __DIR__ . "/../core/Model.php";

class Sach extends Model {

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Sach WHERE SachID = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT * FROM Sach WHERE TenSach LIKE ?");
        $stmt->execute(["%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRating($SachID, $SoSao) {
        $sql = "UPDATE Sach
                SET RatingTB = (RatingTB * SoDanhGia + :SoSao) / (SoDanhGia + 1),
                    SoDanhGia = SoDanhGia + 1
                WHERE SachID = :SachID";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            "SoSao" => $SoSao,
            "SachID" => $SachID
        ]);
    }
}
