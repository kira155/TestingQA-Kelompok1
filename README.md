# Pengujian Website Cleaning Service System

**Tugas Kelompok 1 – Testing & Quality Assurance**  
Program Studi Teknik Informatika, Universitas Pamulang

## Anggota kelompok

| No. | Nama | NIM |
|---|---|---|
| 1 | Abdul Kholik | 231011400097 |
| 2 | Ahmad Reza | 231011403385 |
| 3 | Akbar Rusmanto | 231011401424|
| 4 | Adjie Febrianto | 231011400092 |

## Objek pengujian

**Cleaning Service System**, aplikasi web untuk pengelolaan persediaan barang dan transaksi operasional cleaning service.

Website: https://axioo-production.my.id/cleaning/auth/login.php

## Tujuan

Menguji fungsi antarmuka, autentikasi, navigasi, tampilan stok, filter dan pencarian barang, form permintaan barang, serta halaman dan validasi transaksi barang masuk/keluar. Hasil dicatat berdasarkan actual result dan evidence yang tersedia.

## Metode dan acuan

- **Black Box Testing** melalui antarmuka aplikasi.
- **Automated UI Testing** menggunakan Playwright dan pytest pada browser Chromium.
- **Boundary Value Analysis** untuk validasi jumlah dan batas maksimum stok.
- **Equivalence Partitioning** untuk variasi input valid/tidak valid.
- **State Transition Testing** untuk perubahan pilihan barang dan perubahan validasi.
- Dokumentasi dan proses dipetakan ke ISO/IEC/IEEE 29119 berdasarkan artikel pengantar Stuart Reid yang diberikan. Artikel tersebut bukan salinan lengkap standar normatif; repository ini tidak mengklaim sertifikasi atau pemenuhan penuh seluruh standar.

## Ruang lingkup pengujian aktual

1. Halaman login, struktur form, validasi field kosong, login salah, dan login berhasil.
2. Dashboard, menu navigasi, dan ringkasan sistem.
3. Stok barang, filter kategori, pencarian, dan reset filter.
4. Navigasi Permintaan Barang, Barang Masuk, dan Barang Keluar.
5. Inspeksi form permintaan serta validasi jumlah, pilihan barang, tanggal, dan catatan.
6. Inspeksi halaman Barang Masuk dan Barang Keluar.
7. Inspeksi form barang keluar serta validasi jumlah, pilihan barang, tanggal, keterangan, dan batas maksimum stok.
8. Perubahan pilihan barang dan validasi jumlah setelah pergantian barang.

## Hasil eksekusi terbaru

Eksekusi gabungan pada **23 November 2026** menghasilkan:

| Metrik | Hasil |
|---|---:|
| Test yang dikumpulkan pada sesi gabungan | 29 |
| Passed | 29 |
| Failed | 0 |
| Skipped | 0 |
| Warning pytest | 1 |
| Durasi | 313,65 detik (5 menit 13,65 detik) |

TC-001 dan TC-002 memiliki hasil PASS dari pengujian terdahulu dan tidak termasuk dalam sesi gabungan 29 test. Bukti screenshot khusus TC-002 belum teridentifikasi. Dengan demikian, 30 test case dalam daftar memiliki catatan PASS, tetapi tidak seluruhnya dieksekusi dalam satu sesi.

Satu warning `PytestRemovedIn10Warning` terkait fixture session-scoped yang didefinisikan sebagai instance method. Warning tidak menyebabkan test gagal dan direkomendasikan untuk diperbaiki.

**Batasan:** pengujian validasi form terutama memeriksa elemen, atribut HTML, dan validitas browser. Hasil ini tidak membuktikan seluruh validasi server, proses penyimpanan database, keamanan menyeluruh, performa, atau kompatibilitas lintas browser.

## Lingkungan pengujian aktual

