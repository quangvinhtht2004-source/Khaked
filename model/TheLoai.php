<?php
class TheLoai {
    private $conn;
    private $table = "TheLoai";

    public $TheLoaiID;
    public $TenTheLoai;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM {$this->table}");
    }
}
