<?php
require_once __DIR__ . "/../core/Model.php";

class GioHangItem extends Model {

    public function addItem($GioHangID, $SachID, $SoLuong) {
        // Kiểm tra xem sách đã có trong giỏ chưa
        $stmtCheck = $this->db->prepare("SELECT ItemID, SoLuong FROM GioHangItem WHERE GioHangID = ? AND SachID = ?");
        $stmtCheck->execute([$GioHangID, $SachID]);
        $item = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Nếu có rồi thì cộng dồn
            $newQty = $item['SoLuong'] + $SoLuong;
            $stmtUpdate = $this->db->prepare("UPDATE GioHangItem SET SoLuong = ? WHERE ItemID = ?");
            return $stmtUpdate->execute([$newQty, $item['ItemID']]);
        } else {
            // Nếu chưa có thì thêm mới
            $sql = "INSERT INTO GioHangItem (GioHangID, SachID, SoLuong) VALUES (?, ?, ?)";
            $stmtInsert = $this->db->prepare($sql);
            return $stmtInsert->execute([$GioHangID, $SachID, $SoLuong]);
        }
    }

    // Lấy danh sách item để hiển thị trang Cart
    public function getItems($GioHangID) {
        $sql = "SELECT ghi.*, s.TenSach, s.Gia, s.AnhBia 
                FROM GioHangItem ghi
                JOIN Sach s ON ghi.SachID = s.SachID
                WHERE ghi.GioHangID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$GioHangID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function removeItem($ItemID) {
        $stmt = $this->db->prepare("DELETE FROM GioHangItem WHERE ItemID = ?");
        return $stmt->execute([$ItemID]);
    }
}