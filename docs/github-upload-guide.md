# Panduan Push Pembaruan QA ke GitHub

<<<<<<< HEAD
## Apa yang berubah pada pembaruan 24 November 2026?

- `README.md`, `docs/CHANGELOG.md` dan `docs/regression-plan-2026-11-24.md` memuat progres dan rencana regression (bukan hasil baru yang diklaim PASS).
- `evidence/` sudah memuat **37 file PNG**; tidak perlu menyalin bukti lagi dari folder `C:\xampp`.
- `scripts/verify_repository.py` memeriksa integritas file; `.github/workflows/qa-integrity.yml` akan otomatis menjalankannya saat push.
- Paket perubahan source website berada pada **ZIP Cleaning terpisah**, bukan folder repository QA ini.
=======
## File hasil pembaruan

- `results/test-execution-report.md` — laporan utama untuk ditampilkan GitHub.
- `results/Test_Execution_Report.docx` — salinan laporan Word.
- `test-cases/test-cases.csv` dan `test-cases/test-cases.xlsx` — 30 test case yang telah diselaraskan dengan skenario aktual.

## Upload melalui website GitHub

1. Login ke https://github.com/ dan buka repository kelompok.
2. Ekstrak ZIP terbaru.
3. Upload isi folder `TestingQA-Kelompok1-main/` ke repository dengan mempertahankan struktur folder.
4. Salin screenshot asli dari folder lokal `C:\xampp\htdocs\tester\evidence` ke folder `evidence/` repository. Screenshot belum dimasukkan ke ZIP ini.
5. Periksa `results/test-execution-report.md` dan pastikan tautan evidence dapat dibuka setelah gambar diunggah.
6. Gunakan pesan commit: `docs: update test execution report and actual test cases`.
7. Periksa README agar ringkasan hasil dan status progres tampil dengan benar.
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670

## Cara 1: Git CLI (direkomendasikan)

<<<<<<< HEAD
Bila repo sudah di-clone sebelumnya, **jangan menjalankan `git init` ulang**. Salin seluruh isi folder hasil ZIP ini ke root repo lokal, termasuk folder tersembunyi `.github`, lalu jalankan:

```bash
git status
python scripts/verify_repository.py
git add README.md .github docs evidence scripts results test-cases
git commit -m "test: add QA integrity workflow and November regression plan"
git push origin main
```

Ganti `main` dengan nama branch aktual repo bila berbeda. Jika ada perubahan lokal dari anggota lain, lihat `git status` dan lakukan merge secara hati-hati sebelum push.

## Cara 2: Upload GitHub Website

1. Buka repository GitHub, lalu pilih **Add file → Upload files**.
2. Unggah isi root folder hasil ekstraksi; pertahankan semua subfolder.
3. **Perhatian:** folder tersembunyi `.github/workflows/` sebaiknya ditambahkan melalui Git CLI bila tidak terunggah melalui browser.
4. Commit perubahan dengan pesan: `test: add QA integrity workflow and November regression plan`.
5. Periksa tab **Actions** dan pastikan workflow integritas sukses.

## Checklist sebelum dipublikasikan

- [ ] Bukti screenshot diperiksa agar tidak menampilkan data rahasia/pribadi.
- [ ] `python scripts/verify_repository.py` berhasil.
- [ ] 37 screenshot benar-benar muncul di GitHub.
- [ ] README menjelaskan bahwa 29 PASS berasal dari sesi 23 November 2026.
- [ ] Regression versi baru ditandai NOT RUN sampai benar-benar diuji.
- [ ] Source web tidak ditaruh tanpa sengaja di repo QA jika repository dikhususkan untuk dokumentasi pengujian.
=======
Jalankan dari folder repository hasil ekstraksi:

```bash
git add README.md docs/test-plan.md test-cases results evidence
git commit -m "docs: update test execution report and actual test cases"
git push
```

## Checklist sebelum publish

- [ ] README menampilkan hasil eksekusi: 29 passed, 0 failed, 0 skipped, 1 warning.
- [ ] TC-001 dan TC-002 ditandai sebagai hasil historis, bukan bagian sesi gabungan.
- [ ] CSV dan XLSX berisi skenario TC-001 sampai TC-030 yang sama.
- [ ] Screenshot asli sudah disalin ke `evidence/`.
- [ ] Tautan screenshot pada laporan dapat dibuka.
- [ ] Tidak ada password, token, atau data pribadi dalam file dan screenshot.
- [ ] White Box Testing tidak diklaim telah dilakukan.
- [ ] Batasan pengujian dicantumkan secara jelas.
>>>>>>> 8a815c52c9f38ad2d284ff15ad7176872bd6d670
