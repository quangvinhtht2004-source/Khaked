<?php
class DonHangLog {

    private $conn;
    private $table = "DonHangLog";

    public $DonHangID;
    public $NhanVienID;
    public $TrangThaiMoi;
    public $GhiChu;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add() {
        $sql = "INSERT INTO {$this->table}
                (DonHangID, NhanVienID, TrangThaiMoi, GhiChu)
                VALUES (:DonHangID,:NhanVienID,:TrangThaiMoi,:GhiChu)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":DonHangID" => $this->DonHangID,
            ":NhanVienID" => $this->NhanVienID,
            ":TrangThaiMoi" => $this->TrangThaiMoi,
            ":GhiChu" => $this->GhiChu
        ]);
    }
}
