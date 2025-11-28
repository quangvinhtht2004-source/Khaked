<?php
class Sach {

    private $conn;
    private $table = "Sach";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM {$this->table} WHERE TrangThai=1");
    }

    public function search($key) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE TenSach LIKE :key OR TacGia LIKE :key";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["key" => "%$key%"]);
        return $stmt;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE SachID=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id"=>$id]);
        return $stmt;
    }
}
