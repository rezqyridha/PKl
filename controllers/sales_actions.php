<?php
require_once '../config/database.php';
require_once 'SalesController.php';
require_once 'ProductController.php';

session_start(); // Pastikan session dimulai

$action = $_GET['action'] ?? '';

$database = new Database();
$db = $database->getConnection();
$salesController = new SalesController($db);
$productController = new ProductController($db);

if ($action == 'add') {
    $data = sanitizeInput($_POST);
    $productId = $data['id_produk'] ?? 0;
    $quantitySold = $data['jumlah_terjual'] ?? 0;

    if ($productId && $quantitySold > 0) {
        $product = $productController->getProductById($productId);

        if ($product && $product['stok'] >= $quantitySold) {
            // Kurangi stok produk
            $newStock = $product['stok'] - $quantitySold;
            $productController->updateStock($productId, $newStock);

            // Tambahkan penjualan
            $result = $salesController->addSale($data);
            $_SESSION['alert'] = $result ? 'added' : 'add_failed';
        } else {
            $_SESSION['alert'] = 'insufficient_stock'; // Stok tidak mencukupi
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
    } else {
        $_SESSION['alert'] = 'validation_error';
    }
    header("Location: ../views/admin/sales.php");
    exit();
} elseif ($action == 'delete') {
    $id = intval($_GET['id'] ?? 0);

    if ($id == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/sales.php");
        exit();
    }

    // Ambil data penjualan sebelum dihapus
    $sale = $salesController->getSaleById($id);

    if ($sale) {
        $productId = $sale['id_produk'];
        $quantitySold = $sale['jumlah_terjual'];

        // Tambahkan kembali jumlah terjual ke stok produk
        $product = $productController->getProductById($productId);
        if ($product) {
            $newStock = $product['stok'] + $quantitySold;
            $productController->updateStock($productId, $newStock);
            error_log("Stok produk ID $productId dikembalikan menjadi $newStock.");
        }

        // Hapus penjualan
        $result = $salesController->deleteSaleWithStockRestore($id);
        $_SESSION['alert'] = $result ? 'deleted' : 'delete_failed';
    } else {
        $_SESSION['alert'] = 'not_found';
    }
    header("Location: ../views/admin/sales.php");
    exit();
} else {
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/sales.php");
    exit();
}

// Fungsi Validasi Data Penjualan
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

// Fungsi Sanitasi Input
function sanitizeInput($data)
{
    $sanitizedData = [];
    foreach ($data as $key => $value) {
        $sanitizedData[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    return $sanitizedData;
}
