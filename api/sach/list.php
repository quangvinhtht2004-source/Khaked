<?php
include "../../config/config.php";
include "../../model/Sach.php";

$db = (new Database())->connect();
$sach = new Sach($db);

echo json_encode($sach->getAll()->fetchAll(PDO::FETCH_ASSOC));
