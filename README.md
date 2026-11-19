# Pengujian Website Bafageh Bakery

**Tugas Kelompok 1 – Testing & Quality Assurance**  
Program Studi Teknik Informatika, Universitas Pamulang

## Anggota kelompok

| No. | Nama | NIM |
|---|---|---|
| 1 | Abdul Kholik | 231011400097 |
| 2 | Ahmad Reza | 231011403385 |
| 3 | Akbar Rusmanto | 231011401424 |
| 4 | Adjie Febrianto | 231011400092 |

## Objek pengujian

Kami memilih website [Bafageh Bakery](https://bakery.bafageh.com/) sebagai objek tugas. Website ini digunakan untuk mengenalkan produk bakery serta menyediakan informasi dan formulir permintaan pesanan.

Pengujian difokuskan pada fitur yang dapat diakses pengunjung, seperti menu navigasi, katalog produk, galeri, dan formulir pemesanan. Kami tidak memiliki akses ke kode program maupun database website.

## Tujuan

Tujuan pengujian ini adalah mengetahui apakah fitur utama website dapat digunakan sesuai kebutuhan pengunjung. Selain fungsi website, kami juga memperhatikan kemudahan penggunaan, tampilan pada perangkat yang berbeda, dan waktu respons halaman.

## Metode yang digunakan

Pengujian dilakukan secara **manual** dengan pendekatan **Black Box Testing**. Kami menyiapkan skenario berdasarkan cara pengguna berinteraksi dengan website, kemudian membandingkan hasil yang diharapkan dengan hasil yang benar-benar muncul.

Teknik yang digunakan pada skenario tertentu meliputi:

- **Positive testing:** mencoba fitur dengan masukan yang sesuai.
- **Negative testing:** mencoba masukan yang salah atau tidak lengkap.
- **Equivalence Partitioning:** mengelompokkan contoh masukan valid dan tidak valid.
- **Boundary Value Analysis:** memeriksa nilai di sekitar batas masukan yang ditentukan.

Acuan kualitas yang digunakan adalah **ISO/IEC 25002:2024**, khususnya pemilihan aspek kualitas, penentuan kebutuhan, serta evaluasi hasil. Standar ini menjadi kerangka penilaian kualitas, bukan panduan khusus untuk menjalankan Black Box Testing.

## Rencana pengujian

Kami telah menyusun **30 test case** dengan pembagian berikut:

| Aspek yang diuji | ID test case | Jumlah |
|---|---|---:|
| Functional Suitability dan validasi input | TC-001 – TC-022 | 22 |
| Interaction Capability / kemudahan penggunaan | TC-023 – TC-025 | 3 |
| Compatibility | TC-026 – TC-028 | 3 |
| Performance Efficiency | TC-029 – TC-030 | 2 |
| **Total** | | **30** |

Rencana pengujian serta tabel test case tersedia pada berkas berikut:

- [Test Plan](docs/test-plan.md)
- [Penjelasan metode dan standar](docs/methodology-and-standard.md)
- [Daftar test case (CSV)](test-cases/test-cases.csv)
- [Daftar test case (Excel)](test-cases/test-cases.xlsx)
- [Format laporan bug](bug-reports/BUG-TEMPLATE.md)
- [Laporan hasil pengujian](results/test-execution-report.md)
- [Pertanyaan wawancara QA](interview/qa-interview-questions.md)
- [Form validasi QA](interview/qa-validation-template.md)

## Cara pelaksanaan

Setiap test case dijalankan satu per satu melalui browser. Hasil yang muncul dicatat pada kolom **Actual Result**, kemudian dibandingkan dengan **Expected Result** untuk menentukan status **Pass** atau **Fail**. Jika ada kendala yang membuat kasus tidak dapat dijalankan, status dan alasannya juga dicatat.

Screenshot akan disimpan pada folder `evidence/` dengan nama sesuai ID test case, misalnya `TC-001.png`. Jika ditemukan masalah, langkah untuk mengulangi masalah tersebut dicatat dalam laporan bug.

Pengujian formulir dibatasi agar tidak mengirim pesanan yang tidak benar-benar dimaksudkan kepada pemilik usaha.

## Progres pekerjaan

| Kegiatan | Status |
|---|---|
| Pemilihan website | Selesai |
| Penyusunan test plan | Selesai |
| Penyusunan 30 test case | Selesai |
| Pelaksanaan pengujian dan pengambilan screenshot | Belum dilakukan |
| Penyusunan laporan bug dan kesimpulan | Menunggu hasil pengujian |
| Wawancara dan validasi QA profesional | Belum dilakukan |

**Catatan:** Seluruh test case masih berstatus `Not Run`. Hasil Pass/Fail dan kesimpulan kualitas website akan diisi setelah pengujian benar-benar dilakukan.

## Referensi

1. ISO/IEC. (2024). *ISO/IEC 25002:2024 — Systems and software engineering — Systems and software Quality Requirements and Evaluation (SQuaRE) — Quality model overview and usage*.
2. Bafageh Bakery. Website resmi: https://bakery.bafageh.com/ (diakses 19 November 2026).

PDF standar berlisensi tidak disertakan di repository. Apabila dosen mewajibkan referensi IEEE dalam rentang 2022–2026, sumber IEEE yang sesuai masih perlu ditambahkan.
