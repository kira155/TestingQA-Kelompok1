# Update Repository lewat VS Code

Buka folder repository lokal di VS Code. Sebelum menyalin perubahan, cek dulu apakah ada perubahan anggota lain. Jangan hapus folder `.git`.

```bash
git status
git diff
```

Gunakan Source Control (Ctrl+Shift+G) untuk memilih file yang diubah. Isi pesan commit sesuai pekerjaan, lalu Commit dan Push. Contoh untuk pembaruan dokumentasi ini:

```bash
git add README.md docs results
git commit -m "Simplify test report and add team task plan"
git push origin main
```

Sesuaikan branch dengan repository. Periksa screenshot, log dan file konfigurasi sebelum push agar tidak ada password, token atau data pribadi yang tidak boleh dibagikan. Untuk mengecek daftar test case dan file bukti, jalankan `python scripts/verify_repository.py`.
