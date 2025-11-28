<?php
header("Content-Type: application/json");

// include đúng — KHÔNG DÙNG $_SERVER['DOCUMENT_ROOT']
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
    echo json_encode(["status"=>false, "message"=>"Lỗi hệ thống"]);
}
