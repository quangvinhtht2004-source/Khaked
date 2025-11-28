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

    // valid request
    if (!$data) {
        echo json_encode(["status"=>false, "message"=>"Không nhận được dữ liệu JSON"]);
        exit;
    }

    if (!isset($data["Email"]) || !isset($data["MatKhau"])) {
        echo json_encode(["status"=>false, "message"=>"Thiếu Email hoặc Mật khẩu"]);
        exit;
    }

    // Kiểm tra email
    if ($model->checkEmail($data["Email"])->rowCount() > 0) {
        echo json_encode(["status"=>false, "message"=>"Email đã tồn tại"]);
        exit;
    }

    // Kiểm tra số điện thoại nếu có
    if (isset($data["DienThoai"]) && !empty($data["DienThoai"])) {
        if ($model->checkPhone($data["DienThoai"])->rowCount() > 0) {
            echo json_encode(["status"=>false, "message"=>"Số điện thoại đã tồn tại"]);
            exit;
        }
    }

    // Gói data insert
    $dataInsert = [
        "HoTen"     => $data["HoTen"] ?? "",
        "Email"     => $data["Email"],
        "MatKhau"   => password_hash($data["MatKhau"], PASSWORD_DEFAULT),
        "DienThoai" => $data["DienThoai"] ?? "",
        "DiaChi"    => $data["DiaChi"] ?? ""
    ];

    if ($model->register($dataInsert)) {
        echo json_encode(["status"=>true, "message"=>"Đăng ký thành công"]);
    } else {
        $errorInfo = $db->errorInfo();
        $errorMsg = "Lỗi hệ thống";
        if (isset($errorInfo[2])) {
            $errorMsg = "Lỗi database: " . $errorInfo[2];
        }
        echo json_encode(["status"=>false, "message"=>$errorMsg]);
    }
} catch (PDOException $e) {
    echo json_encode(["status"=>false, "message"=>"Lỗi database: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["status"=>false, "message"=>"Lỗi: " . $e->getMessage()]);
}