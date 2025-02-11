<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/UserModel.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();
$userModel = new UserModel($db);

// Ambil data dari form login
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($username) && !empty($password)) {
    // Cari user di database
    $user = $userModel->getUserByUsername($username);

    if ($user && is_array($user) && isset($user['id_pengguna'])) {  // Periksa apakah user ditemukan
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] === 'karyawan') {
                header("Location: ../employee/dashboard.php");
            } elseif ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                $_SESSION['alert'] = 'Role tidak dikenali.';
                header("Location: login.php");
            }
        } else {
            $_SESSION['alert'] = 'Password salah.';
            header("Location: login.php");
        }
    } else {
        $_SESSION['alert'] = 'Username tidak ditemukan.';
        header("Location: login.php");
    }
} else {
    $_SESSION['alert'] = 'Harap isi username dan password.';
    header("Location: login.php");
}
exit();
