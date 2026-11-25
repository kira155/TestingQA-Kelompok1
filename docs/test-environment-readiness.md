# Test Environment Readiness Report – Template ISO 29119 Part 3

| Item | Kondisi yang dapat diverifikasi dari file | Status |
|---|---|---|
| Source PHP | Tersedia dalam arsip Cleaning (bukan GitHub QA) | AVAILABLE |
| Dump MariaDB | Tersedia dari user (termasuk hashed account); jangan disebar publik | AVAILABLE |
| Konfigurasi runtime | PHP 8.2 & MariaDB 10.4 disebut di header dump; runtime pengujian belum dioperasikan | NOT VERIFIED |
| Build versi baru | Source ZIP tanggal 25 Nov 2026; hash/build id sebaiknya direkam ketika deploy | NOT VERIFIED |
| Host staging | Belum ada bukti URL staging aktif atau database uji | NOT READY |
| Akun staf/admin uji | Database memiliki role; kredensial uji dan izin belum dibuktikan | NOT READY |
| Backup & data sanitasi | Harus dibuat sebelum import/mutasi | NOT READY |
| Browser/resolusi | Sesi historis Chromium tersedia, versi terbaru belum diukur | NOT READY |
| Basis testcase & evidence | CSV/evidence historis diperbaiki | PARTIAL |
| Kesiapan end-to-end manual | Belum dapat dinyatakan siap | NOT READY |

Sebelum uji: backup database, siapkan database salinan, import source dan migrasi secara terkontrol, buat akun uji terpisah, catat URL dan versi, dan tentukan batasan data yang boleh diubah. Simpan laporan environment readiness yang sudah ditandatangani penguji.
