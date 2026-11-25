<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/auth.php';
requireAdmin();
require_once __DIR__ . '/../includes/csrf.php';
cleaning_require_post_csrf();

function redirectWith(string $url, string $type, string $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    header("Location: $url");
    exit;
}

$action   = $_POST['action'] ?? '';
$redirect = '/cleaning/pages/users.php';

// ── TAMBAH USER ───────────────────────────────────────────────────────────────
if ($action === 'tambah_user') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    if (!$username || !$password || !in_array($role, ['admin','staff'])) {
        redirectWith($redirect, 'danger', 'Semua field wajib diisi.');
    }
    if (strlen($password) < 6) {
        redirectWith($redirect, 'danger', 'Password minimal 6 karakter.');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hash, $role);

    if ($stmt->execute()) {
        redirectWith($redirect, 'success', 'User berhasil ditambahkan.');
    } else {
        // Duplicate username
        $msg = str_contains($conn->error, 'Duplicate')
            ? 'Username sudah digunakan.'
            : 'Gagal menambahkan user: ' . $conn->error;
        redirectWith($redirect, 'danger', $msg);
    }
    $stmt->close();
}

// ── RESET PASSWORD ────────────────────────────────────────────────────────────
if ($action === 'reset_password') {
    $id       = (int)($_POST['id_user'] ?? 0);
    $password = $_POST['password'] ?? '';

    if (!$id || strlen($password) < 6) {
        redirectWith($redirect, 'danger', 'ID tidak valid atau password terlalu pendek (min 6 karakter).');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id_user=?");
    $stmt->bind_param("si", $hash, $id);
    $stmt->execute()
        ? redirectWith($redirect, 'success', 'Password berhasil direset.')
        : redirectWith($redirect, 'danger',  'Gagal reset password.');
    $stmt->close();
}

// ── HAPUS USER ────────────────────────────────────────────────────────────────
if ($action === 'hapus_user') {
    $id = (int)($_POST['id_user'] ?? 0);

    // Tidak bisa hapus diri sendiri
    if ($id === (int)$_SESSION['id_user']) {
        redirectWith($redirect, 'warning', 'Tidak bisa menghapus akun sendiri.');
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id_user=?");
    $stmt->bind_param("i", $id);
    $stmt->execute()
        ? redirectWith($redirect, 'success', 'User berhasil dihapus.')
        : redirectWith($redirect, 'danger',  'Gagal menghapus user.');
    $stmt->close();
}

header("Location: $redirect");
exit;