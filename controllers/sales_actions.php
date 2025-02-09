<?php
require_once '../config/database.php';
require_once 'SalesController.php';
require_once 'ProductController.php';

session_start(); // Pastikan session dimulai

$action = $_GET['action'] ?? '';



$database = new Database();
$db = $database->getConnection();
$salesController = new SalesController($db);
$productController = new ProductController($db); // Tambahkan ProductController untuk logika stok

// Menangani aksi tambah penjualan
if ($action == 'add') {
    $data = sanitizeInput($_POST);
    $productId = $data['id_produk'] ?? 0;
    $customerId = $data['id_pelanggan'] ?? 0;
    $saleDate = $data['tanggal_penjualan'] ?? null;
    $quantitySold = $data['jumlah_terjual'] ?? 0;
    $totalPrice = $data['total_harga'] ?? 0;

    if ($productId && $quantitySold > 0) {
        $product = $productController->getProductById($productId);

        if ($product && $product['stok'] >= $quantitySold) {
            $newStock = $product['stok'] - $quantitySold;
            $productController->updateStock($productId, $newStock);

            $data['total_harga'] = $totalPrice;
            $result = $salesController->addSale($data);

            $_SESSION['alert'] = $result ? 'added' : 'add_failed';
        } else {
            $_SESSION['alert'] = 'insufficient_stock';
        }
    } else {
        $_SESSION['alert'] = 'invalid_input';
    }

    header("Location: ../views/admin/sales.php");
    exit();
} elseif ($action == 'edit') {
    $id = intval($_GET['id'] ?? 0);

    if ($id == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/sales.php");
        exit();
    }

    $data = sanitizeInput($_POST);

    if (validateSaleData($data)) {
        $result = $salesController->updateSales($id, $data);

        $_SESSION['alert'] = ($result === 'success') ? 'updated' : (($result === 'no_change') ? 'no_change' : 'update_failed');
        header("Location: ../views/admin/sales.php");
        exit();
    } else {
        $_SESSION['alert'] = 'validation_error';
        header("Location: ../views/admin/sales.php");
        exit();
    }
} elseif ($action == 'delete') {
    $id = intval($_GET['id'] ?? 0);

    if ($id == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/sales.php");
        exit();
    }

    $result = $salesController->deleteSale($id);

    if ($result) {
        $_SESSION['alert'] = 'deleted';
    } else {
        $_SESSION['alert'] = 'delete_failed';
    }
    header("Location: ../views/admin/sales.php");
    exit();
} else {
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/sales.php");
    exit();
}

function validateSaleData($data)
{
    if (
        empty($data['id_produk']) || empty($data['id_pelanggan']) || empty($data['tanggal_penjualan']) ||
        empty($data['jumlah_terjual']) || empty($data['total_harga'])
    ) {
        return false;
    }

    if (!is_numeric($data['jumlah_terjual']) || $data['jumlah_terjual'] <= 0) {
        return false;
    }

    if (!is_numeric($data['total_harga']) || $data['total_harga'] <= 0) {
        return false;
    }

    return true;
}

function sanitizeInput($data)
{
    $sanitizedData = [];
    foreach ($data as $key => $value) {
        $sanitizedData[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    return $sanitizedData;
}
