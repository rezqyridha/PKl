<?php

/**
 * Entry point aplikasi
 * Mengarahkan pengguna ke halaman yang sesuai berdasarkan status login mereka.
 * Menggunakan session aman dan header redirection yang divalidasi.
 */

// Mulai session dengan pengaturan aman
session_start([
    'cookie_httponly' => true, // Mencegah akses JavaScript ke session cookie
    'cookie_secure'   => isset($_SERVER['HTTPS']), // Gunakan cookie hanya pada koneksi HTTPS
    'use_strict_mode' => true // Mencegah penggunaan session ID yang tidak valid
]);

// Regenerasi session ID untuk mencegah session hijacking
session_regenerate_id(true);

// Middleware sederhana untuk mengecek status login
function isUserLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Fungsi untuk redirection dengan validasi URL
function safeRedirect($url)
{
    $safeUrl = filter_var($url, FILTER_SANITIZE_URL);
    header("Location: $safeUrl");
    exit();
}

// Logika utama: cek status login dan arahkan ke halaman yang sesuai
if (isUserLoggedIn()) {
    safeRedirect('views/admin/dashboard.php');
} else {
    safeRedirect('views/auth/login.php');
}
