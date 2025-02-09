<?php
require_once __DIR__ . '../../config/database.php';
require_once __DIR__ . '../../controllers/RestockController.php';
require_once __DIR__ . '../../controllers/ProductController.php';

session_start();

$action = $_GET['action'] ?? '';
$database = new Database();
$db = $database->getConnection();
$restockController = new RestockController($db);
$productController = new ProductController($db);

try {
    switch ($action) {
        case 'add':
            handleAddRestock($restockController, $productController);
            break;

        case 'edit':
            handleEditRestock($restockController);
            break;

        case 'delete':
            handleDeleteRestock($restockController, $productController); // Perbarui fungsi delete
            break;

        default:
            $_SESSION['alert'] = 'invalid_action';
            redirectToRestock();
    }
} catch (Exception $e) {
    $_SESSION['alert'] = 'error';
    error_log("Exception di restock_actions.php: " . $e->getMessage());
    redirectToRestock();
}

exit();

// ==============================
// Fungsi Handler
// ==============================

function handleAddRestock($restockController, $productController)
{
    $data = sanitizeRestockInput($_POST);
    error_log("Data diterima di restock_actions.php (add): " . json_encode($data));

    if (validateRestockData($data)) {
        $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_per_unit'];
        $result = $restockController->addRestock($data);

        if ($result) {
            // Perbarui stok produk setelah restock berhasil
            $product = $productController->getProductById($data['id_produk']);
            if ($product) {
                $newStock = $product['stok'] + $data['jumlah_ditambahkan'];
                $productController->updateStock($data['id_produk'], $newStock);
                error_log("Stok produk ID {$data['id_produk']} diperbarui menjadi: $newStock");
            } else {
                error_log("Produk dengan ID {$data['id_produk']} tidak ditemukan.");
            }

            $_SESSION['alert'] = 'restock_success';
        } else {
            $_SESSION['alert'] = 'add_failed';
        }
    } else {
        $_SESSION['alert'] = 'validation_error';
    }

    redirectToRestock();
}


function handleEditRestock($restockController)
{
    $id = intval($_GET['id'] ?? 0);
    if ($id === 0) {
        $_SESSION['alert'] = 'invalid_id';
        redirectToRestock();
    }

    $data = sanitizeRestockInput($_POST);
    if (validateRestockData($data)) {
        $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_per_unit'];
        $result = $restockController->updateRestock($id, $data);
        $_SESSION['alert'] = $result ? 'updated' : 'no_change';
    } else {
        $_SESSION['alert'] = 'validation_error';
    }

    redirectToRestock();
}

function handleDeleteRestock($restockController, $productController)
{
    $id = intval($_GET['id'] ?? 0);
    if ($id === 0) {
        $_SESSION['alert'] = 'invalid_id';
        redirectToRestock();
    }

    // Ambil data restock sebelum dihapus
    $restock = $restockController->getRestockById($id);
    if ($restock) {
        $productId = $restock['id_produk'];
        $quantityAdded = $restock['jumlah_ditambahkan'];

        // Kurangi kembali stok produk
        $product = $productController->getProductById($productId);
        if ($product) {
            $newStock = $product['stok'] - $quantityAdded;
            $newStock = max($newStock, 0); // Pastikan stok tidak negatif
            $productController->updateStock($productId, $newStock);
            error_log("Stok produk ID $productId dikembalikan menjadi: $newStock");
        }

        // Hapus data restock
        $result = $restockController->deleteRestock($id);
        $_SESSION['alert'] = $result ? 'deleted' : 'delete_failed';
    } else {
        $_SESSION['alert'] = 'not_found';
    }

    redirectToRestock();
}

// ==============================
// Fungsi Helper
// ==============================

function sanitizeRestockInput($data)
{
    return [
        'id_produk' => htmlspecialchars(trim($data['id_produk'] ?? ''), ENT_QUOTES),
        'id_supplier' => htmlspecialchars(trim($data['id_supplier'] ?? ''), ENT_QUOTES),
        'tanggal_restock' => htmlspecialchars(trim($data['tanggal_restock'] ?? ''), ENT_QUOTES),
        'jumlah_ditambahkan' => htmlspecialchars(trim($data['jumlah_ditambahkan'] ?? ''), ENT_QUOTES),
        'harga_per_unit' => htmlspecialchars(trim($data['harga_per_unit'] ?? ''), ENT_QUOTES)
    ];
}

function validateRestockData($data)
{
    return !empty($data['id_produk']) &&
        !empty($data['id_supplier']) &&
        !empty($data['tanggal_restock']) &&
        is_numeric($data['jumlah_ditambahkan']) && $data['jumlah_ditambahkan'] > 0 &&
        is_numeric($data['harga_per_unit']) && $data['harga_per_unit'] > 0;
}


function redirectToRestock()
{
    header("Location: ../views/admin/restock.php");
    exit();
}
