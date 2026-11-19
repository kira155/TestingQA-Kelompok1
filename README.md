# Manual Software Testing — Bafageh Bakery

> **Proyek kuliah QA | Objek: [Bafageh Bakery](https://bakery.bafageh.com/) | Status: Perencanaan / Not Run**

[![Manual Testing](https://img.shields.io/badge/Testing-Manual-blue)](#metodologi) [![Test cases](https://img.shields.io/badge/Test%20Cases-30-orange)](test-cases/test-cases.csv) [![Status](https://img.shields.io/badge/Execution-Not%20Run-lightgrey)](results/test-execution-report.md)

## Gambaran proyek

Repository ini mendokumentasikan **perencanaan, pelaksanaan, dan pelaporan manual Black Box Testing** terhadap website publik milik usaha bakery lokal. Tugas berfokus pada pengalaman pengguna dan fungsi yang bisa dinilai tanpa akses source code atau server.

- **Website diuji:** https://bakery.bafageh.com/
- **Jenis:** Manual testing; Black Box (teknik Equivalence Partitioning, Boundary Value Analysis, positive/negative testing, eksplorasi)
- **Landasan kerangka kualitas:** ISO/IEC 25002:2024 (SQuaRE — Quality model overview and usage)
- **Sumber model kualitas produk:** ISO/IEC 25010 (dirujuk dalam standar 25002; karakteristik dipilih menurut konteks)
- **Rencana test case:** 30, semua berstatus `Not Run` sampai dieksekusi secara nyata
- **Tanggal penyusunan:** 19 November 2026

> **Catatan standar:** ISO/IEC 25002:2024 menjadi **kerangka pengelolaan model kualitas**, bukan standar prosedur test case/teknik Black Box. Standar ini **bukan** standar IEEE dengan penerbitan bersama. Bila tugas mewajibkan referensi IEEE 2022–2026, tambahkan dokumen IEEE/ISO/IEC/IEEE yang relevan dan disetujui dosen.

## Struktur repository

```text
QA_Manual_Testing_Bafageh_Bakery/
├── README.md
├── .gitignore
├── docs/
│   ├── test-plan.md
│   ├── methodology-and-standard.md
│   └── github-upload-guide.md
├── test-cases/
│   ├── test-cases.csv
│   └── test-cases.xlsx
├── evidence/
│   ├── README.md
│   └── .gitkeep
├── bug-reports/
│   └── BUG-TEMPLATE.md
├── results/
│   └── test-execution-report.md
└── interview/
    ├── qa-interview-questions.md
    └── qa-validation-template.md
```

## Metodologi

1. **Reconnaissance:** amati fitur publik website, tautan navigasi, katalog, galeri, dan formulir.
2. **Quality requirements:** turunkan kebutuhan pengguna lalu pilih atribut kualitas yang relevan.
3. **Test design:** susun langkah, input, expected result, prioritas, risiko, dan izin yang dibutuhkan.
4. **Test execution:** jalankan kasus satu per satu; isi Actual Result dan Status (`Pass`, `Fail`, `Blocked`, `Not Run`, `Needs Clarification`).
5. **Evidence:** simpan screenshot bernama `TC-001.png` dst., beri catatan lingkungan dan tanggal.
6. **Defect tracking:** untuk yang benar-benar gagal, isi `bug-reports/BUG-TEMPLATE.md` per temuan.
7. **Reporting:** perbarui `results/test-execution-report.md`, lalu lakukan wawancara/validasi QA profesional.

## Cakupan pengujian

| Aspek | ID | Jumlah |
|---|---|---:|
| Functional Suitability | TC-001 s.d. TC-022 | 22 |
| Interaction Capability | TC-023 s.d. TC-025 | 3 |
| Compatibility | TC-026 s.d. TC-028 | 3 |
| Performance Efficiency | TC-029 s.d. TC-030 | 2 |
| **Total** | | **30** |

Reliability menjadi pengamatan lintas-kasus (jika terjadi error atau ketidakstabilan), bukan kelompok tersendiri agar 30 kasus tetap fokus pada fitur publik.

## Cara menggunakan test case

1. Buka `test-cases/test-cases.xlsx` di Excel atau `test-cases.csv` di spreadsheet editor.
2. Jalankan langkah sesuai kolom `Langkah_Pengujian`.
3. Tulis `Actual_Result` berdasarkan perilaku faktual (bukan dari asumsi).
4. Ubah `Status` menjadi `Pass`, `Fail`, `Blocked`, atau `Needs Clarification`.
5. Lampirkan screenshot `evidence/TC-xxx.png` dan rujuk kolom `Evidence`.
6. Lakukan commit untuk setiap batch pengujian agar perubahan terdokumentasi.

## Etika dan batasan

**Jangan mengirim pesanan palsu kepada bisnis nyata.** Kasus yang dapat memicu pengiriman pesan/pesanan hanya boleh dijalankan setelah izin pemilik atau menggunakan lingkungan pengujian yang disepakati. Test case memuat kolom penanda izin. Jangan unggah nomor telepon atau data pribadi orang lain ke GitHub.

**Jangan unggah PDF berlisensi ISO/IEC 25002:2024 ke repository publik.** Repository cukup memuat identitas referensi, tautan resmi, nomor klausul, dan interpretasi asli; salinan berlisensi pribadi harus tetap privat.

## Status pekerjaan

- [x] Menentukan website objek
- [x] Menyusun test plan dan 30 test case
- [ ] Menjalankan pengujian nyata
- [ ] Mengumpulkan screenshot evidence
- [ ] Membuat bug report berdasarkan temuan
- [ ] Wawancara QA profesional
- [ ] Meminta validasi hasil dari QA profesional
- [ ] Finalisasi hasil serta kesimpulan

## Sumber

1. ISO/IEC. (2024). *ISO/IEC 25002:2024 — Systems and software engineering — SQuaRE — Quality model overview and usage*. **Dokumen berlisensi, tidak disertakan di repository.**
2. Website objek: https://bakery.bafageh.com/ (diakses 19 November 2026).
3. ISO/IEC 25010: Product quality model (diidentifikasi sebagai model produk yang digunakan dalam keluarga SQuaRE; verifikasi edisi dan akses sesuai ketentuan kampus).

## Penanggung jawab

Mahasiswa / tim: **isi nama, NIM, kelas dan dosen pengampu sebelum publikasi.**
