<?php
require_once __DIR__ . "/../core/Model.php";

class KhachHang extends Model {

    public function register($data) {
        try {
            // 1. KIỂM TRA TRÙNG EMAIL
            $stmtCheckEmail = $this->db->prepare("SELECT KhachHangID FROM KhachHang WHERE Email = ?");
            $stmtCheckEmail->execute([$data['Email']]);
            if ($stmtCheckEmail->rowCount() > 0) {
                return ["status" => false, "message" => "Email này đã được sử dụng!"];
            }

            // 2. KIỂM TRA TRÙNG SỐ ĐIỆN THOẠI (Mới thêm)
            $stmtCheckPhone = $this->db->prepare("SELECT KhachHangID FROM KhachHang WHERE DienThoai = ?");
            $stmtCheckPhone->execute([$data['DienThoai']]);
            if ($stmtCheckPhone->rowCount() > 0) {
                return ["status" => false, "message" => "Số điện thoại này đã được đăng ký!"];
            }

            // 3. Hash mật khẩu
            $hashedPw = password_hash($data['MatKhau'], PASSWORD_BCRYPT);

            // 4. Insert dữ liệu (Địa chỉ để rỗng)
            $sql = "INSERT INTO KhachHang (HoTen, Email, MatKhau, DienThoai, DiaChi) 
                    VALUES (:HoTen, :Email, :MatKhau, :DienThoai, :DiaChi)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'HoTen'     => $data['HoTen'],
                'Email'     => $data['Email'],
                'MatKhau'   => $hashedPw,
                'DienThoai' => $data['DienThoai'], // Bắt buộc
                'DiaChi'    => ''                  // Mặc định là rỗng vì không nhập
            ]);

            if ($result) {
                return ["status" => true, "message" => "Đăng ký thành công"];
            }
            return ["status" => false, "message" => "Lỗi hệ thống"];

        } catch (PDOException $e) {
            return ["status" => false, "message" => "Lỗi SQL: " . $e->getMessage()];
        }
    }

    // ... (Giữ nguyên các hàm login, getById khác) ...
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM KhachHang WHERE Email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user['TrangThai'] == 0) return ["status" => false, "message" => "Tài khoản bị khóa"];
            
            if (password_verify($password, $user['MatKhau'])) {
                unset($user['MatKhau']);
                return ["status" => true, "data" => $user];
            }
        }
        return ["status" => false, "message" => "Email hoặc mật khẩu không đúng"];
    }
}
?>