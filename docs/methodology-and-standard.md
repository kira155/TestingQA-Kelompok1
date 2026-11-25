# Pemetaan Metodologi Pengujian ke ISO/IEC/IEEE 29119

**Acuan dokumen:** Stuart Reid, *ISO/IEC/IEEE 29119: The New International Software Testing Standards*. Dokumen ini merupakan artikel pengantar yang menjelaskan struktur dan isi keluarga standar ISO/IEC/IEEE 29119; bukan salinan lengkap teks normatif standar.

## 1. Posisi standar dalam proyek

Proyek ini menggunakan ISO/IEC/IEEE 29119 sebagai acuan proses, dokumentasi, dan teknik pengujian perangkat lunak. Pemilihan Black Box Testing dan White Box Testing merupakan pendekatan pengujian proyek. Standar 29119 menyediakan kerangka proses, dokumentasi, dan teknik desain/pengukuran pengujian; standar tersebut tidak berarti bahwa setiap proyek wajib memakai seluruh teknik yang disebutkan.

Menurut Reid, keluarga ISO/IEC/IEEE 29119 terdiri dari:
- **Part 1 — Concepts and Vocabulary:** konsep dasar, definisi, model siklus hidup, serta peran dan tanggung jawab pengujian.
- **Part 2 — Test Processes:** proses pengujian pada tingkat organisasi, manajemen pengujian, dan pengujian dinamis.
- **Part 3 — Test Documentation:** jenis dan isi dokumentasi pengujian.
- **Part 4 — Test Techniques:** teknik desain test case dan pengukuran cakupan pengujian, termasuk teknik black-box dan white-box.
- **Part 5 — Keyword-Driven Testing:** pendekatan pengujian berbasis kata kunci.
- **ISO/IEC 33063:** penilaian proses pengujian yang berkaitan dengan proses pada Part 2; merupakan standar pelengkap, bukan salah satu dari lima bagian 29119.

## 2. Pemetaan ke pekerjaan kelompok

| Bagian/acuan | Isi standar menurut Reid | Penerapan pada proyek |
|---|---|---|
| Part 1 | Konsep, istilah, model siklus hidup, peran dan tanggung jawab | Menyamakan istilah test plan, test case, test result, defect/incident, tester, dan ruang lingkup |
| Part 2 — Organizational Test Process | Kebijakan dan strategi pengujian tingkat organisasi | Dicatat sebagai konteks acuan; proyek tugas kelompok tidak membuat kebijakan organisasi formal |
| Part 2 — Test Planning | Memahami konteks, menentukan ruang lingkup, mengidentifikasi/menganalisis risiko, strategi, sumber daya dan jadwal, menyusun serta menyepakati test plan | `docs/test-plan.md` memuat tujuan, ruang lingkup, risiko, pendekatan, lingkungan, entry/exit criteria, dan jadwal/penanggung jawab |
| Part 2 — Test Monitoring & Control | Memantau pelaksanaan terhadap rencana dan menetapkan tindakan pengendalian | Memperbarui status test case, progres eksekusi, kendala, dan tindak lanjut |
| Part 2 — Test Completion | Menilai penyelesaian pengujian dan menghasilkan laporan penyelesaian | `results/test-execution-report.md` merangkum hasil, batasan, bug, dan status pekerjaan |
| Part 2 — Test Design & Implementation | Menurunkan test condition, coverage item, test case, test set, dan test procedure; menjaga traceability ke test basis | Test case diberi ID, kondisi, data, langkah, expected result, dan hubungan dengan kebutuhan/fitur |
| Part 2 — Test Environment Set-Up & Maintenance | Menyiapkan, memeriksa, memelihara lingkungan, serta melaporkan kesiapan | Mencatat OS, browser, versi, viewport, koneksi, akun uji, dan kesiapan alat |
| Part 2 — Test Execution | Menjalankan prosedur dan mencatat hasil aktual | Mengisi Actual Result, status, waktu, dan evidence untuk setiap test case |
| Part 2 — Test Incident Reporting | Menganalisis dan melaporkan insiden/masalah selama pengujian | Temuan yang memenuhi kriteria bug dicatat pada `bug-reports/` dan ditautkan ke test case |
| Part 3 | Dokumentasi organisasi, proyek, level pengujian, hasil, anomali, status, dan lingkungan | Test plan, test case, execution report, bug report, dan evidence menjadi dokumentasi proyek |
| Part 4 | Teknik desain berbasis spesifikasi (black-box), berbasis struktur (white-box), serta teknik berbasis pengalaman | Equivalence Partitioning, Boundary Value Analysis, decision table/state transition bila relevan; statement/branch testing bila source code tersedia; exploratory/error guessing secara terarah |
| Part 5 | Keyword-driven testing dan kerangka/alat pendukung | Tidak diterapkan pada proyek ini karena test case dijalankan manual dan tidak menggunakan framework keyword-driven |

