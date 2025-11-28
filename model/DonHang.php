<?php
class DonHang {

    private $conn;
    private $table = "DonHang";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo đơn hàng mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table}
                (KhachHangID, DiaChiGiao, SoDienThoai, PhuongThucTT, TrangThai, ThanhToan, TongTien)
                VALUES (:KhachHangID, :DiaChiGiao, :SoDienThoai, :PhuongThucTT, :TrangThai, :ThanhToan, :TongTien)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":KhachHangID"  => $data["KhachHangID"],
            ":DiaChiGiao"   => $data["DiaChiGiao"],
            ":SoDienThoai"  => $data["SoDienThoai"],
            ":PhuongThucTT" => $data["PhuongThucTT"],
            ":TrangThai"    => $data["TrangThai"],
            ":ThanhToan"    => $data["ThanhToan"],
            ":TongTien"     => $data["TongTien"]
        ]);
    }

    // Lấy danh sách đơn theo khách
    public function getByCustomer($KhachHangID) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE KhachHangID = :id 
                ORDER BY NgayTao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $KhachHangID]);

        return $stmt;
    }

    // Lấy toàn bộ đơn (cho admin)
    public function getAll() {
        return $this->conn->query("SELECT * FROM {$this->table} ORDER BY NgayTao DESC");
    }

    // Lấy thông tin 1 đơn
    public function getById($DonHangID) {
        $sql = "SELECT * FROM {$this->table} WHERE DonHangID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $DonHangID]);
        return $stmt;
    }

    // Cập nhật trạng thái đơn (Nhân viên xử lý)
    public function updateStatus($DonHangID, $TrangThai, $ThanhToan = null) {
        $sql = "UPDATE {$this->table}
                SET TrangThai = :TrangThai, ThanhToan = IF(:ThanhToan IS NULL, ThanhToan, :ThanhToan)
                WHERE DonHangID = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":TrangThai" => $TrangThai,
            ":ThanhToan" => $ThanhToan,
            ":id" => $DonHangID
        ]);
    }
}
