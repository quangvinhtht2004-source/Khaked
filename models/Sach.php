<?php
require_once __DIR__ . "/../core/Model.php";

class Sach extends Model {

    // Hàm hỗ trợ tính giá khuyến mãi
    private function processPrice($book) {
        if ($book) {
            $book['GiaGoc'] = $book['Gia']; // Giữ giá gốc để hiển thị gạch ngang
            
            // Nếu có % giảm thì tính lại giá bán
            if (isset($book['PhanTramGiam']) && $book['PhanTramGiam'] > 0) {
                $book['Gia'] = $book['GiaGoc'] * (100 - $book['PhanTramGiam']) / 100;
            }
        }
        return $book;
    }

    public function getById($id) {
        $sql = "SELECT s.*, tl.TenTheLoai 
                FROM Sach s 
                LEFT JOIN TheLoai tl ON s.TheLoaiID = tl.TheLoaiID 
                WHERE s.SachID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $this->processPrice($book);
    }

    public function search($keyword) {
        $sql = "SELECT * FROM Sach WHERE TenSach LIKE ? AND TrangThai = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["%$keyword%"]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Xử lý giá cho từng cuốn sách trong danh sách
        foreach ($books as &$book) {
            $book = $this->processPrice($book);
        }
        return $books;
    }
    
    public function getNewArrivals() {
        $books = $this->db->query("SELECT * FROM Sach WHERE TrangThai = 1 ORDER BY NgayTao DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($books as &$book) {
            $book = $this->processPrice($book);
        }
        return $books;
    }

    // Giữ nguyên hàm updateRating...
    public function updateRating($SachID, $SoSao) {
        $sql = "UPDATE Sach 
                SET RatingTB = ((RatingTB * SoDanhGia) + :SoSao) / (SoDanhGia + 1),
                    SoDanhGia = SoDanhGia + 1
                WHERE SachID = :SachID";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['SoSao' => $SoSao, 'SachID' => $SachID]);
    }
}
?>