<?php
header("Content-Type: application/json; charset=utf-8");

$controller = $_GET["controller"] ?? "";
$action     = $_GET["action"] ?? "";

$file = __DIR__ . "/controllers/{$controller}Controller.php";

if (!file_exists($file)) {
    echo json_encode(["status" => false, "message" => "Controller không tồn tại"]);
    exit;
}

require_once $file;

$className = $controller . "Controller";
$ctr = new $className;

if (!method_exists($ctr, $action)) {
    echo json_encode(["status" => false, "message" => "Action không tồn tại"]);
    exit;
}

$ctr->$action();
