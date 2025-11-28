    <?php
require_once __DIR__ . "/../config/config.php";

class GioHang {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getgetConnection();
    }

    public function getCart($khachHangID) {
        $sql = "SELECT * FROM GioHang WHERE KhachHangID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $khachHangID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
