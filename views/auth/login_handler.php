<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

session_start();

$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);

$username = htmlspecialchars(trim($_POST['username']));
$password = trim($_POST['password']);

// Validasi username
$user = $userModel->getUserByUsername($username);

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil, simpan session
    $_SESSION['role'] = $user['role'];
    $_SESSION['user_id'] = $user['id_pengguna'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

    // Arahkan sesuai role
    if ($user['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } elseif ($user['role'] === 'karyawan') {
        header("Location: ../employee/dashboard.php");
    }
    exit();
} else {
    // Login gagal
    header("Location: login.php");
    exit();
}
