# Panduan pembaruan repository GitHub

1. Ekstrak ZIP. Salin seluruh isi folder `TestingQA-Kelompok1-main` ke repository lokal yang sudah memiliki folder `.git`. Jangan menghapus `.git`.
2. Buka repository melalui VS Code. Periksa screenshot lama sebelum memublikasikannya; gunakan repository private jika bukti memuat informasi operasional atau identitas pribadi.
3. Jalankan perintah berikut melalui terminal:

```powershell
python scripts/verify_repository.py
git status
git add .
git diff --cached --stat
git commit -m "Update QA documentation and add Cleaning Stock Management source"
git push origin main
```

Skema dan data contoh hanya untuk localhost. Dump database asli, foto operasional, dan file kredensial tidak boleh ikut diunggah. Pemeriksaan repository tidak menggantikan pengujian manual atau regresi.
