<?php
require_once '../../config/database.php';
require_once '../../controllers/SalesController.php';

session_start();

$database = new Database();
$db = $database->getConnection();
$salesController = new SalesController($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'add') {
    $data = [
        'id_produk' => $_POST['id_produk'],
        'id_pelanggan' => $_POST['id_pelanggan'],
        'tanggal_penjualan' => $_POST['tanggal_penjualan'],
        'jumlah_terjual' => $_POST['jumlah_terjual'],
        'total_harga' => $_POST['total_harga'],
    ];

    $result = $salesController->addSale($data);

    if ($result) {
        $_SESSION['alert'] = 'added';
        header("Location: ../views/admin/sales.php");
    } else {
        $_SESSION['alert'] = 'add_failed';
        header("Location: ../views/admin/sales.php");
    }
} elseif ($action == 'edit') {
    $id = $_GET['id'] ?? null;
    $data = [
        'id_produk' => $_POST['id_produk'],
        'id_pelanggan' => $_POST['id_pelanggan'],
        'tanggal_penjualan' => $_POST['tanggal_penjualan'],
        'jumlah_terjual' => $_POST['jumlah_terjual'],
        'total_harga' => $_POST['total_harga'],
    ];

    $result = $salesController->updateSale($id, $data);

    if ($result) {
        $_SESSION['alert'] = 'updated';
        header("Location: ../views/admin/sales.php");
    } else {
        $_SESSION['alert'] = 'update_failed';
        header("Location: ../views/admin/sales.php");
    }
} elseif ($action == 'delete') {
    $id = $_GET['id'] ?? null;

    $result = $salesController->deleteSale($id);

    if ($result) {
        $_SESSION['alert'] = 'deleted';
    } else {
        $_SESSION['alert'] = 'delete_failed';
    }

    header("Location: ../views/admin/sales.php");
}
