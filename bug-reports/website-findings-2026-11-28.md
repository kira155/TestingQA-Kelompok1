# Temuan dari screenshot website – 28 November 2026

| ID | Temuan | Harapan | Bukti | Status |
|---|---|---|---|---|
| D-06 | Angka stok berbeda pada dua halaman | Nilai sama jika barang, waktu, dan sumber stok sama | [Stok](../evidence/2026-11-28/website/03-stok-barang.png), [Kelola](../evidence/2026-11-28/website/07-kelola-barang.png) | Perlu konfirmasi |
| D-07 | Detergen/rinso bernilai -3 pada Kelola Barang | Stok fisik tidak negatif; jika ada koreksi, harus dapat dijelaskan | [Kelola](../evidence/2026-11-28/website/07-kelola-barang.png) | Perlu konfirmasi penyebab |
| D-08 | Judul login tampak bertumpuk dengan grafis latar | Judul terbaca jelas | [Login](../evidence/2026-11-28/website/01-login.png) | Perlu cek ulang tampilan |
| D-09 | Sisa belum masuk 56, total qty pending 80 | Jika cakupan dan definisi sama, nilai sesuai | [Permintaan](../evidence/2026-11-28/website/04-permintaan-barang.png), [Barang masuk](../evidence/2026-11-28/website/05-barang-masuk.png) | Perlu konfirmasi definisi |

## Rincian D-06

| Barang | Stok Barang | Kelola Barang |
|---|---:|---:|
| Baygon / HIT | 36 | 274 |
| Detergen/rinso | 0 | -3 |
| Kamper bola | 50 | 300 |
| Kapur Ajaib | 8 | 14 |
| Kifa | 17 | 45 |

Langkah cek ulang: buka kedua halaman dengan akun admin, pastikan tidak ada transaksi selama pengecekan, refresh, lalu cocokkan data per ID barang dan query perhitungan stok. Temuan D-06 dan D-07 bisa berkaitan dengan risiko D-03/D-04, tetapi akar penyebab belum dipastikan. Seluruh temuan screenshot dicatat terpisah dari lima temuan pengujian sebelumnya.

Rencana uji ulang berbasis keyword: D-06 sampai D-09 → FU-006 sampai FU-009 (urutan sama). Lihat [keyword test cases](../test-cases/keyword-test-cases.csv). Status temuan tidak berubah.
