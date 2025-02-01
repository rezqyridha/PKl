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

if (!$user) {
    echo "User not found!";
    exit();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : null;
    $newEmail = isset($_POST['email']) ? trim($_POST['email']) : null;

    // Validasi data: Cek apakah salah satu field diisi
    if (empty($newName) && empty($newEmail)) {
        $error = "Anda harus mengisi setidaknya salah satu field (Nama atau Email)!";
    } else {
        // Update data di database
        $updateSuccess = $userModel->updateUserPartial($_SESSION['user_id'], $newName, $newEmail);
        if ($updateSuccess) {
            $success = "Profil berhasil diperbarui!";
            // Refresh data pengguna
            header("Location: profile.php?success=1");
            exit();
        } else {
            $error = "Terjadi kesalahan saat memperbarui profil.";
        }
    }
}
?>

<?php include '../layouts/header.php'; ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Profile</h1>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>
