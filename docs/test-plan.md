# Test Plan — Cleaning Service System

| Elemen | Rincian |
|---|---|
| Project ID | QA-CLEANING-2026 |
| Version | 2.0 (revisi acuan ISO/IEC/IEEE 29119) |
| Dibuat | 23 November 2026 |
| Object | https://axioo-production.my.id/cleaning/auth/login.php |
| Tujuan | Menilai fungsi aplikasi, validasi input, alur autentikasi, dan pengalaman penggunaan |
| Pendekatan | Manual Black Box Testing; White Box Testing hanya jika source code dan izin tersedia |
| Acuan | ISO/IEC/IEEE 29119, berdasarkan artikel pengantar Stuart Reid yang diberikan |
| Jumlah kasus | 30 test case yang diselaraskan dengan fitur inventarisasi dan transaksi barang |
| Lingkungan aktual eksekusi | Windows; Python 3.13.7; pytest 9.2.0; pytest-playwright 0.10.0; Chromium headed |
| Status setelah eksekusi gabungan | 29 Passed; 0 Failed; 0 Skipped; 1 warning. TC-001 dan TC-002 tercatat PASS historis di luar sesi gabungan. |

## A. Tujuan dan Ruang Lingkup

Pengujian dilakukan untuk mengetahui apakah fitur Cleaning Service System berjalan sesuai kebutuhan dan hasil yang diharapkan. Perencanaan, desain, persiapan lingkungan, eksekusi, pemantauan, pelaporan insiden, dan penyelesaian didokumentasikan dengan mengacu pada proses yang dijelaskan dalam ISO/IEC/IEEE 29119 Part 2.

**Black Box Testing** dilakukan melalui antarmuka aplikasi tanpa memeriksa struktur internal kode. Ruang lingkupnya meliputi:
- Halaman login dan registrasi jika tersedia serta dapat diakses.
- Validasi username dan password.
- Input kosong dan input tidak valid.
- Proses autentikasi dan navigasi setelah login.
- Akses halaman yang memerlukan autentikasi.
- Tampilan pada resolusi dan browser berbeda.
- Respons aplikasi terhadap input pengguna.

**White Box Testing** meliputi pemeriksaan pernyataan, percabangan, dan jalur logika program. Pelaksanaan hanya dilakukan jika source code dan izin dari pemilik aplikasi tersedia.

**Di luar scope:** penetration testing, load/stress testing, perubahan database produksi, brute force, serta tindakan yang membutuhkan kredensial atau izin khusus.

## B. Acuan proses ISO/IEC/IEEE 29119 Part 2

| Proses | Penerapan pada proyek |
|---|---|
| Organizational Test Process | Tidak membuat kebijakan organisasi formal; mengikuti aturan dan batasan pengujian yang disepakati |
| Test Planning | Menentukan konteks, ruang lingkup, risiko, strategi, sumber daya, lingkungan, dan kriteria selesai |
| Test Monitoring and Control | Memantau status test case, progres, kendala, dan tindakan tindak lanjut |
| Test Completion | Menyusun ringkasan hasil, cakupan, bug, risiko tersisa, dan keterbatasan |
| Test Design and Implementation | Menyusun kondisi, data, langkah, expected result, prioritas, dan traceability test case |
| Test Environment Set-Up and Maintenance | Menyiapkan serta mencatat browser, perangkat, resolusi, akun uji, koneksi, dan alat |
| Test Execution | Menjalankan test procedure dan mencatat actual result serta evidence |
| Test Incident Reporting | Mencatat masalah/insiden yang ditemukan dan menghubungkannya dengan test case |

## C. Strategi dan prioritas berbasis risiko

Pengujian diprioritaskan berdasarkan dampak kegagalan dan kemungkinan terjadinya risiko. Prioritas ditinjau kembali apabila ditemukan informasi atau hasil pengujian baru.

| Prioritas | Panduan penetapan | Contoh |
|---|---|---|
| High | Risiko berdampak besar pada fungsi utama, autentikasi, atau perlindungan akses/data | Login tidak berfungsi; halaman privat dapat diakses tanpa autentikasi |
| Medium | Mengganggu fungsi pendukung atau alur penggunaan, tetapi dampaknya terbatas | Validasi tidak jelas; navigasi tertentu gagal |
| Low | Dampak kecil dan tidak menghalangi fungsi utama | Ketidakteraturan tampilan yang tidak menghalangi penggunaan |

