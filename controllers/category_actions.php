<?php
require_once '../config/database.php';
require_once '../controllers/CategoryController.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$categoryController = new CategoryController($db);

if ($action == 'add') {
    $nama_kategori = isset($_POST['nama_kategori']) ? $_POST['nama_kategori'] : '';

    if (!empty($nama_kategori)) {
        $data = ['nama_kategori' => $nama_kategori];

        $result = $categoryController->addCategory($data);
        $_SESSION['alert'] = $result ? 'added' : 'failed';
    } else {
        $_SESSION['alert'] = 'failed';
    }
    header("Location: ../views/admin/categories.php");
    exit();
} elseif ($action == 'edit') {
    $categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $nama_kategori = isset($_POST['nama_kategori']) ? $_POST['nama_kategori'] : '';

    if ($categoryId > 0 && !empty($nama_kategori)) {
        $data = ['nama_kategori' => $nama_kategori];
        $result = $categoryController->updateCategory($categoryId, $data);
        $_SESSION['alert'] = $result ? 'updated' : 'failed';
    } else {
        $_SESSION['alert'] = 'failed';
    }
    header("Location: ../views/admin/categories.php");
    exit();
} elseif ($action == 'delete') {
    $categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($categoryId > 0) {
        $result = $categoryController->deleteCategory($categoryId);
        echo json_encode(["success" => $result, "message" => $result ? "Kategori berhasil dihapus." : "Gagal menghapus kategori."]);
    } else {
        echo json_encode(["success" => false, "message" => "ID tidak valid."]);
    }
    exit();
} else {
    $_SESSION['alert'] = 'failed';
    header("Location: ../views/admin/categories.php");
    exit();
}
