<?php
require_once __DIR__ . "/../models/KhachHang.php";
require_once __DIR__ . "/../helpers/response.php";

class AuthController {
    public function login($email, $password) {
        $kh = new KhachHang();
        $user = $kh->dangNhap($email);

        if ($user && password_verify($password, $user["MatKhau"])) {
            return success($user, "Đăng nhập thành công");
        }

        return error("Sai email hoặc mật khẩu");
    }
}
?>
