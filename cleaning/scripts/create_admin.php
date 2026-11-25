<?php
/**
 * Setup admin melalui CLI saja. Tidak tersedia dari browser publik.
 *
 * Variabel lingkungan wajib:
 *   CLEANING_ADMIN_USER
 *   CLEANING_ADMIN_PASSWORD
 *
 * Contoh penggunaan ada di README.md.
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('403 - Script setup hanya dapat dijalankan melalui CLI.');
}

$username = trim((string) getenv('CLEANING_ADMIN_USER'));
$password = (string) getenv('CLEANING_ADMIN_PASSWORD');

if (strlen($username) < 3 || strlen($password) < 8) {
    fwrite(STDERR, "Gagal: set CLEANING_ADMIN_USER (min. 3 karakter) dan CLEANING_ADMIN_PASSWORD (min. 8 karakter).\n");
    exit(1);
}

require_once __DIR__ . '/../config/db.php';

$stmt = $conn->prepare('SELECT id_user FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    fwrite(STDERR, "Gagal: username tersebut sudah terdaftar.\n");
    $stmt->close();
    exit(1);
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$stmt->bind_param('ss', $username, $hash);
if (!$stmt->execute()) {
    fwrite(STDERR, "Gagal membuat akun admin. Periksa koneksi database dan skema tabel.\n");
    $stmt->close();
    exit(1);
}
$stmt->close();
$conn->close();
fwrite(STDOUT, "Akun admin berhasil dibuat. Silakan login melalui halaman aplikasi.\n");
