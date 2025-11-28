<?php
require_once __DIR__ . "/../config/config.php";

class KhachHang {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function dangKy($hoTen, $email, $password) {
        $sql = "INSERT INTO KhachHang (HoTen, Email, MatKhau) VALUES (:hoTen, :email, :matkhau)";
        $stmt = $this->conn->prepare($sql);

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":hoTen", $hoTen);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":matkhau", $hash);

        return $stmt->execute();
    }

    public function dangNhap($email) {
        $sql = "SELECT * FROM KhachHang WHERE Email = :email AND TrangThai = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
