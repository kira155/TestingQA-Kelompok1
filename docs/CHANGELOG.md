# Riwayat Perubahan Proyek QA

## 24 November 2026 — QA evidence & regression readiness

### Repository pengujian (perubahan yang dapat di-commit)

- Memastikan 37 screenshot PNG disertakan dalam `evidence/` (36 direferensikan oleh 30 test case dan satu screenshot inspeksi tambahan).
- Memperbaiki README, laporan, dan panduan GitHub yang sebelumnya menyebut screenshot belum tersedia.
- Menambahkan `scripts/verify_repository.py`: memeriksa 30 ID, referensi evidence, tanda tangan PNG, dan tautan Markdown.
- Menambahkan workflow GitHub Actions di `.github/workflows/qa-integrity.yml` untuk menjalankan pemeriksaan setiap push/pull request.
- Menambahkan 10 rencana regression test versi source 24 November 2026 di `docs/regression-plan-2026-11-24.md`, semuanya masih **NOT RUN**.

### Source Cleaning (riwayat versi sebelum penggabungan)

- Rebranding footer utama dan footer login menjadi `Cleaning Stock Management`.
- Peningkatan aksesibilitas/interaction menu mobile.
- Registrasi publik ditetapkan `staff`; role admin dikelola oleh administrator.
- Setup admin hanya melalui CLI, bukan browser publik.
- Perbaikan redirect pada registrasi dan halaman akses ditolak.
- Koneksi database mendukung environment variables dan respons error yang tidak membuka detail internal.
- Pemeriksaan syntax PHP: **27 file, 27 lulus**. Pengujian runtime aplikasi versi baru belum dilakukan.

### Hasil historis tidak berubah

Sesi pengujian gabungan **23 November 2026** tetap **29 passed, 0 failed, 0 skipped**, dengan satu warning. TC-001 dan TC-002 merupakan PASS historis di luar sesi tersebut. Bukti khusus TC-002 tetap belum teridentifikasi. Tidak ada klaim regresi baru yang sudah PASS.

## 25 November 2026 – audit kesiapan QA manual dan ISO 29119

- Memperbaiki konflik Git Merge pada README, CSV test case, report, dan panduan upload.
- Memisahkan fakta hasil historis Playwright dari tugas manual yang masih NOT RUN.
- Menambahkan gap analysis, RTM, manual test protocol, white-box plan, dan environment readiness.
- Menyiapkan source-level findings dan rencana regression untuk patch aplikasi.
- Source website versi baru tetap memerlukan integration testing; hasil pengujian manual belum dicatat karena belum dijalankan.
