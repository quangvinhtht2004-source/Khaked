<?php
require_once __DIR__ . "/../core/Model.php";

class NhanVien extends Model {

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM NhanVien WHERE Email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['MatKhau'])) {
            if ($user['TrangThai'] == 0) return false; // Bị khóa
            unset($user['MatKhau']);
            return $user;
        }
        return false;
    }

    public function create($data) {
        // Data gồm: HoTen, Email, MatKhau (Raw), VaiTro
        $data['MatKhau'] = password_hash($data['MatKhau'], PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO NhanVien (HoTen, Email, MatKhau, VaiTro) 
                VALUES (:HoTen, :Email, :MatKhau, :VaiTro)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}