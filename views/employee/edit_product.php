<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';

session_start();

// Cek apakah user adalah karyawan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$productController = new ProductController($db);

$id_produk = $_GET['id'] ?? 0;
$product = $productController->getProductById($id_produk);

if (!$product) {
    $_SESSION['alert'] = 'product_not_found';
    header("Location: products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStock = $_POST['stok'] ?? 0;

    if (is_numeric($newStock) && $newStock >= 0) {
        // Validasi berhasil, lakukan update stok
        $result = $productController->updateStock($id_produk, $newStock);

        $_SESSION['alert'] = $result ? 'Stok diperbarui' : 'update_failed';
    } else {
        $_SESSION['alert'] = 'invalid_stock';
    }

    header("Location: products.php");
    exit();
}
?>

<div id="wrapper">
    <?php include '../layouts/sidebar_karyawan.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Edit Stok Produk</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Stok: <?= htmlspecialchars($product['nama_produk']); ?></h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?= $product['stok']; ?>" min="0" required>
                                <?php if (isset($_SESSION['alert']) && $_SESSION['alert'] == 'invalid_stock'): ?>
                                    <small class="text-danger">Stok harus berupa angka yang valid dan tidak boleh negatif.</small>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="products.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../layouts/footer.php'; ?>
    </div>
</div>