<?php
require_once '../../config/database.php';
require_once '../../controllers/SupplierController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$supplierController = new SupplierController($db);

// Ambil ID Supplier dari URL dengan Validasi**
$id_supplier = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_supplier === 0) {
    header("Location: suppliers.php?error=not_found");
    exit();
}

// Ambil Data Supplier dari Database**
$supplier = $supplierController->getSupplierById($id_supplier);
if (!$supplier) {
    header("Location: suppliers.php?error=not_found");
    exit();
}

//  Pastikan Semua Nilai Tidak NULL
$nama_supplier = $supplier['nama'] ?? '';
$kontak_supplier = $supplier['kontak'] ?? '';
$alamat_supplier = $supplier['alamat'] ?? '';

?>

<div id="wrapper">
    <?php $page = 'suppliers';
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Supplier</h1>
                    <a href="suppliers.php" class="btn btn-secondary">Batal</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Edit Supplier</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/supplier_actions.php?action=edit&id=<?= $id_supplier; ?>" method="POST">
                            <div class="form-group">
                                <label for="nama">Nama Supplier</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="<?= htmlspecialchars($nama_supplier, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="kontak">Kontak</small></label>
                                <input type="text" class="form-control" id="kontak" name="kontak"
                                    value="<?= htmlspecialchars($kontak_supplier, ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="Opsional untuk diisi">
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</small></label>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Opsional untuk diisi"><?= htmlspecialchars($alamat_supplier, ENT_QUOTES, 'UTF-8'); ?></textarea>
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