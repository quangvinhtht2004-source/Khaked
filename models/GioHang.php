<?php
require_once __DIR__ . "/../core/Model.php";

class GioHang extends Model {

    // Lấy giỏ hàng của User, nếu chưa có thì tạo luôn
    public function getOrCreate($KhachHangID) {
        $stmt = $this->db->prepare("SELECT * FROM GioHang WHERE KhachHangID = ?");
        $stmt->execute([$KhachHangID]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return $cart;
        } else {
            // Tạo mới
            $stmtInsert = $this->db->prepare("INSERT INTO GioHang (KhachHangID) VALUES (?)");
            $stmtInsert->execute([$KhachHangID]);
            // Lấy lại ID vừa tạo
            $cartId = $this->db->lastInsertId();
            return ['GioHangID' => $cartId, 'KhachHangID' => $KhachHangID];
        }
    }
    
    // Xóa giỏ hàng (Sau khi đặt hàng thành công - thực tế là xóa items trong GioHangItem)
    public function clearCart($GioHangID) {
        $stmt = $this->db->prepare("DELETE FROM GioHangItem WHERE GioHangID = ?");
        return $stmt->execute([$GioHangID]);
    }
}