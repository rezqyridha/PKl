<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../controllers/CustomerController.php';
require_once '../../models/UserModel.php';

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

// Product Controller
$productController = new ProductController($db);
$products = $productController->showAllProducts();

// Customer Controller
$customerController = new CustomerController($db);
$customers = $customerController->getAllCustomers();
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Tambah Penjualan Baru</h1>
                    <a href="sales.php" class="btn btn-secondary">Batal</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Penjualan Baru</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/sales_actions.php?action=add" method="POST">
                            <!-- Produk -->
                            <div class="form-group">
                                <label for="id_produk">Produk</label>
                                <select class="form-control" id="id_produk" name="id_produk" required>
                                    <option value="">-- Pilih Produk --</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= htmlspecialchars($product['id_produk']); ?>" data-price="<?= $product['harga']; ?>">
                                            <?= htmlspecialchars($product['nama_produk']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Pelanggan -->
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan</label>
                                <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= htmlspecialchars($customer['id_pelanggan']); ?>">
                                            <?= htmlspecialchars($customer['nama_pelanggan']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tanggal Penjualan -->
                            <div class="form-group">
                                <label for="tanggal_penjualan">Tanggal Penjualan</label>
                                <input type="date" class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" required>
                            </div>

                            <!-- Jumlah Terjual -->
                            <div class="form-group">
                                <label for="jumlah_terjual">Jumlah Terjual</label>
                                <input type="number" class="form-control" id="jumlah_terjual" name="jumlah_terjual" placeholder="Masukkan jumlah terjual" required>
                            </div>

                            <!-- Total Harga -->
                            <div class="form-group">
                                <label for="total_harga">Total Harga (Rp)</label>
                                <!-- Input hidden untuk menyimpan nilai numerik -->
                                <input type="hidden" id="total_harga" name="total_harga">
                                <!-- Span untuk menampilkan total harga yang diformat -->
                                <span id="total_harga_display" class="form-control bg-light" readonly></span>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
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
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0 // Tidak menampilkan angka desimal
            }).format(angka);
            return formatted;
        }

        function calculateTotal() {
            const selectedOption = selectProduct.options[selectProduct.selectedIndex];
            const hargaProduk = selectedOption.getAttribute('data-price');
            const jumlahTerjual = inputJumlahTerjual.value;

            if (hargaProduk && jumlahTerjual) {
                const totalHarga = parseFloat(hargaProduk) * parseInt(jumlahTerjual);
                inputTotalHarga.value = totalHarga; // Simpan nilai numerik
                displayTotalHarga.textContent = formatRupiah(totalHarga); // Tampilkan dalam format Rupiah tanpa desimal
            } else {
                inputTotalHarga.value = '';
                displayTotalHarga.textContent = 'Rp 0';
            }
        }

        selectProduct.addEventListener('change', calculateTotal);
        inputJumlahTerjual.addEventListener('input', calculateTotal);
    });
</script>