<?php
/**
 * Pengaturan koneksi database.
 * Variabel environment dapat digunakan pada hosting/production.
 * Nilai default mempertahankan kompatibilitas XAMPP lokal.
 */
$host = getenv('CLEANING_DB_HOST') ?: 'localhost';
$user = getenv('CLEANING_DB_USER') ?: 'root';
$pass = getenv('CLEANING_DB_PASS');
$pass = ($pass === false) ? '' : $pass;
$db   = getenv('CLEANING_DB_NAME') ?: 'cleaning_service';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_errno) {
    error_log('Cleaning Stock - database connection failed: ' . $conn->connect_error);
    http_response_code(503);
    exit('Layanan database sedang tidak tersedia. Silakan hubungi administrator.');
}
$conn->set_charset('utf8mb4');
date_default_timezone_set('Asia/Jakarta');
