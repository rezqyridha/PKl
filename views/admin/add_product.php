<?php
require_once '../../config/database.php';
require_once '../../controllers/CategoryController.php';
require_once '../../models/UserModel.php';
require_once '../../models/ProductModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$categoryController = new CategoryController($db);
$categories = $categoryController->getAllCategories(); // Ambil semua kategori
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Add New Product</h1>
                    <a href="products.php" class="btn btn-secondary">Back</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">New Product Details</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/product_actions.php?action=add" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter product description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id_kategori']); ?>">
                                            <?= htmlspecialchars($category['nama_kategori']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price" required>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter product stock" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>