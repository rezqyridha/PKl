<?php
require_once '../../config/database.php';
require_once '../../controllers/ProductController.php';
require_once '../../controllers/SupplierController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'karyawan') {
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
    <?php include '../layouts/sidebar_karyawan.php'; ?>
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
                        <form action="../../controllers/restock_actions.php?action=add" method="POST" onsubmit="return validateForm();">
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

                            <div class="form-group">
                                <label for="jumlah_ditambahkan">Jumlah Ditambahkan</label>
                                <input type="number" class="form-control" id="jumlah_ditambahkan" name="jumlah_ditambahkan" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_per_unit">Harga per Unit (Rp)</label>
                                <input type="number" class="form-control" id="harga_per_unit" name="harga_per_unit" required>
                            </div>

                            <div class="form-group">
                                <label for="total_biaya">Total Biaya (Rp)</label>
                                <input type="text" class="form-control bg-light" id="total_biaya" readonly>
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
        const totalBiayaDisplay = document.getElementById('total_biaya');

        function calculateTotalBiaya() {
            const hargaPerUnit = parseFloat(hargaPerUnitInput.value) || 0;
            const jumlahDitambahkan = parseInt(jumlahDitambahkanInput.value) || 0;
            const totalBiaya = hargaPerUnit * jumlahDitambahkan;
            totalBiayaDisplay.value = `Rp ${totalBiaya.toLocaleString('id-ID')}`;
        }

        hargaPerUnitInput.addEventListener('input', calculateTotalBiaya);
        jumlahDitambahkanInput.addEventListener('input', calculateTotalBiaya);

        // Validasi form sebelum submit
        function validateForm() {
            const jumlah = parseInt(jumlahDitambahkanInput.value) || 0;
            const harga = parseFloat(hargaPerUnitInput.value) || 0;

            if (jumlah <= 0 || harga <= 0) {
                Swal.fire({
                    title: "Error!",
                    text: "Jumlah dan harga harus lebih dari 0.",
                    icon: "error"
                });
                return false;
            }

            // Tampilkan SweetAlert Success sebelum submit form
            Swal.fire({
                title: "Sukses!",
                text: "Restock berhasil ditambahkan!",
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = "../employee/restock.php";
            });

        }

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah form submit langsung
            validateForm();
        });
    });
</script>
document.addEventListener('DOMContentLoaded', function() {
const hargaPerUnitInput = document.getElementById('harga_per_unit');
const jumlahDitambahkanInput = document.getElementById('jumlah_ditambahkan');
const totalBiayaDisplay = document.getElementById('total_biaya');

function calculateTotalBiaya() {
const hargaPerUnit = parseFloat(hargaPerUnitInput.value) || 0;
const jumlahDitambahkan = parseInt(jumlahDitambahkanInput.value) || 0;
const totalBiaya = hargaPerUnit * jumlahDitambahkan;

totalBiayaDisplay.value = `