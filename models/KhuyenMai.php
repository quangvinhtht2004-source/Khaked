<?php
require_once __DIR__ . "/../core/Model.php";

class KhuyenMai extends Model {

    // Tìm mã khuyến mãi theo Code (Test điều kiện: Đang mở, Còn số lượng, Chưa hết hạn)
    public function findByCode($code) {
        $sql = "SELECT * FROM KhuyenMai 
                WHERE Code = ? 
                AND TrangThai = 1 
                AND SoLuong > 0 
                AND NgayKetThuc >= NOW()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Trừ số lượng mã sau khi đặt hàng thành công
    public function decreaseQuantity($id) {
        $sql = "UPDATE KhuyenMai SET SoLuong = SoLuong - 1 WHERE KhuyenMaiID = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>