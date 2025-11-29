<?php
require_once __DIR__ . "/../core/Model.php";

class TheLoai extends Model {

    public function getAll() {
        return $this->db->query("SELECT * FROM TheLoai")->fetchAll(PDO::FETCH_ASSOC);
    }
}