Klasifikasi ini adalah skala kerja tim, bukan skala yang ditentukan secara numerik oleh standar. Urutan eksekusi mengikuti prioritas risiko, dengan tetap memperhatikan dependensi antar-test case.

## D. Quality focus dan kriteria operasional

Aspek berikut merupakan fokus evaluasi proyek, bukan klaim bahwa nama aspek ini merupakan daftar karakteristik kualitas yang ditetapkan oleh ISO/IEC/IEEE 29119.

| Fokus pengujian | Kebutuhan yang diamati | Kriteria operasional |
|---|---|---|
| Fungsi dan validasi | Pengguna dapat menjalankan fungsi sesuai hak akses | Hasil aktual sesuai expected result yang diturunkan dari fitur/kebutuhan |
| Interaksi dan usability | Pengguna memahami navigasi dan pesan sistem | Navigasi dapat dipahami; pesan validasi membantu koreksi |
| Compatibility | Aplikasi dapat digunakan pada browser dan ukuran layar yang diuji | Elemen penting tidak terpotong atau saling menimpa |
| Performance observation | Sistem memberikan respons yang dapat digunakan | Waktu respons dicatat berulang pada kondisi yang didokumentasikan |
| Reliability observation | Hasil skenario konsisten | Pengulangan skenario tidak menghasilkan error yang tidak diharapkan |
| Security-related functional checks | Akses dan autentikasi berjalan sesuai hak pengguna | Input tidak valid ditolak; halaman terbatas memerlukan autentikasi sesuai aturan |

## E. Teknik desain pengujian (rujukan Part 4)

### Black Box / specification-based
- **Equivalence Partitioning:** membagi input ke kelas valid dan tidak valid.
- **Boundary Value Analysis:** menguji nilai di sekitar batas yang diketahui atau telah disepakati.
- **Decision Table Testing:** digunakan untuk kombinasi kondisi yang menghasilkan tindakan berbeda.
- **State Transition Testing:** digunakan untuk fitur dengan status atau perpindahan kondisi.
- **Positive dan Negative Testing:** variasi input benar dan salah sebagai pendekatan penyusunan skenario.
- **Exploratory Testing / Error Guessing:** eksplorasi terarah berdasarkan risiko dan pengalaman.

### White Box / structure-based
- **Statement Testing:** memeriksa eksekusi pernyataan kode yang relevan.
- **Branch/Decision Testing:** memeriksa cabang keputusan yang relevan.
- **Path Testing:** memeriksa jalur logika terpilih sesuai ruang lingkup dan kompleksitas.

Teknik dipilih sesuai fitur dan test basis. Tidak semua teknik harus diterapkan pada setiap test case. White Box Testing tidak dapat dinyatakan selesai tanpa source code dan izin yang sesuai.

## F. Test Design, traceability, dan dokumentasi

Setiap test case harus mencatat sekurang-kurangnya:
- ID dan fitur/kondisi yang diuji.
- Prioritas berdasarkan risiko.
- Prasyarat dan test data.
- Langkah/prosedur pengujian.
- Expected Result.
- Actual Result, status, tanggal/waktu, dan tester setelah eksekusi.
- Evidence dan tautan ke bug report jika ditemukan insiden.

Test case ditelusurkan ke fitur atau kebutuhan yang menjadi test basis. Bug report harus merujuk ke test case yang memunculkannya. Dokumen utama mengikuti keluaran dokumentasi yang dijelaskan pada Part 3, disesuaikan dengan skala tugas kelompok.

## G. Entry / Exit Criteria

**Entry Criteria:**
- Website dapat diakses dan objek pengujian telah dipastikan.
- Ruang lingkup, test case, prioritas, dan expected result telah ditinjau.
- Browser/perangkat pengujian telah ditentukan.
- Akun uji dan izin telah tersedia jika diperlukan.
- Source code tersedia jika White Box Testing akan dilakukan.
- Risiko terhadap data produksi telah dikenali dan mitigasinya ditetapkan.

