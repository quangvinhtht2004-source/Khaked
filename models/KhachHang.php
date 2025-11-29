<?php
class KhachHang {

    private $conn;
    private $table = "KhachHang";

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- HÀM ĐĂNG KÝ ---
    public function register($data) {
        try {
            // Kiểm tra email trùng
            $queryCheck = "SELECT KhachHangID FROM " . $this->table . " WHERE Email = :Email LIMIT 1";
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(":Email", $data["Email"]);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                return ["status" => false, "message" => "Email đã tồn tại"];
            }

            // Hash mật khẩu
            $hashedPw = password_hash($data["MatKhau"], PASSWORD_BCRYPT);
            
            // Xử lý số điện thoại
            $sdt = isset($data["DienThoai"]) ? $data["DienThoai"] : "";

            $sql = "INSERT INTO $this->table (HoTen, Email, MatKhau, DienThoai)
                    VALUES (:HoTen, :Email, :MatKhau, :DienThoai)";

            $stmt = $this->conn->prepare($sql);

            $hoTen = htmlspecialchars(strip_tags($data["HoTen"]));
            $email = htmlspecialchars(strip_tags($data["Email"]));
            
            $stmt->bindParam(":HoTen", $hoTen);
            $stmt->bindParam(":Email", $email);
            $stmt->bindParam(":MatKhau", $hashedPw);
            $stmt->bindParam(":DienThoai", $sdt);

            if ($stmt->execute()) {
                return ["status" => true, "message" => "Đăng ký thành công"];
            }

            return ["status" => false, "message" => "Lỗi server"];

        } catch (PDOException $e) {
            return ["status" => false, "message" => "Lỗi SQL: " . $e->getMessage()];
        }
    }

    // --- HÀM ĐĂNG NHẬP (Phải nằm TRONG dấu ngoặc của class) ---
    public function login($email, $password) {
        // 1. Viết câu lệnh SQL lấy thông tin user theo Email
        $query = "SELECT KhachHangID, HoTen, Email, MatKhau, DienThoai 
                  FROM " . $this->table . " 
                  WHERE Email = :Email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Email", $email);
        $stmt->execute();

        // 2. Nếu tìm thấy Email
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3. Kiểm tra mật khẩu (So khớp hash)
            if (password_verify($password, $row['MatKhau'])) {
                // Xóa mật khẩu khỏi mảng trước khi trả về (để bảo mật)
                unset($row['MatKhau']);
                
                return [
                    "status" => true,
                    "message" => "Đăng nhập thành công",
                    "data" => $row 
                ];
            } else {
                return ["status" => false, "message" => "Mật khẩu không đúng"];
            }
        }

        return ["status" => false, "message" => "Email không tồn tại"];
    }

} // <--- Dấu đóng ngoặc class phải nằm ở đây (Cuối cùng)
?>