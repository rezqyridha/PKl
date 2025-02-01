<?php

require_once '../config/database.php';
require_once '../controllers/CategoryController.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

$database = new Database();
$db = $database->getConnection();
$categoryController = new CategoryController($db);

if ($action === 'add') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (empty($name)) {
        $_SESSION['error'] = "Category name cannot be empty.";
        header("Location: ../views/admin/add_category.php");
        exit();
    }

    if ($categoryController->isCategoryExists($name)) {
        $_SESSION['error'] = "Category with the same name already exists.";
        header("Location: ../views/admin/add_category.php");
        exit();
    }

    $result = $categoryController->addCategory($name);

    if ($result) {
        $_SESSION['success'] = "Category successfully added!";
        header("Location: ../views/admin/categories.php");
    } else {
        $_SESSION['error'] = "Failed to add category.";
        header("Location: ../views/admin/add_category.php");
    }
    exit();
} elseif ($action === 'delete') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        $_SESSION['error'] = "Invalid category ID.";
        header("Location: ../views/admin/categories.php");
        exit();
    }

    $result = $categoryController->deleteCategory($id);

    if ($result) {
        $_SESSION['success'] = "Category successfully deleted!";
    } else {
        $_SESSION['error'] = "Cannot delete category. Make sure it has no related products.";
    }
    header("Location: ../views/admin/categories.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/admin/categories.php");
    exit();
}
?>
