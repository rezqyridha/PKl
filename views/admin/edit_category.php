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


// Ambil ID kategori dari parameter URL
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($categoryId === 0) {
    $_SESSION['error_message'] = "Invalid category ID!";
    header("Location: categories.php");
    exit();
}

// Ambil data kategori berdasarkan ID
$category = $categoryController->getCategoryById($categoryId);

if (!$category) {
    $_SESSION['error_message'] = "Category not found!";
    header("Location: categories.php");
    exit();
}

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (empty($name)) {
        $_SESSION['error_message'] = "Category name cannot be empty!";
    } elseif ($categoryController->editCategory($categoryId, $name)) {
        $_SESSION['success_message'] = "Category successfully updated!";
        header("Location: categories.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update category. Category name may already exist.";
    }
}
?>

<div id="wrapper">
    <?php include '../layouts/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../layouts/header.php'; ?>

            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Edit Category</h1>
                    <a href="categories.php" class="btn btn-secondary">Back</a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Category Details</h6>
                    </div>
                    <div class="card-body">
                        <!-- Tampilkan Alert Jika Ada -->
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <?= htmlspecialchars($_SESSION['success_message']); ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($_SESSION['error_message']); ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category['nama_kategori']); ?>" required>
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
