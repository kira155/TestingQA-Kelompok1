# Evidence Pengujian

Folder `evidence/` dalam repository ini **telah berisi 37 screenshot PNG** dari pengujian yang sebelumnya dilakukan:

- 36 screenshot dirujuk oleh `test-cases/test-cases.csv` dan `results/test-execution-report.md`.
- 1 screenshot inspeksi tambahan: `inspect_filter_stok.png`.
- Bukti screenshot khusus **TC-002 masih belum teridentifikasi**.

Sebelum dipublikasikan secara terbuka, periksa screenshot agar tidak memuat password, token, cookie, data pengguna, atau informasi pelanggan yang bersifat sensitif. Screenshot merupakan evidence historis, **bukan bukti pengujian ulang versi aplikasi 24 November 2026**.

Periksa konsistensi file dengan:

```bash
python scripts/verify_repository.py
```

## Screenshot website terbaru

Tersedia [9 screenshot halaman website](2026-11-28/website/) dari 28 November. Nama akun pada gambar baru disamarkan. Screenshot memperlihatkan tampilan halaman, bukan bukti seluruh fungsi berjalan. Lihat [catatan pengecekan](../results/2026-11-28/website-review.md).
