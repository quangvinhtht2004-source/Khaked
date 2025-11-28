<?php
class GioHang {

    private $conn;
    private $table = "GioHang";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByCustomer($KhachHangID) {
        $stmt = $this->conn->prepare("SELECT * FROM GioHang WHERE KhachHangID=:id");
        $stmt->execute(["id"=>$KhachHangID]);
        return $stmt;
    }

    public function create($KhachHangID) {
        $stmt = $this->conn->prepare("INSERT INTO GioHang(KhachHangID) VALUES(:id)");
        return $stmt->execute(["id"=>$KhachHangID]);
    }
}
