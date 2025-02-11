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
    $stock = $_POST['stock'] ?? null;  // Stok bisa bernilai null jika kosong

    // Validasi input wajib diisi
    if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price)) {
        // Jika stok kosong, set sebagai null agar sesuai dengan perubahan di model
        $stock = ($stock === '' || is_null($stock)) ? null : (int)$stock;

        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'satuan' => $satuan,
            'price' => $price,
            'stock' => $stock,  // Stok bisa null atau angka
        ];

        $result = $productController->addProduct($data);

        $_SESSION['alert'] = $result ? 'added' : 'add_failed';
    } else {
        $_SESSION['alert'] = 'invalid_input';  // Alert jika input tidak lengkap
    }

    header("Location: ../views/admin/products.php");
    exit();
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
    $stock = $_POST['stock'] ?? ''; // Pastikan stok selalu diambil sebagai input

    if (!empty($name) && !empty($description) && !empty($category) && !empty($satuan) && !empty($price)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'satuan' => $satuan,
            'price' => $price,
            'stock' => $stock,
        ];

        $result = $productController->updateProduct($productId, $data);

        $_SESSION['alert'] = $result ? 'updated' : 'update_failed';
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
