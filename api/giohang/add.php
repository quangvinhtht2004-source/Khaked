<?php
include "../../config/config.php";
include "../../model/GioHangItem.php";

$db = (new Database())->connect();
$item = new GioHangItem($db);

$data = json_decode(file_get_contents("php://input"),true);

$item->addItem($data);

echo json_encode(["status"=>true]);
