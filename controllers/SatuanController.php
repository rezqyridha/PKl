<?php

// SatuanController.php
require_once __DIR__ . '/../models/SatuanModel.php';
require_once __DIR__ . '/../config/database.php';

class SatuanController
{
    private $satuanModel;
    private $db;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->satuanModel = new SatuanModel($db);
        $this->db = $db;
    }

    public function index()
    {
        return $this->satuanModel->getAllSatuan();
    }

    public function create($nama_satuan, $deskripsi)
    {
        return $this->satuanModel->addSatuan($nama_satuan, $deskripsi);
    }

    public function edit($id, $nama_satuan, $deskripsi)
    {
        return $this->satuanModel->updateSatuan($id, $nama_satuan, $deskripsi);
    }

    public function delete($id)
    {
        return $this->satuanModel->deleteSatuan($id);
    }

    public function getSatuanById($id)
    {
        return $this->satuanModel->getSatuanById($id);
    }

    public function getAllSatuan()
    {
        $query = "SELECT * FROM satuan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSatuanByProductId($id_produk)
    {
        try {
            $query = "SELECT s.* 
                  FROM satuan s
                  JOIN produk p ON p.id_satuan = s.id_satuan
                  WHERE p.id_produk = :id_produk";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_produk', $id_produk, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getSatuanByProductId: " . $e->getMessage());
            return null;
        }
    }
}
