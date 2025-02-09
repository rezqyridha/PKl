<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';
session_start();

$database = new Database();
$db = $database->getConnection();
$userModel = new UserModel($db);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));
    $email = htmlspecialchars(trim($_POST['email']));
    $kontak = htmlspecialchars(trim($_POST['kontak']));

    // Validasi input
    if (empty($username) || empty($password) || empty($nama_lengkap) || empty($email)) {
        $_SESSION['alert'] = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $_SESSION['alert'] = 'Password harus minimal 6 karakter!';
    } else {
        // Cek apakah username atau email sudah terdaftar
        if ($userModel->checkUsernameOrEmailExists($username, $email)) {
            $_SESSION['alert'] = 'Username atau email sudah digunakan!';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Simpan user dengan role 'karyawan'
            $data = [
                'username' => $username,
                'password' => $hashedPassword,
                'nama_lengkap' => $nama_lengkap,
                'email' => $email,
                'kontak' => $kontak,
                'role' => 'karyawan'
            ];

            if ($userModel->addUser($data)) {
                $_SESSION['alert'] = 'register_success';
                header("Location: ../auth/login.php");
                exit();
            } else {
                $_SESSION['alert'] = 'Terjadi kesalahan saat registrasi!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Karyawan</title>
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Register Karyawan</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h5 text-gray-800">Form Register Karyawan</h1>
            <a href="login.php" class="btn btn-primary">Batal</a>
        </div>

        <?php if (isset($_SESSION['alert'])): ?>
            <div class="alert alert-warning"><?= $_SESSION['alert'];
                                                unset($_SESSION['alert']); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="kontak">Kontak (Opsional)</label>
                <input type="text" class="form-control" id="kontak" name="kontak">
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    </div>
</body>

</html>