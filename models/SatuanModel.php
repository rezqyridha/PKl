<?php
// SatuanModel.php
class SatuanModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllSatuan()
    {
        $query = "SELECT * FROM satuan ORDER BY id_satuan ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSatuanById($id)
    {
        $query = "SELECT * FROM satuan WHERE id_satuan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addSatuan($nama_satuan, $deskripsi)
    {
        $query = "INSERT INTO satuan (nama_satuan, deskripsi) VALUES (:nama_satuan, :deskripsi)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama_satuan', $nama_satuan);
        $stmt->bindParam(':deskripsi', $deskripsi);
        return $stmt->execute();
    }

    public function updateSatuan($id, $nama_satuan, $deskripsi)
    {
        $query = "UPDATE satuan SET nama_satuan = :nama_satuan, deskripsi = :deskripsi WHERE id_satuan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_satuan', $nama_satuan);
        $stmt->bindParam(':deskripsi', $deskripsi);
        return $stmt->execute();
    }

    public function deleteSatuan($id)
    {
        $query = "DELETE FROM satuan WHERE id_satuan = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
