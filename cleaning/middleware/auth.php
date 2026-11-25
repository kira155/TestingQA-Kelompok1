<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Pastikan user sudah login.
 * Jika belum, redirect ke login.
 */
function requireLogin() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: /cleaning/auth/login.php");
        exit;
    }
}

/**
 * Pastikan user adalah admin.
 * Jika bukan, tampilkan error 403.
 */
function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        die("
            <div style='font-family:sans-serif;text-align:center;margin-top:80px;'>
                <h2>403 - Akses Ditolak</h2>
                <p>Halaman ini hanya bisa diakses oleh admin.</p>
                <a href='/cleaning/pages/dashboard.php'>Kembali ke Dashboard</a>
            </div>
        ");
    }
}

/**
 * Cek apakah user adalah admin (return bool).
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>