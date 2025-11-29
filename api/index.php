<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ... phần code Router bên dưới ...

// 2. Định tuyến (Router)
$controller = $_GET["controller"] ?? "";
$action     = $_GET["action"] ?? "";

// Kiểm tra controller có hợp lệ không
if (empty($controller) || empty($action)) {
    echo json_encode(["status" => false, "message" => "Vui lòng chỉ định controller và action"]);
    exit;
}

// Đường dẫn file Controller (Lưu ý đường dẫn file)
$file = __DIR__ . "/controllers/{$controller}Controller.php";

if (!file_exists($file)) {
    echo json_encode(["status" => false, "message" => "Controller '$controller' không tồn tại"]);
    exit;
}

require_once $file;

$className = $controller . "Controller";
if (!class_exists($className)) {
    echo json_encode(["status" => false, "message" => "Class '$className' không tồn tại"]);
    exit;
}

$ctr = new $className;

if (!method_exists($ctr, $action)) {
    echo json_encode(["status" => false, "message" => "Action '$action' không tồn tại"]);
    exit;
}

// Gọi hàm trong Controller
$ctr->$action();
?>