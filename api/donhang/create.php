<?php
include "../../config/config.php";
include "../../model/DonHang.php";
include "../../model/ChiTietDonHang.php";

$db = (new Database())->connect();

$dh = new DonHang($db);
$ct = new ChiTietDonHang($db);

$data = json_decode(file_get_contents("php://input"), true);

// Tạo đơn
$dh->create($data);
$DonHangID = $db->lastInsertId();

// Chi tiết
foreach ($data["Cart"] as $item) {
    $ct->create([
        "DonHangID"=>$DonHangID,
        "SachID"=>$item["SachID"],
        "SoLuong"=>$item["SoLuong"],
        "DonGia"=>$item["DonGia"]
    ]);
}

echo json_encode(["status"=>true, "DonHangID"=>$DonHangID]);
