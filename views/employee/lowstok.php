<?php
include '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../models/ProductModel.php';

session_start();

// Periksa apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan' || !isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$conn = (new Database())->getConnection(); // Ambil koneksi database
$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);

$productModel = new ProductModel($conn);
$lowStockProducts = $productModel->getLowStockProducts();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Produk Hampir Habis</title>
    <?php include '../layouts/header.php'; ?> <!-- Load header dan CSS -->
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-danger">Daftar Produk Hampir Habis</h3>
            <a href="dashboard.php" class="btn btn-primary">← Kembali ke Dashboard</a>
        </div>

        <table class="table table-bordered" id="lowStockTable">
            <thead class="thead">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($lowStockProducts) > 0) : ?>
                    <?php foreach ($lowStockProducts as $index => $product) : ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($product['nama_produk']); ?></td>
                            <td class="text-danger font-weight-bold"><?= $product['stok']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Tidak ada produk yang hampir habis</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../layouts/footer.php'; ?> <!-- Load footer dan JavaScript -->

    <script>
        $(document).ready(function() {
            $('#lowStockTable').DataTable();
        });
    </script>
</body>

</html>