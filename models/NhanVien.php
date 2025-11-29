<?php
require_once __DIR__ . "/../core/Model.php";

class NhanVien extends Model {

    public function login($email) {
        $stmt = $this->db->prepare("SELECT * FROM NhanVien WHERE Email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO NhanVien (HoTen, Email, MatKhau, VaiTro)
                VALUES (:HoTen, :Email, :MatKhau, :VaiTro)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
