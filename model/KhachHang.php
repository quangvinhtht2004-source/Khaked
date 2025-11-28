<?php
class KhachHang {

    private $conn;
    private $table = "KhachHang";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE Email=:Email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["Email"=>$email]);
        return $stmt;
    }

    public function register($data) {
        $sql = "INSERT INTO {$this->table}
                (HoTen, Email, MatKhau, DienThoai, DiaChi)
                VALUES (:HoTen, :Email, :MatKhau, :DienThoai, :DiaChi)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function login($email) {
        $sql = "SELECT * FROM {$this->table} WHERE Email=:Email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["Email"=>$email]);
        return $stmt;
    }
}
