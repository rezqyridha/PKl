<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
require_once '../../controllers/CustomerController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$customerController = new CustomerController($db);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$customer = $customerController->getCustomerById($id);

if (!$customer) {
    header("Location: customers.php?error=not_found");
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
                    <h1 class="h3 text-gray-800">Edit Customer</h1>
                    <a href="customers.php" class="btn btn-secondary">Back</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Customer Details</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/customer_actions.php?action=edit&id=<?= $id ?>" method="POST">
                            <div class="form-group">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" 
                                    value="<?= htmlspecialchars($customer['nama_pelanggan']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kontak">Kontak</label>
                                <input type="text" class="form-control" id="kontak" name="kontak" 
                                    value="<?= htmlspecialchars($customer['kontak']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" required><?= htmlspecialchars($customer['alamat']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="kota">Kota</label>
                                <input type="text" class="form-control" id="kota" name="kota" 
                                    value="<?= htmlspecialchars($customer['kota']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="provinsi">Provinsi</label>
                                <select class="form-control" id="provinsi" name="provinsi" required>
                                    <option value="">Pilih Provinsi</option>
                                    <option value="DKI Jakarta" <?= $customer['provinsi'] == "DKI Jakarta" ? "selected" : "" ?>>DKI Jakarta</option>
                                    <option value="Jawa Barat" <?= $customer['provinsi'] == "Jawa Barat" ? "selected" : "" ?>>Jawa Barat</option>
                                    <option value="Jawa Timur" <?= $customer['provinsi'] == "Jawa Timur" ? "selected" : "" ?>>Jawa Timur</option>
                                    <option value="Sumatera Utara" <?= $customer['provinsi'] == "Sumatera Utara" ? "selected" : "" ?>>Sumatera Utara</option>
                                    <option value="Kalimantan Selatan" <?= $customer['provinsi'] == "Kalimantan Selatan" ? "selected" : "" ?>>Kalimantan Selatan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../layouts/footer.php'; ?>
    </div>
</div>