## 3. Proses pengujian yang digunakan

Artikel menjelaskan delapan proses pada Part 2:

1. Organizational Test Process.
2. Test Planning.
3. Test Monitoring and Control.
4. Test Completion.
5. Test Design and Implementation.
6. Test Environment Set-Up and Maintenance.
7. Test Execution.
8. Test Incident Reporting.

Dalam tugas kelompok ini, proses organisasi formal tidak dibuat karena objeknya satu proyek akademik. Fokus penerapan berada pada proses manajemen proyek dan proses pengujian dinamis yang dapat dilakukan sesuai akses yang tersedia.

### Alur pelaksanaan proyek

1. **Perencanaan:** menetapkan objek, tujuan, ruang lingkup, risiko, pendekatan, lingkungan, dan kriteria selesai.
2. **Desain dan implementasi test case:** menurunkan fitur/kondisi yang diuji menjadi test case; mencatat test data, expected result, dan prioritas.
3. **Persiapan lingkungan:** memastikan website, browser, akun uji, perangkat, serta pencatatan evidence siap.
4. **Eksekusi:** menjalankan test case, membandingkan hasil aktual dengan expected result, dan menyimpan evidence.
5. **Pemantauan dan pengendalian:** memperbarui status dan menangani test case yang terhambat atau memerlukan klarifikasi.
6. **Pelaporan insiden:** membuat bug report untuk kegagalan yang dapat direproduksi dan didukung bukti.
7. **Penyelesaian:** merangkum cakupan, hasil, risiko tersisa, batasan, dan rekomendasi.

## 4. Risk-Based Testing

PDF menjelaskan bahwa ISO/IEC/IEEE 29119 mendorong pendekatan **risk-based testing**. Risiko dianalisis dan diprioritaskan untuk menentukan urutan pengujian serta jenis pengujian yang paling sesuai. Penilaian risiko perlu ditinjau kembali ketika ditemukan informasi baru.

Penerapan sederhana pada proyek:
- **High:** fungsi inti, autentikasi, akses halaman yang seharusnya terlindungi, dan validasi yang dapat menyebabkan kegagalan layanan atau paparan data.
- **Medium:** fungsi pendukung dan alur yang mengganggu penggunaan tetapi masih memiliki alternatif.
- **Low:** masalah kosmetik atau ketidaknyamanan yang dampaknya terbatas.

Prioritas di atas adalah klasifikasi kerja tim, bukan skala angka yang ditetapkan oleh PDF. Tim perlu menyesuaikannya dengan dampak dan kemungkinan risiko yang benar-benar ditemukan. Pengujian hanya dilakukan pada akun dan lingkungan yang diizinkan.

## 5. Teknik desain test case (Part 4)

