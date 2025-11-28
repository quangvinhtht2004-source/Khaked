<?php
include "../../config/config.php";
include "../../model/Sach.php";

$db = (new Database())->connect();
$sach = new Sach($db);

$key = $_GET["q"] ?? "";

echo json_encode($sach->search($key)->fetchAll(PDO::FETCH_ASSOC));
