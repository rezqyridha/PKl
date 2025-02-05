<?php
class SalesModel
{
    private $db;
    private $table = 'penjualan';

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Menambahkan penjualan
    public function addSale($data)
    {
        $query = "INSERT INTO {$this->table} (id_produk, id_pelanggan, tanggal_penjualan, jumlah_terjual, total_harga)
                  VALUES (:id_produk, :id_pelanggan, :tanggal_penjualan, :jumlah_terjual, :total_harga)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_produk', $data['id_produk']);
        $stmt->bindParam(':id_pelanggan', $data['id_pelanggan']);
        $stmt->bindParam(':tanggal_penjualan', $data['tanggal_penjualan']);
        $stmt->bindParam(':jumlah_terjual', $data['jumlah_terjual']);
        $stmt->bindParam(':total_harga', $data['total_harga']);

        return $stmt->execute();
    }

    // Mengambil semua penjualan dengan nama produk dan nama pelanggan
    public function getAllSales()
    {
        $query = "SELECT penjualan.id_penjualan, produk.nama_produk, pelanggan.nama_pelanggan, 
                  penjualan.tanggal_penjualan, penjualan.jumlah_terjual, penjualan.total_harga
                  FROM {$this->table} 
                  JOIN produk ON penjualan.id_produk = produk.id_produk
                  JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Mengambil penjualan berdasarkan ID
    public function getSaleById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_penjualan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSalesByDate($startDate, $endDate)
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE tanggal_penjualan BETWEEN :start_date AND :end_date";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengedit penjualan
    public function updateSale($id, $data)
    {
        $query = "UPDATE {$this->table} SET id_produk = :id_produk, id_pelanggan = :id_pelanggan, 
                    tanggal_penjualan = :tanggal_penjualan, jumlah_terjual = :jumlah_terjual, total_harga = :total_harga 
                    WHERE id_penjualan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_produk', $data['id_produk']);
        $stmt->bindParam(':id_pelanggan', $data['id_pelanggan']);
        $stmt->bindParam(':tanggal_penjualan', $data['tanggal_penjualan']);
        $stmt->bindParam(':jumlah_terjual', $data['jumlah_terjual']);
        $stmt->bindParam(':total_harga', $data['total_harga']);

        return $stmt->execute();
    }

    // Menghapus penjualan
    public function deleteSale($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id_penjualan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
