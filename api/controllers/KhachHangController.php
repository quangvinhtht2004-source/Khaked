<?php
require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../models/KhachHang.php";
require_once __DIR__ . "/../../helper/response.php";

class KhachHangController {

    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new KhachHang($this->db);
    }



$controller = new KhachHangController();
$action = $_GET["action"] ?? "";

switch ($action) {
    case "register": $controller->register(); break;
    case "login": $controller->login(); break;
    default: jsonResponse(false, "API không hợp lệ");
}
