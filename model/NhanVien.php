<?php
class NhanVien {

    private $conn;
    private $table = "NhanVien";

    public $NhanVienID;
    public $HoTen;
    public $Email;
    public $MatKhau;
    public $VaiTro;
    public $TrangThai;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT * FROM {$this->table} WHERE Email=:Email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Email", $this->Email);
        $stmt->execute();
        return $stmt;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM {$this->table}");
    }

    public function create() {
        $sql = "INSERT INTO {$this->table} (HoTen, Email, MatKhau, VaiTro, TrangThai)
                VALUES (:HoTen, :Email, :MatKhau, :VaiTro, :TrangThai)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":HoTen" => $this->HoTen,
            ":Email" => $this->Email,
            ":MatKhau" => $this->MatKhau,
            ":VaiTro" => $this->VaiTro,
            ":TrangThai" => $this->TrangThai
        ]);
    }
}
