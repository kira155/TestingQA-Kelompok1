# Pengujian Kualitas Website Cleaning Stock Management

**Kelompok 1 – Testing & Quality Assurance | Universitas Pamulang**

Anggota: Abdul Kholik (231011400097), Ahmad Reza (231011403385), Akbar Rusmanto (231011401424), Adjie Febrianto (231011400092).

<<<<<<< HEAD
## Latar belakang

Cleaning Stock Management merupakan aplikasi berbasis PHP dan MariaDB untuk mencatat persediaan, permintaan barang, dan transaksi masuk maupun keluar. Pengujian dilakukan untuk mengetahui apakah fungsi aplikasi bekerja sesuai kebutuhan serta mengidentifikasi kekurangan pada validasi input, hak akses pengguna, dan pengelolaan stok.
=======
| No. | Nama | NIM |
|---|---|---|
| 1 | Abdul Kholik | 231011400097 |
| 2 | Ahmad Reza | 231011403385 |
| 3 | Akbar Rusmanto | 231011401424 | 
| 4 | Adjie Febrianto | 231011400092 |


## Objek pengujian
>>>>>>> c772c2fd17c25c02587f61eb60daaa3ef380fd47

## Metode dan standar pengujian

Kelompok menggunakan rancangan pengujian manual Black Box dan White Box. Black Box berfokus pada perilaku aplikasi dari sisi pengguna, sedangkan White Box meninjau percabangan dan alur logika pada source code. Penyusunan dokumen mengacu pada ISO/IEC/IEEE 29119, terutama Part 2 untuk proses, Part 3 untuk dokumentasi, dan Part 4 untuk teknik pengujian. Acuan tersebut diterapkan sesuai lingkup tugas, bukan sebagai klaim sertifikasi.

## Perkembangan pengujian

Pada 23 November 2026, sesi pengujian otomatis sebelumnya mencatat 29 PASS, 0 FAIL, 0 SKIP, dan 1 warning. Repository memuat 30 skenario historis dengan catatan PASS; TC-001 dan TC-002 juga memiliki catatan dari sesi terdahulu. Bukti khusus TC-002 belum ditemukan. Angka tersebut tidak mewakili hasil pengujian manual pada versi aplikasi yang telah diperbaiki.

Review source menemukan beberapa masalah terkait transaksi dan konsistensi stok. Perbaikan telah disiapkan, dan pemeriksaan sintaks serta struktur repository berhasil. Pengujian manual dan regresi pada aplikasi terbaru masih berstatus NOT RUN.

## Struktur repository

| Folder | Isi |
|---|---|
| [cleaning/](cleaning/README.md) | Source website, skema lokal, data demo dan migrasi |
| [docs/](docs/test-plan.md) | Rencana pengujian, metode, analisis ISO, RTM, dan rencana White Box |
| [test-cases/](test-cases/test-cases.xlsx) | Test case dan lembar pencatatan hasil |
| [results/](results/test-execution-report.md) | Laporan hasil historis dan audit |
| [bug-reports/](bug-reports/BUG-TEMPLATE.md) | Temuan source dan format laporan defect |
| [evidence/](evidence/README.md) | Bukti pengujian terdahulu |
| [interview/](interview/qa-interview-questions.md) | Daftar pertanyaan dan lembar validasi narasumber |
| [scripts/](scripts/verify_repository.py) | Pemeriksaan kelengkapan artefak QA |

## Menjalankan aplikasi secara lokal

Ikuti [petunjuk instalasi](cleaning/README.md). Source code dan skema database kosong sudah tersedia di dalam repository. Data contoh bersifat fiktif; dump asli dan foto operasional tidak disertakan. Gunakan akun uji baru dan lingkungan lokal, bukan server produksi.

## Pemeriksaan repository

```powershell
python scripts/verify_repository.py
```

Pemeriksaan ini hanya memastikan konsistensi dokumen dan bukti, bukan mengeksekusi aplikasi. Rencana pengujian lanjutan dapat dilihat pada [analisis ISO](docs/iso-29119-gap-analysis.md) dan [laporan audit](results/laporan-audit-iso29119.md).

## Referensi

- ISO/IEC/IEEE 29119-2:2021, *Software testing – Test processes*. https://www.iso.org/standard/79428.html
- ISO/IEC/IEEE 29119-3:2021, *Software testing – Test documentation*. https://www.iso.org/standard/79429.html
- ISO/IEC/IEEE 29119-4:2021, *Software testing – Test techniques*. https://www.iso.org/standard/79430.html
- S. Reid, *ISO/IEC/IEEE 29119: The New International Software Testing Standards*, artikel pengantar yang dilampirkan pada tugas.
