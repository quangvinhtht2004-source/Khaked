<?php
// Ngăn cache (Giống register.php)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Cho phép CORS và các method (Giống register.php)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Login chỉ cần POST
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status"=>false, "message"=>"Method not allowed. Use POST."]);
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Include config và model (Giống register.php)
    include "../../config/config.php";
    include "../../model/KhachHang.php";

    $db = (new Database())->connect();
    $model = new KhachHang($db);

    // Nhận dữ liệu JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // 1. Kiểm tra dữ liệu đầu vào
    if (!$data) {
        http_response_code(400);
        echo json_encode(["status"=>false, "message"=>"Không nhận được dữ liệu JSON"]);
        exit;
    }

    if (!isset($data["Email"]) || !isset($data["MatKhau"])) {
        http_response_code(400);
        echo json_encode(["status"=>false, "message"=>"Vui lòng nhập Email/SĐT và Mật khẩu"]);
        exit;
    }

    // 2. Tìm user trong database
    // Lưu ý: data["Email"] ở đây chứa giá trị người dùng nhập (có thể là Email HOẶC SĐT)
    $stmt = $model->login($data["Email"]); 
    
    // Kiểm tra có tìm thấy user không
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Kiểm tra mật khẩu (So sánh pass nhập vào với pass đã mã hóa trong DB)
        // Lưu ý: Trong register.php bạn dùng password_hash, nên ở đây dùng password_verify
        if (password_verify($data["MatKhau"], $user["MatKhau"])) {
            
            // Xóa mật khẩu hash trước khi trả về client để bảo mật
            unset($user["MatKhau"]);

            // TRẢ VỀ THÀNH CÔNG (status: true khớp với login.js)
            http_response_code(200);
            echo json_encode([
                "status" => true,
                "message" => "Đăng nhập thành công",
                "user" => $user
            ]);
        } else {
            // Sai mật khẩu
            http_response_code(401); // 401 Unauthorized
            echo json_encode(["status"=>false, "message"=>"Mật khẩu không chính xác"]);
        }
    } else {
        // Không tìm thấy tài khoản (Sai email hoặc SĐT)
        http_response_code(401);
        echo json_encode(["status"=>false, "message"=>"Tài khoản không tồn tại"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status"=>false, "message"=>"Lỗi database: " . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status"=>false, "message"=>"Lỗi: " . $e->getMessage()]);
}
?>