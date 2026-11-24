# Test Execution Report — Cleaning Service Stock Management

**Tanggal eksekusi gabungan:** 23 November 2026  
**Aplikasi:** Cleaning Service Stock Management  
**URL pengujian:** https://axioo-production.my.id/cleaning/auth/login.php

## 1. Ringkasan Eksekutif

Eksekusi gabungan menghasilkan **29 passed, 0 failed, 0 skipped, dan 1 warning** dalam **313,65 detik (5 menit 13,65 detik)**. Angka ini hanya mencakup 29 test yang dikumpulkan pada sesi gabungan.

TC-001 dan TC-002 memiliki hasil PASS dari pengujian terdahulu, tetapi tidak termasuk dalam sesi gabungan. TC-002 masih memerlukan konfirmasi bukti screenshot khusus. Karena itu, 30 skenario memiliki catatan hasil PASS, tetapi bukan berarti 30 test dijalankan dalam satu sesi.

## 2. Acuan Dokumentasi

Laporan mengadaptasi unsur dokumentasi dari materi Stuart Reid, *ISO/IEC/IEEE 29119: The New International Software Testing Standards*: Part 2 (test processes), Part 3 (test documentation), dan Part 4 (test techniques). Materi Part 3 membahas antara lain test results, anomaly reports, test environment report, dan test level completion report. Dokumen ini bukan sertifikasi atau klaim kesesuaian penuh terhadap seluruh standar.

## 3. Lingkungan Pengujian

| Komponen | Detail |
|---|---|
| Sistem operasi | Windows (win32) |
| Terminal | Windows PowerShell |
| Direktori proyek | `C:\xampp\htdocs\tester` |
| Python | 3.13.7 |
| pytest | 9.2.0 |
| Plugin | pytest-playwright 0.10.0 |
| Browser | Chromium |
| Mode | Headed (`--headed`) |
| Framework | Playwright + pytest |
| URL awal | `https://axioo-production.my.id/cleaning/auth/login.php` |
| Folder bukti | `evidence/` |

## 4. Ruang Lingkup dan Metode

Ruang lingkup meliputi login, dashboard, stok barang, filter dan pencarian, navigasi transaksi, form permintaan barang, halaman barang masuk/keluar, serta validasi form dan batas maksimum stok. Metode yang digunakan adalah black-box testing melalui Playwright dan pytest. Boundary Value Analysis digunakan pada pengujian jumlah dan batas stok; inspeksi antarmuka digunakan untuk memeriksa elemen, menu, tabel, input, dropdown, dan textarea.

Pengujian validasi form pada sesi ini terutama memeriksa elemen, atribut HTML, dan validitas browser. Hasil tersebut tidak otomatis membuktikan validasi sisi server atau keberhasilan penyimpanan transaksi.

## 5. Ringkasan Hasil Eksekusi

| Metrik | Hasil |
|---|---:|
| Test terkumpul | 29 |
| Passed | 29 |
| Failed | 0 |
| Skipped | 0 |
| Warning | 1 |
| Durasi | 313,65 detik |

## 6. Detail Hasil per Test Case

