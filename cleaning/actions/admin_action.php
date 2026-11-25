<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /cleaning/auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/csrf.php';
cleaning_require_post_csrf();

$action = $_POST['action'] ?? '';

// Audit hanya baca; transaksi berulang tidak otomatis dianggap duplikat.
// Penghapusan massal berisiko menghilangkan riwayat pemakaian sah.
if ($action === 'bersihkan_data_kotor') {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'msg' => 'Pembersihan otomatis dinonaktifkan untuk menjaga riwayat. Audit terlebih dahulu melalui laporan dan backup database.'
    ];
}
header('Location: /cleaning/pages/laporan.php');
exit;
