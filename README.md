# Pengujian Website Cleaning Service System

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

Objek pengujian pada tugas ini adalah Cleaning Service System, yaitu aplikasi berbasis web yang digunakan sebagai sistem layanan cleaning service.

Website: https://axioo-production.my.id/cleaning/auth/login.php

Pengujian dilakukan berdasarkan fitur yang dapat diakses melalui browser tanpa menggunakan source code maupun akses database aplikasi.

## Tujuan

Tujuan pengujian ini adalah memastikan fitur-fitur pada website Cleaning Service System berjalan sesuai dengan fungsinya, mengetahui kemungkinan kesalahan sistem, serta mengevaluasi kualitas dan kemudahan penggunaan aplikasi.

## Metode yang digunakan

Pengujian menggunakan dua metode, yaitu Black Box Testing dan White Box Testing.

Black Box Testing merupakan pengujian perangkat lunak yang berfokus pada fungsi aplikasi berdasarkan input dan output tanpa memeriksa struktur kode program. Pengujian dilakukan untuk memastikan setiap fitur berjalan sesuai dengan kebutuhan.

White Box Testing merupakan pengujian perangkat lunak yang berfokus pada struktur internal dan alur kode program. Pengujian dilakukan dengan memeriksa logika program, kondisi, percabangan, serta jalur eksekusi kode.

Teknik yang digunakan pada skenario tertentu meliputi:

Black Box Testing:
Positive Testing
Negative Testing
Equivalence Partitioning
Boundary Value Analysis

White Box Testing:
Statement Coverage
Branch Coverage
Path Testing

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
