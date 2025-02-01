<?php
class LogModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllLogs() {
        $query = "SELECT * FROM log_aktivitas";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLog($userId, $action) {
        $query = "INSERT INTO log_aktivitas (id_pengguna, aksi, tanggal_aksi) VALUES (:userId, :action, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':action', $action);
        $stmt->execute();
    }
}
?>
