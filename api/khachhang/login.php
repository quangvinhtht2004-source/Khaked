<?php
header("Content-Type: application/json");

include "../../config/config.php";
include "../../model/KhachHang.php";

$db = (new Database())->connect();
$model = new KhachHang($db);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["Email"]) || !isset($data["MatKhau"])) {
    echo json_encode(["status"=>false, "message"=>"Dữ liệu không hợp lệ"]);
    exit;
}

$stmt = $model->login($data["Email"]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data["MatKhau"], $user["MatKhau"])) {
    echo json_encode(["status"=>true, "user"=>$user]);
} else {
    echo json_encode(["status"=>false, "message"=>"Sai email hoặc mật khẩu"]);
}
