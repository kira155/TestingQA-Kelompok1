# Cleaning Stock Management

Aplikasi inventaris untuk operasional cleaning service, dibuat dengan PHP, MySQL, Bootstrap, dan Bootstrap Icons.

## Pembaruan 24 November 2026

- Footer dashboard dan halaman login diseragamkan dengan identitas produk yang netral dan tampilan lebih profesional.
- Tahun hak cipta mengikuti tahun server; footer desktop mengikuti area konten dan menyesuaikan lebar layar seluler.
- Menu samping seluler dapat ditutup dengan tombol Esc atau klik di luar menu. Tombol menu memiliki atribut aksesibilitas.
- Registrasi publik **selalu** membuat akun `staff`, walaupun permintaan POST berisi `role=admin`; admin dapat membuat pengguna berhak admin lewat menu **Kelola User**.
- Script bootstrap akun admin dibatasi pada CLI sehingga tidak bisa dijalankan siapa saja melalui alamat URL.
- Redirect registrasi dan tautan dashboard pada halaman 403 diperbaiki.

## Persyaratan

- PHP 8.0 atau lebih baru (mysqli aktif)
- MySQL/MariaDB dengan database `cleaning_service` **dan skema yang sesuai**
- Web server (Apache/XAMPP) dengan aplikasi terpasang pada path `/cleaning`

## Menjalankan aplikasi

1. Salin direktori `cleaning/` ke folder web server (contoh: `C:\xampp\htdocs\cleaning`).
2. Buat database lokal kosong `cleaning_service` melalui phpMyAdmin. Pilih database tersebut, lalu import `database/schema.sql` dan, jika diperlukan, `database/demo_data.sql`. Jangan gunakan pada database produksi.
3. Untuk XAMPP lokal, konfigurasi `config/db.php` mempertahankan nilai default `localhost`, `root`, dan database `cleaning_service`. Untuk server production, gunakan environment variables `CLEANING_DB_HOST`, `CLEANING_DB_USER`, `CLEANING_DB_PASS`, `CLEANING_DB_NAME`; jangan mengunggah password database ke repository publik.
4. Buka `http://localhost/cleaning/auth/login.php`.
5. Jika belum punya akun admin, jalankan script `scripts/create_admin.php` melalui **terminal**, bukan browser, menggunakan environment variable `CLEANING_ADMIN_USER` dan `CLEANING_ADMIN_PASSWORD` (minimal 8 karakter). Jangan menulis password ke commit atau file dokumentasi.

Pada Windows PowerShell, jalankan dengan variabel environment lokal sesi terminal:

```powershell
$env:CLEANING_ADMIN_USER = 'nama_admin'
$env:CLEANING_ADMIN_PASSWORD = Read-Host 'Password admin baru'
php scripts/create_admin.php
Remove-Item Env:CLEANING_ADMIN_PASSWORD
```

Akun admin lain bisa dibuat oleh administrator pada menu **Kelola User**.

## Pemeriksaan sebelum deploy

```bash
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

Untuk Windows, jalankan lint masing-masing file PHP menggunakan perintah `php -l path/file.php`.

**Batasan pengujian:** Syntax PHP dan pemeriksaan source tidak menggantikan pengujian terhubung database, browser, maupun audit keamanan lengkap. Jalankan regression test login, registrasi, stok, transaksi, dan menu admin pada server staging sebelum mengubah website produksi.

## Catatan patch audit 25 November 2026

**Source diperbaiki, belum lulus regression runtime.** Perubahan: token CSRF untuk form mutasi, autentikasi endpoint transaksi, row lock saat barang keluar, agregasi stok master tanpa penggandaan JOIN, penyimpanan cached stok saat membuat barang, pengamanan delete terhadap histori, pencocokan identitas REQ yang presisi, pembersihan otomatis data dinonaktifkan, dan migrasi trigger UPDATE.

**Cara rollout:** backup seluruh DB terlebih dahulu; deploy ke salinan staging; jalankan `migrations/audit_stok_readonly.sql` dan teliti selisih stok; tinjau lalu terapkan `migrations/20261125_fix_transaksi_update.sql` di staging (khusus database lama; skema instalasi baru sudah memakai trigger yang diperbaiki). Jalankan lint PHP dan `python -m unittest discover -s tests -v`. Lalu eksekusi tes manual `MAN-01..MAN-11`, `WB-01..WB-06`, `REG-001..REG-010` dari repository QA. Hanya setelah seluruh tes kritis lulus dan backup diverifikasi, deploy dengan prosedur change management.

**Perubahan kompatibilitas:** form POST dari versi lama (tanpa CSRF) akan ditolak 403 setelah deployment. Jangan deploy hanya PHP action tanpa halaman/form baru. Riwayat mutasi dan data produksi tidak diubah oleh paket ini. Skema dan data demo belum diuji bersama MariaDB pada lingkungan ini.
