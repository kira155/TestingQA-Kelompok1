# Testing QA – Cleaning Service System

Tugas kelompok 1 mata kuliah Testing & Quality Assurance, Teknik Informatika, Universitas Pamulang.

## Anggota kelompok

| No. | Nama | NIM |
|---|---|---|
| 1 | Abdul Kholik | 231011400097 |
| 2 | Ahmad Reza | 231011403385 |
| 3 | Akbar Rusmanto | 231011401424 |
| 4 | Adjie Febrianto | 231011400092 |

## Tentang aplikasi

Cleaning Service System digunakan untuk mengelola stok, permintaan barang, barang masuk dan barang keluar. Tujuan pengujian ini untuk mengetahui apakah fitur tersebut berjalan sesuai dengan fungsinya dan mencari kesalahan yang masih ada.

Website: https://axioo-production.my.id/cleaning/auth/login.php

## Metode pengujian

Metode utama yang digunakan adalah Black Box dan White Box. Black Box berfokus pada input dan hasil yang ditampilkan aplikasi, sedangkan White Box melihat alur pada source code PHP. Pengujian ulang dilakukan setelah ada perubahan aplikasi. Sebagai pemeriksaan tambahan, digunakan Playwright dan script pengujian lokal.

## Progres pengujian

| Tanggal | Kegiatan | Hasil |
|---|---|---|
| 23 November 2026 | Pengujian fitur login, stok dan transaksi menggunakan Playwright | 29 PASS, 0 FAIL, 1 warning |
| 24 November 2026 | Perubahan footer, registrasi dan navigasi; cek sintaks PHP | 27 file PHP lolos; uji fungsi belum lengkap |
| 28 November 2026 | Pengujian komponen lokal dan pengecekan source code | 25 PASS, 2 FAIL; 5 temuan kode (2 di antaranya juga muncul saat pengujian lokal) |

Total daftar test case awal adalah 30. Tidak semuanya dijalankan pada sesi Playwright 23 November. Bukti khusus TC-002 belum ditemukan.

## Update terakhir

Pada pengujian lokal versi 24 November, terdapat dua skenario yang belum sesuai: akses halaman kelola user dan transaksi tanpa sesi login. Dari pengecekan kode, ada tiga risiko tambahan pada proses tambah barang, perubahan stok dan pencocokan permintaan barang.

| Pekerjaan | Status |
|---|---|
| Test case awal | 30 skenario tersedia |
| Black Box manual pada versi terbaru | Belum selesai |
| White Box (review kode) | 10 pemeriksaan, 5 temuan |
| Regression testing | Sebagian |
| Perbaikan bug dan tes ulang | Belum dilakukan |
| Wawancara QA | Belum dilakukan |

Hasil 28 November berasal dari 3 uji HTTP biasa, 19 uji HTTP dengan database simulasi dan 5 pemeriksaan tampilan offline. MySQL belum tersedia di lingkungan tersebut. Jadi, hasil ini belum menjadi hasil pengujian manual pada website versi terbaru dengan database asli.

## File pengujian

- [Test plan](docs/test-plan.md) dan [acuan metode](docs/methodology-and-standard.md)
- [Daftar test case (Excel)](test-cases/test-cases.xlsx) / [CSV](test-cases/test-cases.csv)
- [Hasil pengujian awal](results/test-execution-report.md)
- [Hasil pengujian 28 November](results/2026-11-28/report.md)
- [Daftar bug](bug-reports/defects-2026-11-28.md) dan [status regression](results/2026-11-28/regression-status.csv)
- [Screenshot](evidence/), [riwayat perubahan](docs/CHANGELOG.md), dan [rencana pembagian tugas](docs/pembagian-tugas.md)
- [Pertanyaan wawancara QA](interview/qa-interview-questions.md)

Acuan dokumentasi: ISO/IEC/IEEE 29119 berdasarkan artikel pengantar Stuart Reid (2013) yang tersedia. Referensi ini masih perlu dilengkapi sesuai ketentuan tahun referensi dari dosen. Proyek ini tidak mengklaim pemenuhan penuh standar tersebut.
