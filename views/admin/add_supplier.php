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


?>

<div id="wrapper">
    <?php $page = 'suppliers';
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Tambah Supplier Baru</h1>
                    <a href="suppliers.php" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Supplier Baru</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/supplier_actions.php?action=add" method="POST">
                            <div class="form-group">
                                <label for="nama_supplier">Nama Supplier</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama supplier" required>
                            </div>
                            <div class="form-group">
                                <label for="kontak_supplier">Kontak</label>
                                <input type="text" class="form-control" id="kontak" name="kontak" placeholder="Masukkan kontak supplier">
                            </div>
                            <div class="form-group">
                                <label for="alamat_supplier">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat supplier"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Supplier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>