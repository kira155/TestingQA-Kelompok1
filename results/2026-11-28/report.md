# Laporan pengujian lokal — 28 November 2026

## 1. Ruang lingkup dan lingkungan
- Objek: Cleaning Stock Management, source paket 24 November 2026.
- SHA-256 ZIP source: `62d4d034e438d476359f41b62f33774bb1be4bcc90926451136ba84c5c6f2f69`.
- Lingkungan: PHP 8.4.24 CLI/built-in HTTP server, Python requests dan Playwright Chromium, Linux; localhost terisolasi.
- MySQL/MariaDB dan ekstensi mysqli tidak tersedia. Instalasi paket gagal karena DNS jaringan. Dump SQL tanggal 25 November diketahui tersedia, tetapi tidak diimpor. Source 25 November tidak ditemukan pada pencarian file yang dilakukan; tidak menyatakan source ini versi produksi terkini.
- Tiga kasus HTTP memakai aplikasi tanpa adapter. Sembilan belas kasus HTTP memakai test double mysqli (data fiktif, tidak menyimpan data). Lima kasus memuat HTML hasil respons aplikasi pada DOM Chromium offline; akses CDN diblokir, sehingga tidak merepresentasikan seluruh tampilan live.
- Pemeriksaan lokal dibantu script, bukan sesi manual manusia pada situs produksi. White Box manual adalah inspeksi kode, bukan cakupan jalur instrumentasi. Tidak mengakses atau mengubah database/website produksi. Wawancara profesional belum dilakukan.

## 2. Hasil eksekusi

| Metode | PASS | FAIL | Total |
|---|---:|---:|---:|
| HTTP native | 3 | 0 | 3 |
| HTTP fixture | 17 | 2 | 19 |
| Offline DOM fixture | 5 | 0 | 5 |
| Total | 25 | 2 | 27 |

Pass rate: 92,59% untuk skenario komponen lokal saja. White Box review dan regression mapping tidak ditambahkan ke denominator agar tidak terjadi penghitungan ganda. Hasil historis 23 November tetap 29 PASS, 0 FAIL, 1 warning.

- [Actual dan expected tiap kasus](component-results.csv)
- [Console eksekusi](execution-console.txt)
- [SQL trace simulasi](fixture-query-trace.jsonl)
- [HTTP log native](php-native.log) dan [fixture](php-fixture.log)
- [Bukti screenshot offline](../../evidence/2026-11-28/)
- [Pemeriksaan CLI tambahan](manual-cli-check.txt)

## 3. White Box manual — inspeksi alur kode

10 pemeriksaan: 5 sesuai inspeksi; 5 temuan (2 didukung eksekusi komponen, 3 risiko hasil inspeksi). Tidak mengklaim statement/branch coverage persentase.

| ID | Lokasi | Hasil |
|---|---|---|
| WB-01 | `auth/login.php:21–53` | Sesuai inspeksi: Input kosong, pengguna tidak ditemukan, kata sandi benar/salah; prepared statement, password_verify dan regenerasi sesi |
| WB-02 | `auth/register.php:17–62` | Sesuai inspeksi: Role selalu staff; batas panjang dan konfirmasi; password di-hash |
| WB-03 | `scripts/create_admin.php:11–23` | Sesuai inspeksi: Guard CLI dan validasi panjang sebelum koneksi |
| WB-04 | `actions/transaksi_action.php:34–63` | Sesuai inspeksi: Jenis, kuantitas dan batas stok divalidasi |
| WB-05 | `actions/permintaan_action.php:26–36` | Sesuai inspeksi: ID dan kuantitas permintaan divalidasi |
| WB-06 | `actions/transaksi_action.php:2–11, 65–74` | Temuan D-01: Tidak ada requireLogin sebelum input transaksi; id_user dapat null |
| WB-07 | `pages/users.php:3–10; middleware/auth.php:21–31` | Temuan D-02: requireAdmin setelah header/sidebar; respons 403 dapat gagal setelah output |
| WB-08 | `actions/barang_action.php:66–89` | Temuan D-03: Tambah barang bind_param sssiss padahal satuan string dan stok integer; stok negatif tidak ditolak saat tambah |
| WB-09 | `actions/transaksi_action.php:50–75` | Temuan D-04: Pengecekan stok, INSERT dan update stok tanpa transaksi/locking atomik |
| WB-10 | `actions/permintaan_action.php:90–106; pages/permintaan.php:34–37` | Temuan D-05: LIKE %REQ-id% dapat mencocokkan ID dengan prefix sama |

[Detail white box](white-box-review.csv).

## 4. Regression testing

| ID | Status | Actual / batasan |
|---|---|---|
| REG-001 | PARTIAL | Footer halaman transaksi keluar diperiksa (BB-24–26); dashboard dan halaman lain belum diuji. |
| REG-002 | PARTIAL | Brand login dan kredit lama diuji offline (BB-23); verifikasi tahun dan browser online belum dilakukan. |
| REG-003 | PARTIAL | Footer pada 375/768/1280 px sesuai bounding box offline; CSS CDN tidak dimuat; tidak membuktikan seluruh layout. |
| REG-004 | PARTIAL | DOM offline: buka, Esc dan klik luar mengubah aria-expanded sesuai BB-27; uji browser online belum. |
| REG-005 | PARTIAL | Respons registrasi serta parameter INSERT staff sesuai; login dengan akun yang baru dibuat dan persistensi belum. |
| REG-006 | PARTIAL | Parameter role staff pada request role admin sesuai BB-13; database nyata belum. |
| REG-007 | PASS | HTTP asli lokal mengembalikan 403 sebelum koneksi database (BB-02). |
| REG-008 | BLOCKED | CLI tanpa kredensial ditolak sesuai validasi, tetapi CLI dengan akun valid dan duplikasi perlu MySQL. |
| REG-009 | PASS (fixture) | Sesi login melalui fixture mengalihkan register ke dashboard (BB-14); belum diuji dengan MySQL. |
| REG-010 | BLOCKED | Belum mengulang TC-001–030 sebagai end-to-end; sebagian perilaku login/validasi diuji komponen. Dua defect masih terbuka. |

## 5. Disposition defect
D-01 dan D-02 ditemukan melalui pengujian lokal; D-03 hingga D-05 merupakan temuan statis yang harus direproduksi di staging. Semua OPEN; belum ada perbaikan source dalam paket ini. Rekomendasi: prioritaskan kontrol sesi dan integritas stok. Lihat [daftar defect](../../bug-reports/defects-2026-11-28.md).

## 6. Langkah penyelesaian manual
Gunakan salinan source yang sama dengan staging, database baru dengan skema dump yang sudah dibersihkan dan data dummy. PHP mysqli harus aktif. Login dengan akun QA admin dan staff; jalankan ulang TC-001–030 dari CSV, catat expected/actual dan screenshot. Cek bahwa registrasi benar-benar menyimpan staff, stok awal/masuk/keluar dan saldo database konsisten, duplikasi CLI ditolak, dan akses tanpa sesi tidak menulis transaksi. Ulangi setelah defect diperbaiki. Jangan menggunakan database produksi untuk pengujian perubahan data.

Wawancara: gunakan daftar pertanyaan dan form pada folder interview; isi jawaban narasumber QA yang nyata, waktu, jabatan, dan persetujuan. Tidak ada jawaban fiktif.
