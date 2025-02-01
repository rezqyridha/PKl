<?php
class RestockModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllRestocks() {
        $query = "SELECT * FROM restock";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestockById($id) {
        $query = "SELECT * FROM restock WHERE id_restock = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
