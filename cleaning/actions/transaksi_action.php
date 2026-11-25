<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/csrf.php';
cleaning_require_post_csrf();

if (!function_exists('isAdmin')) {
    function isAdmin(): bool {
        return ($_SESSION['role'] ?? '') === 'admin';
    }
}

$action = $_POST['action'] ?? '';

// INPUT TRANSAKSI (dengan harga_satuan dan total_harga)
if ($action === 'input_transaksi') {
    $id_barang = (int)($_POST['id_barang'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $notes = trim($_POST['notes'] ?? '');
    $jenis = $_POST['jenis'] ?? '';
    
    // Validasi jenis transaksi
    if (!in_array($jenis, ['masuk', 'keluar'])) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Jenis transaksi tidak valid.'];
        header('Location: /cleaning/pages/dashboard.php');
        exit;
    }
    
    $redirect = $jenis === 'masuk' ? '/cleaning/pages/transaksi_masuk.php' : '/cleaning/pages/transaksi_keluar.php';
    
    // Validasi input
    if (!$id_barang || $qty <= 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data tidak lengkap atau tidak valid.'];
        header("Location: $redirect");
        exit;
    }
    
    // Lock saldo barang: dua transaksi bersamaan tidak boleh sama-sama memakai stok yang sama.
    $conn->begin_transaction();
    try {
        $q = $conn->prepare('SELECT harga_barang, stok_sekarang FROM barang WHERE id_barang=? FOR UPDATE');
        $q->bind_param('i', $id_barang); $q->execute();
        $barang = $q->get_result()->fetch_assoc(); $q->close();
        if (!$barang) { throw new RuntimeException('Barang tidak ditemukan.'); }
        if ($jenis === 'keluar' && $qty > (int)$barang['stok_sekarang']) {
            throw new RuntimeException('Stok tidak mencukupi!');
        }
        $harga_satuan = (int)$barang['harga_barang'];
        $total_harga = $qty * $harga_satuan;
        $stmt = $conn->prepare("INSERT INTO transaksi
                (id_barang, id_user, jenis, qty, harga_satuan, total_harga, tanggal, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $id_user = (int)$_SESSION['id_user'];
        $stmt->bind_param('iisiiiss', $id_barang, $id_user, $jenis, $qty, $harga_satuan, $total_harga, $tanggal, $notes);
        $stmt->execute(); $stmt->close();
        // Trigger after_transaksi_insert memperbarui cached stok_sekarang.
        $conn->commit();
        $_SESSION['flash'] = ['type' => 'success', 'msg' => $jenis === 'masuk'
            ? 'Barang masuk berhasil dicatat.' : 'Barang keluar berhasil dicatat.'];
    } catch (Throwable $e) {
        $conn->rollback();
        error_log('Transaksi gagal: ' . $e->getMessage());
        $message = $e instanceof RuntimeException ? $e->getMessage() : 'Gagal menyimpan transaksi.';
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => $message];
    }

    header("Location: $redirect");
    exit;
}

// HAPUS TRANSAKSI (admin only)
if ($action === 'hapus_transaksi') {
    if (!isAdmin()) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Akses ditolak.'];
        header('Location: /cleaning/pages/dashboard.php');
        exit;
    }
    
    $id_transaksi = (int)($_POST['id_transaksi'] ?? 0);
    $kembali = $_POST['kembali'] ?? '/cleaning/pages/dashboard.php';
    $allowedBack = ['/cleaning/pages/dashboard.php','/cleaning/pages/transaksi_masuk.php','/cleaning/pages/transaksi_keluar.php','/cleaning/pages/laporan.php'];
    if (!in_array($kembali, $allowedBack, true)) $kembali='/cleaning/pages/dashboard.php';
    
    // Ambil id_barang sebelum hapus
    $barang = $conn->query("SELECT id_barang FROM transaksi WHERE id_transaksi = $id_transaksi")->fetch_assoc();
    
    if ($barang && $conn->query("DELETE FROM transaksi WHERE id_transaksi = $id_transaksi")) {
        // after_transaksi_delete memperbarui cached saldo secara otomatis.
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Transaksi berhasil dihapus.'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal menghapus transaksi.'];
    }
    
    header("Location: $kembali");
    exit;
}

header('Location: /cleaning/pages/dashboard.php');
exit;
?>