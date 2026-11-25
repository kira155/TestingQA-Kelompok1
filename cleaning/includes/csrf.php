<?php
/** CSRF token untuk form mutasi data. Gunakan setelah sesi dimulai. */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function cleaning_csrf_token(): string {
    if (empty($_SESSION['cleaning_csrf'])) {
        $_SESSION['cleaning_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['cleaning_csrf'];
}
function cleaning_csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(cleaning_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}
function cleaning_require_post_csrf(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        exit('Metode tidak diizinkan.');
    }
    $value = $_POST['csrf_token'] ?? null;
    if (!is_string($value) || !hash_equals(cleaning_csrf_token(), $value)) {
        http_response_code(403);
        exit('Permintaan ditolak: token keamanan tidak valid. Segarkan halaman dan coba lagi.');
    }
}
