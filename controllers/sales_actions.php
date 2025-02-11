<?php
require_once '../config/database.php';
require_once 'SalesController.php';
require_once 'ProductController.php';

session_start();
$action = $_GET['action'] ?? '';
$role = $_SESSION['role'] ?? '';

$database = new Database();
$db = $database->getConnection();
$salesController = new SalesController($db);
$productController = new ProductController($db);

try {
    ob_start();  // Mulai buffer output
    switch ($action) {
        case 'add':
            handleAddSale($salesController, $productController, $role);
            $redirectUrl = ($role === 'karyawan') ? '../views/employee/sales.php' : '../views/admin/sales.php';
            header("Location: $redirectUrl");
            exit();
        case 'edit':
            handleEditSale($salesController, $productController);
            $redirectUrl = ($role === 'karyawan') ? '../views/employee/sales.php' : '../views/admin/sales.php';
            header("Location: $redirectUrl");
            exit();
        case 'delete':
            handleDeleteSale($salesController);
            break;  // Tidak ada header, karena delete mengembalikan JSON
        default:
            echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
    }
    ob_end_flush();  // Kirim output setelah diproses
} catch (Exception $e) {
    ob_end_clean();  // Hapus output jika ada kesalahan
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada server.', 'error' => $e->getMessage()]);
}

// ==============================
// Fungsi Handler
// ==============================

function handleAddSale($salesController, $productController, $role)
{
    $data = sanitizeInput($_POST);
    $productId = intval($data['id_produk'] ?? 0);
    $quantitySold = intval($data['jumlah_terjual'] ?? 0);

    if ($productId > 0 && $quantitySold > 0) {
        $product = $productController->getProductById($productId);

        if ($product && $product['stok'] >= $quantitySold) {
            $result = $salesController->addSale($data);
            if ($result) {
                // Kurangi stok produk setelah berhasil menambah penjualan
                $newStock = $product['stok'] - $quantitySold;
                $productController->updateStock($productId, $newStock);
                $_SESSION['alert'] = 'added';
            } else {
                $_SESSION['alert'] = 'add_failed';
            }
        } else {
            $_SESSION['alert'] = 'insufficient_stock';
        }
    } else {
        $_SESSION['alert'] = 'validation_error';
    }
    error_log("Session alert set to: " . $_SESSION['alert']);
}

function handleEditSale($salesController, $productController)
{
    $id = intval($_GET['id'] ?? 0);
    $data = sanitizeInput($_POST);

    if ($id === 0 || !validateSaleData($data)) {
        $_SESSION['alert'] = 'validation_error';
        return;
    }

    $result = $salesController->updateSales($id, $data);
    $_SESSION['alert'] = ($result === 'success') ? 'updated' : 'update_failed';
}

function handleDeleteSale($salesController)
{
    header('Content-Type: application/json');
    ob_start();  // Buffer untuk menghindari output tambahan

    $id = intval($_GET['id'] ?? 0);

    if ($id === 0) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
        return;
    }

    $result = $salesController->deleteSaleWithStockRestore($id);

    ob_end_clean();
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil dihapus. Stok produk telah dikembalikan ke jumlah sebelum penjualan.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus data. Stok produk tidak berubah.'
        ]);
    }
}

// ==============================
// Fungsi Helper
// ==============================

function sanitizeInput($data)
{
    $sanitizedData = [];
    foreach ($data as $key => $value) {
        $sanitizedData[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    return $sanitizedData;
}

function validateSaleData($data)
{
    return !empty($data['id_produk']) && !empty($data['id_pelanggan']) && !empty($data['tanggal_penjualan']) &&
        !empty($data['jumlah_terjual']) && !empty($data['total_harga']) &&
        is_numeric($data['jumlah_terjual']) && is_numeric($data['total_harga']);
}
