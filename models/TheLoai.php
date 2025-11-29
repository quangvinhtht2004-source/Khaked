<?php
require_once __DIR__ . "/../core/Model.php";

class TheLoai extends Model {
    public function getAll() {
        return $this->db->query("SELECT * FROM TheLoai")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
         $stmt = $this->db->prepare("SELECT * FROM TheLoai WHERE TheLoaiID = ?");
         $stmt->execute([$id]);
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}