| ID | Skenario | Hasil | Catatan | Bukti |
|---|---|---|---|---|
| TC-001 | Inspeksi halaman login | PASS* | Pengujian terdahulu | login-page-inspection.png |
| TC-002 | Inspeksi struktur form login | PASS* | Hasil historis; bukti khusus belum teridentifikasi | Belum teridentifikasi |
| TC-003 | Validasi seluruh field login kosong | PASS | Sesi gabungan | TC003_login_kosong.png |
| TC-004 | Validasi username kosong | PASS | Sesi gabungan | TC004_username_kosong.png |
| TC-005 | Validasi password kosong | PASS | Sesi gabungan | TC005_password_kosong.png |
| TC-006 | Login kredensial salah | PASS | Sesi gabungan | TC006_sebelum_submit.png; TC006_hasil_login_salah.png |
| TC-007 | Login kredensial benar | PASS | Sesi gabungan | TC007_sebelum_login.png; TC007_setelah_login.png |
| TC-008 | Inspeksi dashboard dan menu | PASS | Sesi gabungan | TC008_dashboard.png |
| TC-009 | Tampilan stok barang | PASS | Sesi gabungan | TC009_stok_barang.png |
| TC-010 | Filter kategori stok | PASS | Sesi gabungan | TC010_filter_chemical.png; TC010_filter_peralatan.png; TC010_filter_semua_kategori.png |
| TC-011 | Pencarian nama barang | PASS | Sesi gabungan | TC011_search_sabun.png; TC011_search_barangtidakadaxyz987.png |
| TC-012 | Reset filter stok | PASS | Sesi gabungan | TC012_reset_filter_stok.png |
| TC-013 | Navigasi menu transaksi | PASS | Sesi gabungan | TC013_permintaan_barang.png; TC013_barang_masuk.png; TC013_barang_keluar.png |
| TC-014 | Inspeksi form permintaan barang | PASS | Sesi gabungan | TC014_form_permintaan.png |
| TC-015 | Validasi jumlah permintaan | PASS | Sesi gabungan | TC015_validasi_jumlah.png |
| TC-016 | Validasi pilihan barang permintaan | PASS | Sesi gabungan | TC016_validasi_pilihan_barang.png |
| TC-017 | Validasi tanggal permintaan | PASS | Sesi gabungan | TC017_validasi_tanggal_permintaan.png |
| TC-018 | Validasi catatan permintaan | PASS | Sesi gabungan | TC018_validasi_catatan_permintaan.png |
| TC-019 | Inspeksi halaman barang masuk | PASS | Sesi gabungan | TC019_inspeksi_barang_masuk.png |
| TC-020 | Inspeksi halaman barang keluar | PASS | Sesi gabungan | TC020_inspeksi_barang_keluar.png |
| TC-021 | Inspeksi form barang keluar | PASS | Sesi gabungan | TC021_inspeksi_form_barang_keluar.png |
| TC-022 | Validasi jumlah barang keluar | PASS | Sesi gabungan | TC022_validasi_jumlah_barang_keluar.png |
| TC-023 | Validasi pilihan barang keluar | PASS | Sesi gabungan | TC023_validasi_pilihan_barang_keluar.png |
| TC-024 | Validasi tanggal barang keluar | PASS | Sesi gabungan | TC024_validasi_tanggal_barang_keluar.png |
| TC-025 | Validasi keterangan barang keluar | PASS | Sesi gabungan | TC025_validasi_keterangan_barang_keluar.png |
| TC-026 | Inspeksi batas maksimum stok | PASS | Sesi gabungan | TC026_inspeksi_batas_stok.png |
| TC-027 | Batas maksimum stok Baygon / HIT | PASS | Sesi gabungan | TC027_batas_maksimum_stok.png |
| TC-028 | Batas stok Chinoff 50 EC | PASS | Sesi gabungan | TC028_batas_stok_barang_berbeda.png |
| TC-029 | Perubahan pilihan barang | PASS | Sesi gabungan | TC029_perubahan_pilihan_barang.png |
| TC-030 | Validasi jumlah setelah ganti barang | PASS | Sesi gabungan | TC030_validasi_jumlah_setelah_ganti_barang.png |

`PASS*` = hasil pengujian terdahulu, bukan dari sesi gabungan. Bukti khusus TC-002 belum teridentifikasi pada inventarisasi folder.

## 7. Temuan dan Catatan

### Warning pytest

Satu `PytestRemovedIn10Warning` muncul karena fixture berscope session didefinisikan sebagai instance method. Warning tidak menyebabkan test gagal. Perbaikan fixture direkomendasikan untuk kompatibilitas versi pytest mendatang.

### Hasil validasi batas stok

- TC-026: atribut `max` belum terisi pada kondisi awal dan menjadi 35 setelah Baygon / HIT dipilih.
- TC-027: jumlah 34 dan 35 diterima, sedangkan 36 ditolak untuk stok Baygon / HIT sebesar 35.
- TC-028: jumlah 1 dan 2 diterima, sedangkan 3 ditolak untuk stok Chinoff 50 EC sebesar 2.
- TC-029: batas maksimum berubah dari 35 menjadi 2 saat barang diganti dan dibersihkan saat pilihan dikosongkan.
- TC-030: jumlah 3 diterima untuk Baygon / HIT, ditolak untuk Chinoff 50 EC, dan jumlah 2 diterima untuk Chinoff 50 EC.

### Batasan

Keamanan aplikasi secara menyeluruh, validasi sisi server seluruh input, konsistensi database setelah penyimpanan, performa/beban, concurrency, dan kompatibilitas lintas browser belum diuji secara menyeluruh.

## 8. Inventarisasi Bukti

