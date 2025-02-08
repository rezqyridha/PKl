<?php
require_once __DIR__ . '../../config/database.php';
require_once __DIR__ . '../../controllers/RestockController.php';
session_start();

$action = $_GET['action'] ?? '';
$database = new Database();
$db = $database->getConnection();
$restockController = new RestockController($db);

try {
    switch ($action) {
        case 'add':
            handleAddRestock($restockController);
            break;

        case 'edit':
            handleEditRestock($restockController);
            break;

        case 'delete':
            handleDeleteRestock($restockController);
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

function handleAddRestock($restockController)
{
    $data = sanitizeRestockInput($_POST);
    error_log("Data diterima di restock_actions.php (add): " . json_encode($data));

    if (validateRestockData($data)) {
        $data['total_biaya'] = $data['jumlah_ditambahkan'] * $data['harga_per_unit'];
        $result = $restockController->addRestock($data);
        $_SESSION['alert'] = $result ? 'added' : 'add_failed';
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

function handleDeleteRestock($restockController)
{
    $id = intval($_GET['id'] ?? 0);
    if ($id === 0) {
        $_SESSION['alert'] = 'invalid_id';
        redirectToRestock();
    }

    $result = $restockController->deleteRestock($id);
    $_SESSION['alert'] = $result ? 'deleted' : 'delete_failed';

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