| Kelompok teknik | Teknik | Penggunaan dalam proyek |
|---|---|---|
| Specification-based / Black-box | Equivalence Partitioning | Mengelompokkan input valid dan tidak valid, misalnya username/password kosong atau format tidak sesuai |
| Specification-based / Black-box | Boundary Value Analysis | Menguji nilai di sekitar batas panjang input atau batas nilai yang telah ditentukan |
| Specification-based / Black-box | Decision Table Testing | Digunakan bila hasil bergantung pada kombinasi beberapa kondisi, misalnya kombinasi kredensial dan status akun |
| Specification-based / Black-box | State Transition Testing | Digunakan bila fitur memiliki status/alur, misalnya login, logout, atau perubahan status permintaan |
| Experience-based | Error Guessing / Exploratory Testing | Pemeriksaan terarah berdasarkan pengalaman dan eksplorasi; temuan tetap harus dicatat dengan langkah reproduksi |
| Structure-based / White-box | Statement Testing | Mengukur apakah pernyataan kode yang relevan telah dijalankan |
| Structure-based / White-box | Branch/Decision Testing | Memeriksa hasil cabang keputusan yang relevan |

Teknik dipilih sesuai fitur dan test basis yang tersedia. Tidak semua teknik harus digunakan pada setiap fitur. White Box Testing membutuhkan source code dan izin akses; jika source code tidak tersedia, hasil White Box tidak boleh diklaim telah dilakukan.

## 6. Dokumentasi dan traceability (Part 3)

Dokumen proyek dipetakan sebagai berikut:

| Dokumen proyek | Kegunaan |
|---|---|
| `docs/test-plan.md` | Rencana pengujian proyek |
| `test-cases/test-cases.csv` dan `.xlsx` | Spesifikasi test case dan pencatatan hasil |
| `results/test-execution-report.md` | Ringkasan hasil dan status penyelesaian pengujian |
| `bug-reports/BUG-TEMPLATE.md` | Format laporan insiden/bug |
| `evidence/` | Screenshot/video pendukung hasil pengujian |
| `interview/` | Instrumen wawancara dan validasi tambahan |

Setiap test case sebaiknya dapat ditelusuri ke fitur, kebutuhan, atau test basis yang menjadi alasan pengujian. Setiap bug report harus merujuk ke test case terkait. Bukti harus diberi nama sesuai ID test case dan tidak boleh memuat data pribadi atau rahasia.

## 7. Batasan interpretasi

- Artikel Stuart Reid adalah artikel pengantar tentang ISO/IEC/IEEE 29119, bukan keseluruhan dokumen normatif standar.
- Pemetaan ini menunjukkan penggunaan konsep dan struktur yang dijelaskan dalam artikel; bukan sertifikasi atau pernyataan bahwa proyek telah sepenuhnya memenuhi seluruh persyaratan ISO/IEC/IEEE 29119.
- Artikel menjelaskan Part 2 sebagai proses pengujian dinamis. Artikel juga menyebut bahwa proses pengujian statis belum tercakup dalam model Part 2 yang dibahas saat itu.
- Black Box dan White Box adalah pendekatan yang dipilih tim. Teknik yang digunakan harus disesuaikan dengan test basis, risiko, akses, dan karakteristik fitur.
- Target waktu, jumlah test case, severity, serta prioritas yang ditentukan tim merupakan kriteria proyek, bukan angka wajib dari ISO/IEC/IEEE 29119.

## Referensi

Reid, S. (2013). *ISO/IEC/IEEE 29119: The New International Software Testing Standards*.


## Catatan versi dan interpretasi

Materi Stuart Reid dalam lampiran menjelaskan versi awal ISO 29119 (2013). Untuk laporan proyek 2026, rujukan katalog ISO yang dapat diverifikasi adalah ISO/IEC/IEEE 29119-2:2021, -3:2021, dan -4:2021. Pemetaan dalam proyek ini bersifat adaptasi praktis (tailoring) tanpa audit kesesuaian formal. Perbedaan manual vs otomatis harus dilaporkan pada level setiap test case.