<<<<<<< HEAD
Pada versi repository yang diperbarui tanggal **24 November 2026**, folder `evidence/` sudah memuat **37 file PNG**: 36 screenshot yang direferensikan test case dan 1 bukti inspeksi tambahan. Screenshot khusus TC-002 belum teridentifikasi. Daftar file di bawah ini dapat dibuka melalui tautan repository:
=======
Pada saat inventarisasi, folder lokal `C:\\xampp\\htdocs\\tester\\evidence` berisi 38 file PNG. **File gambar tersebut belum disertakan di dalam ZIP repository ini.** Daftar berikut adalah nama file yang perlu disalin ke folder `evidence/` sebelum laporan dipublikasikan agar tautannya dapat dibuka:
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670
- [`inspect_filter_stok.png`](../evidence/inspect_filter_stok.png)
- [`login-page-inspection.png`](../evidence/login-page-inspection.png)
- [`TC003_login_kosong.png`](../evidence/TC003_login_kosong.png)
- [`TC004_username_kosong.png`](../evidence/TC004_username_kosong.png)
- [`TC005_password_kosong.png`](../evidence/TC005_password_kosong.png)
- [`TC006_hasil_login_salah.png`](../evidence/TC006_hasil_login_salah.png)
- [`TC006_sebelum_submit.png`](../evidence/TC006_sebelum_submit.png)
- [`TC007_sebelum_login.png`](../evidence/TC007_sebelum_login.png)
- [`TC007_setelah_login.png`](../evidence/TC007_setelah_login.png)
- [`TC008_dashboard.png`](../evidence/TC008_dashboard.png)
- [`TC009_stok_barang.png`](../evidence/TC009_stok_barang.png)
- [`TC010_filter_chemical.png`](../evidence/TC010_filter_chemical.png)
- [`TC010_filter_peralatan.png`](../evidence/TC010_filter_peralatan.png)
- [`TC010_filter_semua_kategori.png`](../evidence/TC010_filter_semua_kategori.png)
- [`TC011_search_barangtidakadaxyz987.png`](../evidence/TC011_search_barangtidakadaxyz987.png)
- [`TC011_search_sabun.png`](../evidence/TC011_search_sabun.png)
- [`TC012_reset_filter_stok.png`](../evidence/TC012_reset_filter_stok.png)
- [`TC013_barang_keluar.png`](../evidence/TC013_barang_keluar.png)
- [`TC013_barang_masuk.png`](../evidence/TC013_barang_masuk.png)
- [`TC013_permintaan_barang.png`](../evidence/TC013_permintaan_barang.png)
- [`TC014_form_permintaan.png`](../evidence/TC014_form_permintaan.png)
- [`TC015_validasi_jumlah.png`](../evidence/TC015_validasi_jumlah.png)
- [`TC016_validasi_pilihan_barang.png`](../evidence/TC016_validasi_pilihan_barang.png)
- [`TC017_validasi_tanggal_permintaan.png`](../evidence/TC017_validasi_tanggal_permintaan.png)
- [`TC018_validasi_catatan_permintaan.png`](../evidence/TC018_validasi_catatan_permintaan.png)
- [`TC019_inspeksi_barang_masuk.png`](../evidence/TC019_inspeksi_barang_masuk.png)
- [`TC020_inspeksi_barang_keluar.png`](../evidence/TC020_inspeksi_barang_keluar.png)
- [`TC021_inspeksi_form_barang_keluar.png`](../evidence/TC021_inspeksi_form_barang_keluar.png)
- [`TC022_validasi_jumlah_barang_keluar.png`](../evidence/TC022_validasi_jumlah_barang_keluar.png)
- [`TC023_validasi_pilihan_barang_keluar.png`](../evidence/TC023_validasi_pilihan_barang_keluar.png)
- [`TC024_validasi_tanggal_barang_keluar.png`](../evidence/TC024_validasi_tanggal_barang_keluar.png)
- [`TC025_validasi_keterangan_barang_keluar.png`](../evidence/TC025_validasi_keterangan_barang_keluar.png)
- [`TC026_inspeksi_batas_stok.png`](../evidence/TC026_inspeksi_batas_stok.png)
- [`TC027_batas_maksimum_stok.png`](../evidence/TC027_batas_maksimum_stok.png)
- [`TC028_batas_stok_barang_berbeda.png`](../evidence/TC028_batas_stok_barang_berbeda.png)
- [`TC029_perubahan_pilihan_barang.png`](../evidence/TC029_perubahan_pilihan_barang.png)
- [`TC030_validasi_jumlah_setelah_ganti_barang.png`](../evidence/TC030_validasi_jumlah_setelah_ganti_barang.png)

<<<<<<< HEAD
[`inspect_filter_stok.png`](../evidence/inspect_filter_stok.png) merupakan bukti inspeksi tambahan. File screenshot telah tersedia dalam `evidence/`. Pastikan file-file tersebut ikut dalam commit GitHub. Jangan mengunggah password, token, atau data pribadi.
=======
[`inspect_filter_stok.png`](../evidence/inspect_filter_stok.png) merupakan bukti inspeksi tambahan. Salin seluruh screenshot asli dari folder lokal `evidence/` ke `evidence/` pada repository, lalu commit file gambar tersebut. Jangan mengunggah password, token, atau data pribadi.
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670

## 9. Kesimpulan

Pada sesi gabungan tanggal 23 November 2026, seluruh 29 test yang dikumpulkan berhasil (29 passed, 0 failed, 0 skipped). Hasil historis TC-001 dan TC-002 dicatat terpisah. Dengan demikian, seluruh 30 skenario memiliki catatan hasil PASS, tetapi TC-002 masih memerlukan konfirmasi bukti screenshot khusus. Hasil ini hanya mendukung kesimpulan untuk antarmuka dan validasi browser pada skenario yang diuji, bukan bahwa seluruh aplikasi bebas cacat.

## Referensi

Reid, S. (2013). *ISO/IEC/IEEE 29119: The New International Software Testing Standards* [Artikel PDF yang diberikan].