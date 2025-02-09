<?php
require_once '../../config/database.php';
require_once '../../controllers/CategoryController.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$categoryController = new CategoryController($db);

$userModel = new UserModel($db);
$user = $userModel->getUserById($_SESSION['user_id']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: categories.php?error=invalid_request");
    exit();
}

$category = $categoryController->getCategoryById($id);
if (!$category) {
    header("Location: categories.php?error=not_found");
    exit();
}
?>

<div id="wrapper">
    <?php $page = 'categories';
    include '../layouts/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Kategori</h1>
                    <a href="categories.php" class="btn btn-secondary">Batal</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Edit Kategori</h6>
                    </div>
                    <div class="card-body">
                        <form action="../../controllers/category_actions.php?action=edit&id=<?= $category['id_kategori']; ?>" method="POST">
                            <div class="form-group">
                                <label for="nama_kategori">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                    value="<?= htmlspecialchars($category['nama_kategori']); ?>" required>
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