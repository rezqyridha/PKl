<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../models/UserModel.php';

session_start();

// Periksa apakah user adalah karyawan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: ../auth/login.php");
    exit();
}

// Koneksi ke database dan mengambil data produk
$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$productController = new ProductController($db);
$products = $productController->showAllProducts(); // Ambil semua produk
$categories = $productController->getAllCategories();
$satuan = $productController->getAllSatuan();
?>

<div id="wrapper">
    <?php
    $page = 'products';
    include '../layouts/sidebar_karyawan.php';
    ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <?php if (isset($_SESSION['alert'])): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: "Notifikasi",
                                    text: "<?= $_SESSION['alert']; ?>",
                                    icon: "info"
                                });
                            });
                        </script>
                        <?php unset($_SESSION['alert']); ?>
                    <?php endif; ?>

                    <h1 class="h3 text-gray-800">Daftar Produk</h1>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Produk Tersedia</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($products)): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($product['nama_produk']); ?></td>
                                                <td><?= htmlspecialchars($product['deskripsi']); ?></td>
                                                <td>
                                                    <?php
                                                    $categoryName = '';
                                                    foreach ($categories as $category) {
                                                        if ($category['id_kategori'] == $product['id_kategori']) {
                                                            $categoryName = $category['nama_kategori'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($categoryName);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $satuanName = '';
                                                    foreach ($satuan as $satuanItem) {
                                                        if ($satuanItem['id_satuan'] == $product['id_satuan']) {
                                                            $satuanName = $satuanItem['nama_satuan'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($satuanName);
                                                    ?>
                                                </td>
                                                <td>Rp <?= number_format($product['harga']); ?></td>
                                                <td><?= ($product['stok'] === null || $product['stok'] === 0) ? '0' : htmlspecialchars($product['stok']); ?></td>
                                                <td>
                                                    <a href="edit_product.php?id=<?= $product['id_produk']; ?>" class="btn btn-warning btn-circle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada produk yang tersedia.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../layouts/footer.php'; ?>
    </div>
</div>