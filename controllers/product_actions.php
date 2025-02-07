<?php
require_once '../config/database.php';
require_once 'ProductController.php';

session_start(); // Pastikan session dimulai

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$productController = new ProductController($db);

// Menangani aksi tambah produk
if ($action == 'add') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $satuan = $_POST['satuan'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price) && !empty($stock)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'satuan' => $satuan,
            'price' => $price,
            'stock' => $stock,
        ];

        $result = $productController->addProduct($data);

        $_SESSION['alert'] = $result ? 'added' : 'add_failed';
        header("Location: ../views/admin/products.php");
        exit();
    } else {
        $_SESSION['alert'] = 'invalid_input';
        header("Location: ../views/admin/products.php");
        exit();
    }
}

// Menangani aksi edit produk
elseif ($action == 'edit') {
    $productId = intval($_GET['id'] ?? 0);

    if ($productId == 0) {
        $_SESSION['alert'] = 'invalid_id';
        header("Location: ../views/admin/products.php");
        exit();
    }

    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $satuan = $_POST['satuan'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price) && !empty($stock)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'satuan' => $satuan,
            'price' => $price,
            'stock' => $stock,
        ];

        $result = $productController->editProduct($productId, $data);

        $_SESSION['alert'] = $result ? 'updated' : 'no_change';
        header("Location: ../views/admin/products.php");
        exit();
    } else {
        $_SESSION['alert'] = 'update_failed';
        header("Location: ../views/admin/products.php");
        exit();
    }
}


// Menangani aksi hapus produk
elseif ($action == 'delete') {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($productId == 0) {
        echo json_encode(["success" => false, "message" => "ID produk tidak valid."]);
        exit();
    }

    $result = $productController->deleteProduct($productId);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Produk berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus produk."]);
    }
    exit();
} else {
    // Jika aksi tidak valid
    $_SESSION['alert'] = 'invalid_action';
    header("Location: ../views/admin/products.php");
    exit();
}
