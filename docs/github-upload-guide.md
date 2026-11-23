# Cara Upload Repository ke GitHub

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

## Alternatif dengan Git CLI

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
