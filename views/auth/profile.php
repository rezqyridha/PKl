<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userModel = new UserModel($db);

$user = $userModel->getUserById($_SESSION['user_id']);

// Tentukan URL dashboard sesuai dengan role
$dashboardUrl = ($user['role'] === 'karyawan') ? '../employee/dashboard.php' : '../admin/dashboard.php';
?>

<?php include '../layouts/header.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profile</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></p>
            <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($user['nama_lengkap']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?= ucfirst($user['role']); ?></p>

            <!-- Tombol Edit -->
            <a href="edit_profile.php" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
            <a href="<?= $dashboardUrl; ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success">Profil berhasil diperbarui!</div>
<?php endif; ?>