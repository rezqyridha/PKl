<?php

// SatuanController.php
require_once __DIR__ . '/../models/SatuanModel.php';
require_once __DIR__ . '/../config/database.php';

class SatuanController
{
    private $satuanModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->satuanModel = new SatuanModel($db);
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
}
