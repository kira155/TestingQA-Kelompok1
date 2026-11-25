# Cleaning Stock Management – Software Testing

Proyek mata kuliah Testing & Quality Assurance, Universitas Pamulang.

## Kelompok 1

Anggota: Abdul Kholik (231011400097), Ahmad Reza (231011403385), Akbar Rusmanto (231011401424), Adjie Febrianto (231011400092).

## Aplikasi

Cleaning Stock Management adalah aplikasi PHP dan MariaDB untuk mengelola persediaan, permintaan barang, serta transaksi masuk dan keluar. Pengujian difokuskan pada login, hak akses, validasi input dan ketepatan saldo stok.

## Metode pengujian

- Black Box: pengujian fungsi, validasi input, Equivalence Partitioning dan Boundary Value Analysis.
- White Box: pemeriksaan alur program, kondisi dan percabangan.
- Regression Testing: pemeriksaan ulang fungsi setelah perubahan kode.

Perencanaan, dokumentasi dan teknik pengujian mengacu pada ISO/IEC/IEEE 29119 Part 2, 3 dan 4.

## Status pengujian

| Kegiatan | Status |
|---|---|
| Sesi Playwright, 23 November 2026 | 29 PASS, 0 FAIL, 1 warning |
| Test case historis | 30 skenario dengan catatan PASS |
| Pemeriksaan source PHP | Selesai |
| Black Box manual versi terbaru | NOT RUN |
| White Box manual versi terbaru | NOT RUN |
| Regression Testing versi terbaru | NOT RUN |
| Wawancara QA | Belum dilakukan |

TC-001 dan TC-002 memiliki hasil dari sesi sebelumnya. Screenshot khusus TC-002 belum tersedia. Hasil Playwright tidak mencakup versi source yang telah diperbaiki.

## Dokumentasi

| Dokumen | Lokasi |
|---|---|
| Rencana pengujian | [Test Plan](docs/test-plan.md) |
| Metode dan standar | [Metodologi](docs/methodology-and-standard.md) |
| Skenario manual | [Black Box](docs/manual-test-execution.md) / [White Box](docs/white-box-manual-review.md) |
| Daftar test case | [Excel](test-cases/test-cases.xlsx) / [CSV](test-cases/test-cases.csv) |
| Pemetaan kebutuhan | [RTM](docs/requirements-traceability-matrix.md) |
| Hasil pengujian | [Test Report](results/test-execution-report.md) |
| Temuan | [Bug Reports](bug-reports/) |
| Bukti | [Evidence](evidence/) |
| Evaluasi standar | [ISO 29119](docs/iso-29119-gap-analysis.md) |
| Wawancara | [QA Interview](interview/qa-interview-questions.md) |

## Instalasi lokal

Source aplikasi, skema database dan data demo tersedia di [cleaning/](cleaning/README.md). Gunakan database dan akun khusus pengujian.

Untuk memeriksa kelengkapan test case dan tautan dokumentasi:

```bash
python scripts/verify_repository.py
```

## Referensi

- ISO/IEC/IEEE 29119-2:2021 – Test processes. https://www.iso.org/standard/79428.html
- ISO/IEC/IEEE 29119-3:2021 – Test documentation. https://www.iso.org/standard/79429.html
- ISO/IEC/IEEE 29119-4:2021 – Test techniques. https://www.iso.org/standard/79430.html
- S. Reid, *ISO/IEC/IEEE 29119: The New International Software Testing Standards*.
