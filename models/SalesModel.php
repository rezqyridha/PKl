<?php
class SalesModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllSales() {
        $query = "SELECT * FROM penjualan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSaleById($id) {
        $query = "SELECT * FROM penjualan WHERE id_penjualan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSalesByDate($date) {
        $query = "SELECT SUM(total_harga) AS total_sales FROM penjualan WHERE tanggal_penjualan = :date";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_sales'] ?? 0;
    }
    
    
}
?>
