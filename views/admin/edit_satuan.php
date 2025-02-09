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

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: satuan.php?error=invalid_request");
    exit();
}

$satuan = $satuanController->getSatuanById($id);
if (!$satuan) {
    header("Location: satuan.php?error=not_found");
    exit();
}
?>

<div id="wrapper">
    <?php $page = 'satuan';
    include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Satuan</h1>
                    <a href="satuan.php" class="btn btn-secondary">Kembali</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Satuan</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/satuan_actions.php?action=edit&id=<?= htmlspecialchars($satuan['id_satuan'] ?? ''); ?>" method="POST">
                            <div class="form-group">
                                <label for="nama_satuan">Nama Satuan</label>
                                <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" value="<?= htmlspecialchars($satuan['nama_satuan'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi"><?= htmlspecialchars($satuan['deskripsi'] ?? ''); ?></textarea>
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