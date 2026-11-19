# Cara Upload Repository ke GitHub

## Cara paling mudah (website GitHub)

1. Login di https://github.com/ dan klik **New repository**.
2. Nama: `qa-manual-testing-bafageh-bakery`.
3. Pilih **Public** bila dosen meminta tautan terbuka. Jangan upload data pribadi atau PDF ISO berlisensi.
4. Klik **Create repository** (boleh tanpa README bawaan, karena README sudah tersedia).
5. Di halaman repository klik **Add file → Upload files**.
6. **Ekstrak ZIP** lalu upload **isi folder** `QA_Manual_Testing_Bafageh_Bakery/` (bukan ZIP saja) agar README tampil di GitHub. Folder kosong tidak terlihat; folder `evidence` memiliki `.gitkeep`.
7. Isi pesan commit `Initial commit: QA test plan and 30 manual test cases` lalu **Commit changes**.
8. Tambahkan screenshot setiap selesai testing dan commit, misalnya `docs: add TC-001 to TC-010 evidence and results`.
9. Kirim tautan repository kepada dosen.

## Alternatif dengan Git CLI

Dari terminal di dalam folder project:

```bash
git init
git add .
git commit -m "Initial QA documentation and 30 manual test cases"
git branch -M main
git remote add origin https://github.com/USERNAME/qa-manual-testing-bafageh-bakery.git
git push -u origin main
```

Buat repository kosong di GitHub sebelum menggunakan terminal, lalu ganti USERNAME dengan username Anda. GitHub dapat meminta autentikasi token/credential manager, bukan password biasa.

## Checklist sebelum publish

- [ ] README tampil di halaman utama repository.
- [ ] Tidak ada file PDF ISO berlisensi.
- [ ] Nama/NIM sesuai keperluan tugas, tidak memuat kontak pribadi yang tidak relevan.
- [ ] Tidak ada screenshot dengan data pribadi pelanggan, cookies/token, atau nomor kontak uji nyata.
- [ ] Test case dengan status Not Run tidak diklaim Pass.
- [ ] Folder `evidence/` diisi screenshot hasil uji, bukan gambar ilustrasi.
- [ ] Wawancara QA asli dan ada bukti izin narasumber bila dipublikasi.
