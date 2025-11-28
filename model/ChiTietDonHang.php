<?php
class ChiTietDonHang {

    private $conn;
    private $table = "ChiTietDonHang";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm 1 sản phẩm vào chi tiết đơn hàng
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (DonHangID, SachID, SoLuong, DonGia)
                VALUES (:DonHangID, :SachID, :SoLuong, :DonGia)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":DonHangID" => $data["DonHangID"],
            ":SachID"    => $data["SachID"],
            ":SoLuong"   => $data["SoLuong"],
            ":DonGia"    => $data["DonGia"]
        ]);
    }

    // Lấy toàn bộ sản phẩm trong đơn hàng
    public function getByDonHang($DonHangID) {
        $sql = "SELECT ctdh.*, sach.TenSach, sach.AnhBia
                FROM {$this->table} ctdh
                JOIN Sach sach ON ctdh.SachID = sach.SachID
                WHERE ctdh.DonHangID = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $DonHangID]);

        return $stmt;
    }
}