| Komponen | Detail |
|---|---|
| Sistem operasi | Windows (win32) |
| Terminal | Windows PowerShell |
| Python | 3.13.7 |
| pytest | 9.2.0 |
| pytest-playwright | 0.10.0 |
| Browser | Chromium |
| Mode | Headed (`--headed`) |
| Framework | Playwright + pytest |
| Direktori lokal pengujian | `C:\xampp\htdocs\tester` |

## Struktur dokumentasi

- [Test Plan](docs/test-plan.md)
- [Pemetaan metodologi dan ISO/IEC/IEEE 29119](docs/methodology-and-standard.md)
- [Daftar test case CSV](test-cases/test-cases.csv)
- [Daftar test case Excel](test-cases/test-cases.xlsx)
- [Test Execution Report (Markdown)](results/test-execution-report.md)
- [Test Execution Report (Word)](results/Test_Execution_Report.docx)
- [Format laporan bug](bug-reports/BUG-TEMPLATE.md)
- [Pertanyaan wawancara QA](interview/qa-interview-questions.md)
- [Form validasi QA](interview/qa-validation-template.md)
- [Regression plan update web 24 November 2026](docs/regression-plan-2026-11-24.md)
- [Changelog progres](docs/CHANGELOG.md)
- [Pemeriksaan repository otomatis](scripts/verify_repository.py)

## Evidence

<<<<<<< HEAD
**37 file screenshot PNG sudah tersedia** pada folder [`evidence/`](evidence/) dari arsip yang diterima: 36 menjadi rujukan test case, satu merupakan inspeksi tambahan. Bukti khusus TC-002 masih belum teridentifikasi. Sebelum push ke repo publik, periksa screenshot agar tidak memuat password, token, atau data pribadi.
=======
Daftar nama 38 screenshot telah diinventarisasi dari folder lokal `C:\xampp\htdocs\tester\evidence`. File gambar belum disertakan dalam ZIP ini. Salin screenshot asli ke folder `evidence/` pada repository sebelum dipublikasikan agar tautan evidence dalam laporan dapat diakses. Jangan unggah password, token, atau data pribadi.
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670

## Progres pekerjaan

| Kegiatan | Status |
|---|---|
| Pemilihan website dan objek pengujian | Selesai |
| Test plan dan acuan dokumentasi | Tersedia |
| Penyusunan 30 test case sesuai fitur aktual | Selesai dan diselaraskan |
| Eksekusi gabungan | 29 test: PASS 29, FAIL 0, SKIP 0 |
| Hasil historis TC-001 dan TC-002 | PASS; TC-002 perlu konfirmasi evidence khusus |
<<<<<<< HEAD
| Bukti screenshot dalam repository | 37 file PNG tersedia; TC-002 belum memiliki screenshot khusus |
=======
| Inventarisasi screenshot lokal | 38 file PNG terdata; belum dimasukkan ke ZIP ini |
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670
| Laporan eksekusi | Diperbarui di `results/` |
| White Box Testing | Belum dilakukan; memerlukan source code dan izin |
| Pengujian server-side, database, performa, dan lintas browser | Belum dicakup secara menyeluruh |
| Wawancara dan validasi QA profesional | Belum dilakukan |

<<<<<<< HEAD
## Pembaruan 24 November 2026

Repository ini kini memiliki 37 screenshot yang tersedia, 10 rencana regression test tambahan (status **NOT RUN**), dan pemeriksaan otomatis untuk konsistensi file. Jalankan dari root repository:

```bash
python scripts/verify_repository.py
```

GitHub Actions juga akan menjalankan pemeriksaan yang sama ketika ada push atau pull request. Ini **bukan** pengujian browser otomatis maupun pengujian ulang aplikasi setelah perubahan source.

Source aplikasi Cleaning diperbarui dalam arsip terpisah. Rincian perubahan dan batasan pengujian tersedia di [Changelog](docs/CHANGELOG.md) dan [Regression Plan](docs/regression-plan-2026-11-24.md).

=======
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670
## Referensi

Reid, S. (2013). *ISO/IEC/IEEE 29119: The New International Software Testing Standards* [Artikel PDF yang diberikan].
