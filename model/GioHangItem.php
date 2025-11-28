<?php
class GioHangItem {

    private $conn;
    private $table = "GioHangItem";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addItem($data) {
        $sql = "INSERT INTO {$this->table} (GioHangID,SachID,SoLuong)
                VALUES (:GioHangID,:SachID,:SoLuong)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}
