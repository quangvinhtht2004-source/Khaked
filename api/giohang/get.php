<?php
include "../../config/config.php";
include "../../model/GioHang.php";

$db = (new Database())->connect();
$gh = new GioHang($db);

$KhachHangID = $_GET["khid"];

$stmt = $gh->getByCustomer($KhachHangID);
$gio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gio) {
    $gh->create($KhachHangID);
    $gio = $gh->getByCustomer($KhachHangID)->fetch(PDO::FETCH_ASSOC);
}

echo json_encode($gio);
