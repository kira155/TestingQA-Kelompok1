# Test Plan — Cleaning Service System

| Elemen | Rincian |
|---|---|
| Project ID | QA-CLEANING-2026 |
| Version | 1.0 (draft) |
| Dibuat | 23 November 2026 |
| Object | https://axioo-production.my.id/cleaning/auth/login.php |
| Tujuan | Menilai kualitas fungsi aplikasi, validasi input, keamanan dasar alur autentikasi, dan pengalaman penggunaan |
| Pendekatan | Manual Black Box Testing dan White Box Testing |
| Standar kerangka kualitas | ISO/IEC 25002:2024 sebagai referensi kerangka kualitas perangkat lunak |
| Jumlah kasus | 30 test case (target awal, dapat disesuaikan) |
| Lingkungan | Desktop: Chrome dan Edge; mobile viewport 375×812; desktop viewport 1366×768 |
| Status awal | Not Run |

## A. Tujuan dan Ruang Lingkup

Pengujian dilakukan untuk mengetahui apakah fitur pada Cleaning Service System berjalan sesuai dengan kebutuhan dan fungsinya.

Ruang lingkup Black Box Testing meliputi:
- Halaman login.
- Form registrasi.
- Validasi username dan password.
- Validasi input kosong dan input tidak valid.
- Proses autentikasi pengguna.
- Navigasi halaman setelah login.
- Tampilan website pada berbagai resolusi dan browser.
- Respons sistem terhadap input pengguna.

Ruang lingkup White Box Testing meliputi:
- Pemeriksaan struktur kode program.
- Pemeriksaan alur logika program.
- Pemeriksaan percabangan kondisi (branch).
- Pemeriksaan pernyataan program (statement).
- Pengujian jalur eksekusi kode (path testing).

**Di luar scope:** pengujian penetrasi, load/stress test, perubahan database produksi, dan tindakan yang membutuhkan kredensial atau izin khusus.

Catatan: Pengujian White Box dilaksanakan setelah source code aplikasi diperoleh dari tim pengembang.

## B. Quality Attributes & Kriteria

| Aspek | Kebutuhan Pengguna | Kriteria Operasional |
|---|---|---|
| Functional Suitability | Pengguna dapat login dan menggunakan fitur aplikasi | Fungsi berjalan sesuai hasil yang diharapkan |
| Interaction Capability | Pengguna dapat memahami tampilan dan pesan sistem | Navigasi jelas dan validasi mudah dipahami |
| Compatibility | Website dapat digunakan pada browser dan layar berbeda | Tidak terdapat elemen penting yang terpotong atau bertumpuk |
| Performance Efficiency | Website memberikan respons yang baik | Waktu respons dicatat melalui pengujian berulang |
| Reliability | Sistem memberikan hasil yang konsisten | Tidak terjadi error saat menjalankan skenario yang sama |
| Security | Proses autentikasi melindungi akses pengguna | Input tidak valid ditolak dan akses halaman terlindungi diuji sesuai izin |

## C. Teknik Desain Pengujian

### 1. Black Box Testing

- **Equivalence Partitioning:** membagi input menjadi kelompok valid dan tidak valid.
- **Boundary Value Analysis:** menguji batas panjang input dan nilai yang diperbolehkan.
- **Positive Testing:** menguji penggunaan aplikasi dengan input yang benar.
- **Negative Testing:** menguji aplikasi dengan input yang salah atau tidak lengkap.
- **Exploratory Testing:** memeriksa navigasi dan perilaku aplikasi secara langsung.

### 2. White Box Testing

- **Statement Coverage:** memastikan pernyataan kode yang relevan telah dieksekusi.
- **Branch Coverage:** memeriksa setiap kemungkinan percabangan kondisi.
- **Path Testing:** memeriksa jalur eksekusi logika program.

Perlu dibedakan antara kriteria berdasarkan kebutuhan sistem yang telah dibuktikan dan asumsi berdasarkan praktik pengujian umum. Jika aturan validasi belum diketahui, gunakan status `Needs Clarification` dan jangan langsung menetapkan `Fail`.

## D. Entri dan Keluar (Entry / Exit Criteria)

**Entry Criteria:**
- Website dapat diakses.
- Browser dan resolusi pengujian telah ditentukan.
- Test case telah disiapkan.
- Source code tersedia untuk White Box Testing.
- Izin pengujian telah diperoleh apabila diperlukan.
- Risiko penggunaan akun dan data produksi telah dikenali.

**Exit Criteria:**
- Seluruh test case yang aman telah dijalankan atau status `Blocked` dijelaskan.
- Evidence terhubung dengan hasil pengujian.
- Setiap hasil `Fail` memiliki bug report.
- Pengujian Black Box dan White Box dilaporkan secara terpisah.
- QA profesional telah memberikan evaluasi jika sesi evaluasi dilaksanakan.
- Kesimpulan mencantumkan risiko dan keterbatasan pengujian.

## E. Environment Recording

Isi saat pelaksanaan pengujian:

- Tester dan tanggal/jam: [isi]
- OS / perangkat: [isi]
- Chrome: [versi]
- Edge: [versi]
- Resolusi desktop dan mobile: [isi]
- Jenis koneksi: [isi]
- Website version/snapshot: [isi jika diketahui]
- Source code tersedia: [ya/tidak]
- Izin pengujian: [ya/tidak]

## F. Defect Severity

- **Critical:** fungsi utama aplikasi tidak dapat digunakan dan berdampak sangat besar.
- **High:** fitur utama seperti login atau registrasi gagal tanpa alternatif yang wajar.
- **Medium:** fungsi terganggu tetapi masih terdapat alternatif penggunaan.
- **Low:** kesalahan tampilan atau teks yang tidak berdampak signifikan terhadap fungsi utama.

## G. Risks & Mitigation

1. Website dapat berubah sewaktu-waktu: catat tanggal pengujian dan kondisi website.
2. Pengujian login membutuhkan akun: gunakan akun pengujian yang telah diizinkan.
3. Data produksi berisiko berubah: hindari perubahan data tanpa persetujuan.
4. Source code belum tersedia: jadwalkan White Box Testing setelah source code diperoleh.
5. Aturan validasi belum diketahui: tandai `Needs Clarification`.
6. Screenshot dapat mengandung data pribadi: sensor sebelum diunggah ke repository publik.
7. Waktu respons dipengaruhi jaringan: lakukan pengukuran berulang dan catat kondisinya.

## H. Deliverables

- `docs/test-plan.md`
- `test-cases/test-cases.xlsx`
- `test-cases/test-cases.csv`
- `evidence/`
- `bug-reports/`
- `results/test-execution-report.md`
- `interview/`
- README.md yang diperbarui sesuai progres pengujian.

## I. Status Pelaksanaan

| Jenis Pengujian | Status |
|---|---|
| Black Box Testing | Belum dijalankan |
| White Box Testing | Menunggu source code |
| Dokumentasi Test Case | Dalam persiapan |
| Bug Reporting | Menunggu hasil pengujian |
| Evaluasi QA Professional | Belum dilakukan |

## J. Kesimpulan

Test Plan ini digunakan sebagai pedoman pelaksanaan pengujian Cleaning Service System dengan metode Black Box Testing dan White Box Testing. Pengujian dilakukan untuk mengevaluasi fungsi, validasi, struktur kode, serta kualitas aplikasi. Hasil pengujian akan digunakan sebagai dasar dalam penyusunan laporan dan evaluasi kualitas perangkat lunak.