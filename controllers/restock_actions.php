<?php
require_once __DIR__ . '../../config/database.php';
require_once __DIR__ . '../../controllers/RestockController.php';
require_once __DIR__ . '../../controllers/ProductController.php';

session_start();
$action = $_GET['action'] ?? '';

// Tentukan URL redirect berdasarkan role pengguna
$redirectUrl = ($_SESSION['role'] === 'admin') ? "../views/admin/restock.php" : "../views/employee/restock.php";

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
            if ($_SESSION['role'] === 'admin') {
                handleEditRestock($restockController, $productController);
            } else {
                $_SESSION['alert'] = 'unauthorized_action';
            }
            break;

        case 'delete':
            if ($_SESSION['role'] === 'admin') {
                handleDeleteRestock($restockController, $productController);
            } else {
                $_SESSION['alert'] = 'unauthorized_action';
            }
            break;

        default:
            $_SESSION['alert'] = 'invalid_action';
    }
} catch (Exception $e) {
    $_SESSION['alert'] = 'error';
    error_log("Exception di restock_actions.php: " . $e->getMessage());
}

header("Location: $redirectUrl");
exit();

// ==============================
// Fungsi Handler
// ==============================

function handleAddRestock($restockController, $productController)
{
    $data = sanitizeRestockInput($_POST);
    $data['harga_per_unit'] = cleanNumericInput($data['harga_per_unit']);
    $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_per_unit'];

    if ($restockController->addRestock($data)) {
        $_SESSION['alert'] = 'restock_success';
    } else {
        $_SESSION['alert'] = 'add_failed';
    }
}

function handleEditRestock($restockController, $productController)
{
    $id = intval($_GET['id'] ?? 0);
    if ($id === 0) {
        $_SESSION['alert'] = 'invalid_id';
        return;
    }

    $data = sanitizeRestockInput($_POST);
    $data['harga_per_unit'] = cleanNumericInput($data['harga_per_unit']);
    $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_per_unit'];

    $result = $restockController->updateRestock($id, $data);
    $_SESSION['alert'] = $result ? 'updated' : 'no_change';
}

function handleDeleteRestock($restockController, $productController)
{
    $id = intval($_GET['id'] ?? 0);
    if ($id === 0) {
        $_SESSION['alert'] = 'invalid_id';
        return;
    }

    $result = $restockController->deleteRestock($id);
    $_SESSION['alert'] = $result ? 'deleted' : 'delete_failed';
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
        'jumlah_ditambahkan' => intval($data['jumlah_ditambahkan'] ?? 0),
        'harga_per_unit' => htmlspecialchars(trim($data['harga_per_unit'] ?? ''), ENT_QUOTES)
    ];
}

function cleanNumericInput($value)
{
    return (int)str_replace('.', '', $value);
}
