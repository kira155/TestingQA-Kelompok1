# Riwayat Perubahan Proyek QA

## 28 November 2026 — local component execution and source review

- Menambahkan 27 hasil pengujian lokal (25 PASS, 2 FAIL) dengan metode native HTTP, fixture HTTP dan DOM offline.
- Menambahkan review White Box 10 area, 5 defect OPEN, dan mapping status 10 regression case.
- Menambahkan screenshot offline, log, trace fixture, dan harness reproduksi.
- Database nyata, manual end-to-end, dan wawancara masih belum selesai; hasil 23 November tidak diubah.


## 24 November 2026 — QA evidence & regression readiness

### Repository pengujian (perubahan yang dapat di-commit)

- Memastikan 37 screenshot PNG disertakan dalam `evidence/` (36 direferensikan oleh 30 test case dan satu screenshot inspeksi tambahan).
- Memperbaiki README, laporan, dan panduan GitHub yang sebelumnya menyebut screenshot belum tersedia.
- Menambahkan `scripts/verify_repository.py`: memeriksa 30 ID, referensi evidence, tanda tangan PNG, dan tautan Markdown.
- Menambahkan workflow GitHub Actions di `.github/workflows/qa-integrity.yml` untuk menjalankan pemeriksaan setiap push/pull request.
- Menambahkan 10 rencana regression test versi source 24 November 2026 di `docs/regression-plan-2026-11-24.md`, semuanya masih **NOT RUN**.

### Source Cleaning (paket ZIP terpisah)

- Rebranding footer utama dan footer login menjadi `Cleaning Stock Management`.
- Peningkatan aksesibilitas/interaction menu mobile.
- Registrasi publik ditetapkan `staff`; role admin dikelola oleh administrator.
- Setup admin hanya melalui CLI, bukan browser publik.
- Perbaikan redirect pada registrasi dan halaman akses ditolak.
- Koneksi database mendukung environment variables dan respons error yang tidak membuka detail internal.
- Pemeriksaan syntax PHP: **27 file, 27 lulus**. Pengujian runtime aplikasi versi baru belum dilakukan.

### Hasil historis tidak berubah

Sesi pengujian gabungan **23 November 2026** tetap **29 passed, 0 failed, 0 skipped**, dengan satu warning. TC-001 dan TC-002 merupakan PASS historis di luar sesi tersebut. Bukti khusus TC-002 tetap belum teridentifikasi. Tidak ada klaim regresi baru yang sudah PASS.
