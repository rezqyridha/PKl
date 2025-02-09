<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../controllers/CustomerController.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/SalesController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$salesController = new SalesController($db);
$productController = new ProductController($db);
$customerController = new CustomerController($db);

// Ambil data produk dan pelanggan
$products = $productController->showAllProducts();
$customers = $customerController->getAllCustomers();

// Validasi ID penjualan
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: sales.php?error=invalid_request");
    exit();
}

$sale = $salesController->getSaleById($id);
if (!$sale) {
    header("Location: sales.php?error=not_found");
    exit();
}
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Penjualan</h1>
                    <a href="sales.php" class="btn btn-secondary">Batal</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Edit Penjualan</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/sales_actions.php?action=edit&id=<?= $sale['id_penjualan']; ?>" method="POST">
                            <!-- Produk -->
                            <div class="form-group">
                                <label for="id_produk">Produk</label>
                                <select class="form-control" id="id_produk" name="id_produk" required>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id_produk']; ?>"
                                            data-price="<?= $product['harga']; ?>"
                                            <?= $sale['id_produk'] == $product['id_produk'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($product['nama_produk']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Pelanggan -->
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan</label>
                                <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['id_pelanggan']; ?>" <?= $sale['id_pelanggan'] == $customer['id_pelanggan'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($customer['nama_pelanggan']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tanggal Penjualan -->
                            <div class="form-group">
                                <label for="tanggal_penjualan">Tanggal Penjualan</label>
                                <input type="date" class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" value="<?= htmlspecialchars($sale['tanggal_penjualan']); ?>" required>
                            </div>

                            <!-- Jumlah Terjual -->
                            <div class="form-group">
                                <label for="jumlah_terjual">Jumlah Terjual</label>
                                <input type="number" class="form-control" id="jumlah_terjual" name="jumlah_terjual" value="<?= htmlspecialchars($sale['jumlah_terjual']); ?>" required>
                            </div>

                            <!-- Total Harga (Otomatis) -->
                            <div class="form-group">
                                <label for="total_harga">Total Harga (Rp)</label>
                                <input type="hidden" id="total_harga" name="total_harga" value="<?= $sale['total_harga']; ?>">
                                <span id="total_harga_display" class="form-control bg-light"><?= 'Rp ' . number_format($sale['total_harga'], 0, ',', '.'); ?></span>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectProduct = document.getElementById('id_produk');
        const inputJumlahTerjual = document.getElementById('jumlah_terjual');
        const inputTotalHarga = document.getElementById('total_harga');
        const displayTotalHarga = document.getElementById('total_harga_display');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function calculateTotal() {
            const selectedOption = selectProduct.options[selectProduct.selectedIndex];
            const hargaProduk = selectedOption.getAttribute('data-price');
            const jumlahTerjual = inputJumlahTerjual.value;

            if (hargaProduk && jumlahTerjual) {
                const totalHarga = parseFloat(hargaProduk) * parseInt(jumlahTerjual);
                inputTotalHarga.value = totalHarga; // Nilai untuk input hidden
                displayTotalHarga.textContent = formatRupiah(totalHarga); // Tampilkan nilai yang diformat
            } else {
                inputTotalHarga.value = '';
                displayTotalHarga.textContent = 'Rp 0';
            }
        }

        selectProduct.addEventListener('change', calculateTotal);
        inputJumlahTerjual.addEventListener('input', calculateTotal);
    });
</script>