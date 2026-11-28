# Panduan Push Pembaruan QA ke GitHub

## Apa yang berubah pada pembaruan 24 November 2026?

- `README.md`, `docs/CHANGELOG.md` dan `docs/regression-plan-2026-11-24.md` memuat progres dan rencana regression (bukan hasil baru yang diklaim PASS).
- `evidence/` sudah memuat **37 file PNG**; tidak perlu menyalin bukti lagi dari folder `C:\xampp`.
- `scripts/verify_repository.py` memeriksa integritas file; `.github/workflows/qa-integrity.yml` akan otomatis menjalankannya saat push.
- Paket perubahan source website berada pada **ZIP Cleaning terpisah**, bukan folder repository QA ini.

## Cara 1: Git CLI (direkomendasikan)

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
