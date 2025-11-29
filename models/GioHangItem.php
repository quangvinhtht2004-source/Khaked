<?php
require_once __DIR__ . "/../core/Model.php";

class GioHangItem extends Model {

    public function addItem($GioHangID, $SachID, $SoLuong) {
        $stmtCheck = $this->db->prepare("SELECT ItemID, SoLuong FROM GioHangItem WHERE GioHangID = ? AND SachID = ?");
        $stmtCheck->execute([$GioHangID, $SachID]);
        $item = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $newQty = $item['SoLuong'] + $SoLuong;
            $stmtUpdate = $this->db->prepare("UPDATE GioHangItem SET SoLuong = ? WHERE ItemID = ?");
            return $stmtUpdate->execute([$newQty, $item['ItemID']]);
        } else {
            $sql = "INSERT INTO GioHangItem (GioHangID, SachID, SoLuong) VALUES (?, ?, ?)";
            $stmtInsert = $this->db->prepare($sql);
            return $stmtInsert->execute([$GioHangID, $SachID, $SoLuong]);
        }
    }

    public function getItems($GioHangID) {
        // Lấy thêm cột PhanTramGiam
        $sql = "SELECT ghi.*, s.TenSach, s.Gia as GiaGoc, s.PhanTramGiam, s.AnhBia 
                FROM GioHangItem ghi
                JOIN Sach s ON ghi.SachID = s.SachID
                WHERE ghi.GioHangID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$GioHangID]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tính sẵn cột 'GiaBan' (Giá thực tế sau giảm) cho Controller dùng
        foreach ($items as &$item) {
            if (isset($item['PhanTramGiam']) && $item['PhanTramGiam'] > 0) {
                $item['GiaBan'] = $item['GiaGoc'] * (100 - $item['PhanTramGiam']) / 100;
            } else {
                $item['GiaBan'] = $item['GiaGoc'];
            }
        }
        return $items;
    }
    
    public function removeItem($ItemID) {
        $stmt = $this->db->prepare("DELETE FROM GioHangItem WHERE ItemID = ?");
        return $stmt->execute([$ItemID]);
    }
}
?>