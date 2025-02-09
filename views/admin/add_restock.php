<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../controllers/SupplierController.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/RestockController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Inisialisasi controller
$productController = new ProductController($db);
$supplierController = new SupplierController($db);

// Ambil data produk dan supplier
$products = $productController->showAllProducts();
$suppliers = $supplierController->getAllSuppliers();

?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Tambah Restock Baru</h1>
                    <a href="restock.php" class="btn btn-secondary">Batal</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Restock Baru</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/restock_actions.php?action=add" method="POST">
                            <div class="form-group">
                                <label for="id_produk">Produk</label>
                                <select class="form-control" id="id_produk" name="id_produk" required>
                                    <option value="">-- Pilih Produk --</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id_produk']; ?>"><?= htmlspecialchars($product['nama_produk']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_supplier">Supplier</label>
                                <select class="form-control" id="id_supplier" name="id_supplier" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['id_supplier']; ?>"><?= htmlspecialchars($supplier['nama']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_restock">Tanggal dan Waktu Restock</label>
                                <input type="datetime-local" class="form-control" id="tanggal_restock" name="tanggal_restock" required>
                            </div>


                            <!-- Harga per Unit -->
                            <div class="form-group">
                                <label for="harga_per_unit">Harga per Unit (Rp)</label>
                                <input type="number" class="form-control" id="harga_per_unit" name="harga_per_unit" required>
                            </div>

                            <!-- Jumlah Ditambahkan -->
                            <div class="form-group">
                                <label for="jumlah_ditambahkan">Jumlah Ditambahkan</label>
                                <input type="number" class="form-control" id="jumlah_ditambahkan" name="jumlah_ditambahkan" required>
                            </div>

                            <!-- Total Biaya (readonly, dihitung otomatis) -->
                            <div class="form-group">
                                <label for="total_biaya_display">Total Biaya (Rp)</label>
                                <input type="text" class="form-control bg-light" id="total_biaya_display" readonly>
                            </div>


                            <button type="submit" class="btn btn-primary">Simpan Restock</button>
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
        const hargaPerUnitInput = document.getElementById('harga_per_unit');
        const jumlahDitambahkanInput = document.getElementById('jumlah_ditambahkan');
        const totalBiayaDisplay = document.getElementById('total_biaya_display');

        function calculateTotalBiaya() {
            const hargaPerUnit = parseFloat(hargaPerUnitInput.value) || 0;
            const jumlahDitambahkan = parseInt(jumlahDitambahkanInput.value) || 0;
            const totalBiaya = hargaPerUnit * jumlahDitambahkan;

            totalBiayaDisplay.value = `Rp ${totalBiaya.toLocaleString('id-ID')}`;
        }

        hargaPerUnitInput.addEventListener('input', calculateTotalBiaya);
        jumlahDitambahkanInput.addEventListener('input', calculateTotalBiaya);
    });
</script>