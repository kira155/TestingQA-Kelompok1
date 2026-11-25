<?php
// includes/auth.php - Middleware untuk cek login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    // Simpan URL yang dituju
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: /cleaning/auth/login.php');
    exit;
}

// Cek session timeout (optional, 8 jam)
$timeout = 28800; // 8 jam dalam detik
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $timeout)) {
    session_destroy();
    header('Location: /cleaning/auth/login.php?timeout=1');
    exit;
}

// Update last activity
$_SESSION['last_activity'] = time();

// Helper functions
function isAdmin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function isStaff() {
    return ($_SESSION['role'] ?? '') === 'staff';
}

function getCurrentUser() {
    return [
        'id' => $_SESSION['id_user'] ?? null,
        'username' => $_SESSION['username'] ?? 'Guest',
        'role' => $_SESSION['role'] ?? 'guest'
    ];
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /cleaning/pages/dashboard.php');
        exit;
    }
}
?>