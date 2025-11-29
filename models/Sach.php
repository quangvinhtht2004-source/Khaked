<?php
require_once __DIR__ . "/../core/Model.php";

class Sach extends Model {

    public function getById($id) {
        // Join thêm Thể loại để lấy tên thể loại
        $sql = "SELECT s.*, tl.TenTheLoai 
                FROM Sach s 
                LEFT JOIN TheLoai tl ON s.TheLoaiID = tl.TheLoaiID 
                WHERE s.SachID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm sách (chỉ lấy sách đang bán)
    public function search($keyword) {
        $sql = "SELECT * FROM Sach 
                WHERE TenSach LIKE ? AND TrangThai = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy tất cả sách mới nhất
    public function getNewArrivals() {
        return $this->db->query("SELECT * FROM Sach WHERE TrangThai = 1 ORDER BY NgayTao DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật điểm đánh giá (khi có review mới)
    public function updateRating($SachID, $SoSao) {
        // Công thức tính trung bình cộng dồn
        $sql = "UPDATE Sach 
                SET RatingTB = ((RatingTB * SoDanhGia) + :SoSao) / (SoDanhGia + 1),
                    SoDanhGia = SoDanhGia + 1
                WHERE SachID = :SachID";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['SoSao' => $SoSao, 'SachID' => $SachID]);
    }
}