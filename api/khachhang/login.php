<?php
// Ngăn cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Cho phép CORS và các method
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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
    include "../../config/config.php";
    include "../../model/KhachHang.php";

    $db = (new Database())->connect();
    $model = new KhachHang($db);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data["Email"]) || !isset($data["MatKhau"])) {
        echo json_encode(["status"=>false, "message"=>"Dữ liệu không hợp lệ"]);
        exit;
    }

    // Email có thể là email hoặc số điện thoại
    $emailOrPhone = $data["Email"];
    $stmt = $model->login($emailOrPhone);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data["MatKhau"], $user["MatKhau"])) {
        // Không trả về mật khẩu
        unset($user["MatKhau"]);
        echo json_encode(["status"=>true, "user"=>$user]);
    } else {
        echo json_encode(["status"=>false, "message"=>"Sai email/số điện thoại hoặc mật khẩu"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status"=>false, "message"=>"Lỗi database: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["status"=>false, "message"=>"Lỗi: " . $e->getMessage()]);
}
