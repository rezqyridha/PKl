<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$productController = new ProductController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: products.php?error=invalid_request");
    exit();
}

$product = $productController->getProductById($id);
if (!$product) {
    header("Location: products.php?error=not_found");
    exit();
}

// Get all categories
$categories = $productController->getAllCategories();
?>
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Product</h1>
                    <a href="products.php" class="btn btn-secondary">Back</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
                    </div>
                    <div class="card-body">
                    <form action="../../controllers/product_actions.php?action=edit&id=<?= $product['id_produk']; ?>" method="POST">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['nama_produk']); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($product['deskripsi']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <select class="form-control" id="category" name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id_kategori']; ?>" <?= $product['id_kategori'] == $category['id_kategori'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($category['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['harga']); ?>" required>
    </div>
    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stok']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>
