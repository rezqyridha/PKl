<?php
require_once '../../config/database.php';
require_once '../../controllers/CategoryController.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/ProductController.php';  // Pastikan ProductController di-include

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// User Model
$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

// Category Controller
$categoryController = new CategoryController($db);
$categories = $categoryController->getAllCategories();

// Product Controller - Ambil data satuan
$productController = new ProductController($db);  // Inisialisasi ProductController
$satuan = $productController->getAllSatuan();  // Ambil data satuan
?>

<div id="wrapper">
    <?php
    $page = 'products';
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Tambah Produk Baru</h1>
                    <a href="products.php" class="btn btn-secondary">Batal</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Produk Baru</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/product_actions.php?action=add" method="POST">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama produk" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Masukkan deskripsi produk" required></textarea>
                            </div>

                            <!-- Kategori Produk -->
                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id_kategori']); ?>">
                                            <?= htmlspecialchars($category['nama_kategori']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Satuan Produk -->
                            <div class="form-group">
                                <label for="satuan">Satuan Produk</label>
                                <select class="form-control" id="satuan" name="satuan" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <?php foreach ($satuan as $satuanItem): ?>
                                        <option value="<?= $satuanItem['id_satuan']; ?>">
                                            <?= htmlspecialchars($satuanItem['nama_satuan']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Masukkan harga produk (Rp)" required>
                            </div>

                            <div class="form-group">
                                <label for="stock">Stok</label>
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="Masukkan jumlah stok tersedia" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>