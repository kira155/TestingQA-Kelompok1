# Catatan Perubahan

## 28 November 2026

- Mengganti acuan menjadi ISO/IEC/IEEE 29119-5:2024.
- Menyusun keyword library, 53 test case manual, langkah dan prosedur. Hasil lama dipertahankan; eksekusi ulang belum dilakukan.

- Menambahkan 9 screenshot halaman website dan catatan perbedaan stok untuk dicek ulang.

- Menambahkan hasil pemeriksaan lokal: 25 PASS dan 2 FAIL dari 27 skenario.
- Mencatat 5 temuan dari pengecekan kode PHP. Dua temuan juga muncul pada pengujian lokal.
- Memperbarui hasil pengujian ulang footer, registrasi dan navigasi.
- Menyimpan screenshot dan hasil eksekusi. Pengujian MySQL dan wawancara masih belum selesai.

## 24 November 2026

- Mengganti footer website menjadi Cleaning Stock Management.
- Membatasi registrasi publik menjadi akun Staff dan pembuatan Admin melalui terminal.
- Memperbaiki navigasi mobile, redirect dan konfigurasi koneksi database.
- Memeriksa sintaks 27 file PHP; seluruhnya lolos.
- Menambahkan 10 skenario regression testing dan script pengecekan file repository melalui GitHub Actions.
- Merapikan 37 screenshot pengujian awal.

## 23 November 2026

- Menjalankan pengujian login, stok, permintaan dan validasi transaksi dengan Playwright.
- Hasil satu sesi: 29 PASS, 0 FAIL, 1 warning. TC-001 dan TC-002 memiliki catatan PASS dari sesi terdahulu; screenshot khusus TC-002 belum ditemukan.

Source aplikasi disimpan di paket terpisah dari repository laporan QA.
