<?php
require_once '../../config/database.php';
require_once '../../controllers/SatuanController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$satuanController = new SatuanController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);
?>

<div id="wrapper">
    <?php $page = 'satuan';
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Tambah Satuan Baru</h1>
                    <a href="satuan.php" class="btn btn-secondary">Kembali</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Satuan</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/satuan_actions.php?action=add" method="POST">
                            <div class="form-group">
                                <label for="nama_satuan">Nama Satuan</label>
                                <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Masukkan nama satuan" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi satuan (opsional)"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Satuan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>