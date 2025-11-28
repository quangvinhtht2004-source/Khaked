<?php
class Database {
    private $host = "localhost";
    private $db_name = "quanlybansach";   // Tên database của bạn
    private $username = "root";           // Username XAMPP/MAMP/WAMP
    private $password = "";               // Mật khẩu XAMPP mặc định = rỗng
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            // Thiết lập UTF-8 để hỗ trợ tiếng Việt
            $this->conn->exec("SET NAMES utf8mb4");

            // Bật chế độ thông báo lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            echo "Lỗi kết nối: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
