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

$restockController = new RestockController($db);
$productController = new ProductController($db);
$supplierController = new SupplierController($db);

// Ambil data produk dan supplier
$products = $productController->getAllProducts();
$suppliers = $supplierController->getAllSuppliers();

// Validasi ID restock
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: restock.php?error=invalid_request");
    exit();
}

$restock = $restockController->getRestockById($id);
if (!$restock) {
    header("Location: restock.php?error=not_found");
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
                    <h1 class="h3 text-gray-800">Edit Restock</h1>
                    <a href="restock.php" class="btn btn-secondary">Kembali</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Restock</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/restock_actions.php?action=edit&id=<?= $restock['id_restock'] ?? ''; ?>" method="POST">
                            <div class="form-group">
                                <label for="id_produk">Produk</label>
                                <select class="form-control" id="id_produk" name="id_produk" required>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id_produk']; ?>" <?= ($restock['id_produk'] ?? '') == $product['id_produk'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($product['nama_produk'] ?? ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_supplier">Supplier</label>
                                <select class="form-control" id="id_supplier" name="id_supplier" required>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['id_supplier']; ?>" <?= ($restock['id_supplier'] ?? '') == $supplier['id_supplier'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($supplier['nama'] ?? ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_restock">Tanggal Restock</label>
                                <input type="datetime-local" class="form-control" id="tanggal_restock" name="tanggal_restock" value="<?= htmlspecialchars($restock['tanggal_restock'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="jumlah_ditambahkan">Jumlah Ditambahkan</label>
                                <input type="number" class="form-control" id="jumlah_ditambahkan" name="jumlah_ditambahkan" value="<?= htmlspecialchars($restock['jumlah_ditambahkan'] ?? '0'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_per_unit">Harga per Unit (Rp)</label>
                                <input type="number" class="form-control" id="harga_per_unit" name="harga_per_unit"
                                    value="<?= htmlspecialchars($restock['harga_per_unit']); ?>" required>
                            </div>


                            <div class="form-group">
                                <label for="total_biaya">Total Biaya (Rp)</label>
                                <input type="hidden" id="total_biaya" name="total_biaya" value="<?= $restock['total_biaya'] ?? '0'; ?>">
                                <span id="total_biaya_display" class="form-control bg-light" readonly><?= 'Rp ' . number_format($restock['total_biaya'] ?? 0, 0, ',', '.'); ?></span>
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
        const jumlahInput = document.getElementById('jumlah_ditambahkan');
        const hargaInput = document.getElementById('harga_beli');
        const totalInput = document.getElementById('total_biaya');
        const totalDisplay = document.getElementById('total_biaya_display');

        function calculateTotal() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            const total = jumlah * harga;

            totalInput.value = total;
            totalDisplay.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        jumlahInput.addEventListener('input', calculateTotal);
        hargaInput.addEventListener('input', calculateTotal);
    });
</script>