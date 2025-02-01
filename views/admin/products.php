<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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
?>

<?php
// Cek apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tampilkan alert berdasarkan session
if (isset($_SESSION['alert'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {";

    if ($_SESSION['alert'] == 'added') {
        echo "Swal.fire({
            title: 'Good job!',
            text: 'Product successfully added!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'deleted') {
        echo "Swal.fire({
            title: 'Deleted!',
            text: 'Product successfully deleted!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'updated') {
        echo "Swal.fire({
            title: 'Updated!',
            text: 'Product successfully updated!',
            icon: 'success'
        });";
    } elseif ($_SESSION['alert'] == 'add_failed') {
        echo "Swal.fire({
            title: 'Failed!',
            text: 'Failed to add product!',
            icon: 'error'
        });";
    } elseif ($_SESSION['alert'] == 'delete_failed') {
        echo "Swal.fire({
            title: 'Failed!',
            text: 'Failed to delete product!',
            icon: 'error'
        });";
    } elseif ($_SESSION['alert'] == 'update_failed') {
        echo "Swal.fire({
            title: 'Failed!',
            text: 'Failed to update product!',
            icon: 'error'
        });";
    }

    echo "});</script>";
    unset($_SESSION['alert']); // Hapus session alert setelah ditampilkan
}
?>







<div id="wrapper">
    <!-- Sidebar -->
    <?php include '../layouts/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <?php include '../layouts/header.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Manage Products</h1>
                    <a href="add_product.php" class="btn btn-primary">Add New Product</a>
                </div>

                <!-- Products Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php $counter = 1; // Inisialisasi nomor urut ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                        <td><?= $counter++; ?></td> <!-- Tampilkan nomor urut -->
                                            <td><?= htmlspecialchars($product['nama_produk']); ?></td>
                                            <td><?= htmlspecialchars($product['deskripsi']); ?></td>
                                            <td>
                                                <?php
                                                // Mengambil nama kategori berdasarkan ID kategori
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
                <td>Rp <?= number_format($product['harga']); ?></td>
                <td><?= htmlspecialchars($product['stok']); ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id_produk']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $product['id_produk']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="text-center">No products available.</td>
        </tr>
    <?php endif; ?>
</tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php include '../layouts/footer.php'; ?>
    </div>
    <!-- End Content Wrapper -->
</div>
<!-- End Wrapper -->