**Exit Criteria:**
- Semua test case yang aman telah dijalankan atau status Blocked/Needs Clarification dijelaskan.
- Actual Result dan evidence dicatat.
- Setiap Fail yang terkonfirmasi memiliki bug report.
- Hasil Black Box dan White Box dilaporkan terpisah.
- Risiko yang belum diuji dan keterbatasan dicantumkan.
- Laporan penyelesaian telah diperbarui.

## H. Test Environment

Lingkungan yang tercatat pada eksekusi gabungan:
- Sistem operasi: Windows (platform win32).
- Terminal: Windows PowerShell.
- Direktori proyek lokal: `C:\\xampp\\htdocs\\tester`.
- Python: 3.13.7.
- pytest: 9.2.0.
- Plugin: pytest-playwright 0.10.0.
- Browser: Chromium.
- Mode: headed (`--headed`).
- Framework otomasi: Playwright + pytest.
- URL aplikasi: https://axioo-production.my.id/cleaning/auth/login.php.
- Resolusi, versi build website, dan detail jaringan: tidak dicatat pada log eksekusi yang tersedia.
- Kredensial tidak dicantumkan dalam dokumentasi repository.

## I. Incident / Defect Severity

- **Critical:** fungsi utama tidak dapat digunakan atau terdapat dampak sangat besar terhadap akses/data.
- **High:** fitur utama seperti login gagal tanpa alternatif wajar atau akses tidak sesuai hak.
- **Medium:** fungsi terganggu tetapi masih ada alternatif.
- **Low:** kesalahan tampilan/teks dengan dampak kecil.

Severity ditetapkan berdasarkan dampak yang teramati. Jangan menyatakan dugaan penyebab teknis sebagai fakta tanpa bukti.

## J. Risks & Mitigation

1. Website berubah selama pengujian: catat tanggal, waktu, dan kondisi/snapshot jika tersedia.
2. Login membutuhkan akun: gunakan akun uji yang diizinkan.
3. Data produksi berisiko berubah: jangan membuat/mengubah/menghapus data tanpa persetujuan.
4. Source code belum tersedia: tandai White Box sebagai belum dapat dilaksanakan.
5. Aturan validasi belum diketahui: tandai `Needs Clarification`, jangan langsung menyatakan Fail.
6. Evidence berpotensi memuat data pribadi: sensor sebelum disimpan atau dipublikasikan.
7. Waktu respons dipengaruhi jaringan/perangkat: lakukan pengukuran berulang dan catat kondisi.
8. Risiko pengujian dapat berubah: tinjau prioritas selama Test Monitoring and Control.

## K. Deliverables

- `docs/test-plan.md`
- `docs/methodology-and-standard.md`
- `test-cases/test-cases.csv` dan `.xlsx`
- `evidence/`
- `bug-reports/`
- `results/test-execution-report.md`
- `interview/`
- `README.md`

## L. Status Pelaksanaan

| Aktivitas | Status |
|---|---|
| Test Planning | Dokumen rencana tersedia dan diselaraskan dengan fitur aktual |
| Test Design and Implementation | 30 test case diperbarui sesuai skenario yang diuji |
| Test Environment Set-Up | Lingkungan eksekusi dicatat: Windows, Python 3.13.7, pytest 9.2.0, Playwright 0.10.0, Chromium |
| Test Execution | 29 test dieksekusi dalam sesi gabungan: 29 Passed, 0 Failed, 0 Skipped |
| Test Monitoring and Control | Hasil dan satu warning dicatat dalam execution report |
| Test Incident Reporting | Tidak ada test gagal pada sesi gabungan; warning pytest dicatat sebagai catatan teknis |
| Test Completion | Laporan hasil diperbarui; bukti screenshot perlu disalin ke repository sebelum publikasi |
| TC-001 dan TC-002 | PASS historis di luar sesi gabungan; bukti khusus TC-002 belum teridentifikasi |
| White Box Testing | Belum dilakukan; menunggu source code dan izin |

## M. Kesimpulan

Test Plan ini mengadaptasi proses dan dokumentasi pengujian yang dijelaskan dalam artikel pengantar ISO/IEC/IEEE 29119. Fokus penerapan adalah perencanaan berbasis risiko, desain test case yang dapat ditelusuri, persiapan lingkungan, eksekusi, pemantauan, pelaporan insiden, dan penyelesaian. Dokumen ini merupakan pedoman proyek akademik, bukan bukti sertifikasi atau pemenuhan penuh seluruh persyaratan standar.
