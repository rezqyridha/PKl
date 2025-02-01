<?php
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

session_start();

$database = new Database();
$db = $database->getConnection();

$userModel = new UserModel($db);

$username = $_POST['username'];
$password = $_POST['password'];

// Validasi username
$user = $userModel->getUserByUsername($username);

// Debugging validasi password
// var_dump($user, $password, $user['password'], password_verify($password, $user['password']));
// exit;

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil, simpan session
    $_SESSION['role'] = $user['role']; // 'admin' atau 'karyawan'
    $_SESSION['user_id'] = $user['id_pengguna']; // ID pengguna
    header("Location: ../../views/admin/dashboard.php");
    exit();
} else {
    // Login gagal
    header("Location: login.php?error=invalid");
    exit();
}
?>
