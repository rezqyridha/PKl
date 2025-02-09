<?php
require_once '../config/database.php';
require_once '../controllers/SatuanController.php';

$database = new Database();
$db = $database->getConnection();
$satuanController = new SatuanController($db);

$id_produk = $_GET['id_produk'] ?? null;

if ($id_produk) {
    $satuan = $satuanController->getSatuanByProductId($id_produk);
    if ($satuan) {
        echo json_encode(['success' => true, 'nama_satuan' => $satuan['nama_satuan']]);
    } else {
        echo json_encode(['success' => false]);
    }
}
