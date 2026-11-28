# Pengecekan tampilan website – 28 November 2026

Pada tahap ini kami mendokumentasikan tampilan halaman website Cleaning. Bukti berasal dari screenshot yang dikirim anggota melalui percakapan, bukan dari eksekusi ulang script. Versi build dan identitas penguji belum dicatat. Nama akun pada beberapa screenshot disamarkan sebelum dimasukkan ke repository.

| No. | Halaman | Hasil pengamatan | Bukti |
|---|---|---|---|
| 1 | Login | Form username, password dan tombol login terlihat. Footer baru sudah tampil. Judul bagian atas kurang terbaca karena bertumpuk dengan grafis latar. | [Login](../../evidence/2026-11-28/website/01-login.png) |
| 2 | Dashboard | Total 51 barang; 13 habis, 13 menipis, 25 cukup. Jumlah kategori sesuai total. | [Dashboard](../../evidence/2026-11-28/website/02-dashboard.png) |
| 3 | Stok Barang | Kolom pencarian, filter kategori dan tabel stok terlihat. | [Stok barang](../../evidence/2026-11-28/website/03-stok-barang.png) |
| 4 | Permintaan Barang | Form permintaan dan riwayat tampil; ringkasan 60 request, 572 diminta dan 56 belum masuk. | [Permintaan](../../evidence/2026-11-28/website/04-permintaan-barang.png) |
| 5 | Barang Masuk | Daftar pending dan riwayat tampil; total qty pending 80. | [Barang masuk](../../evidence/2026-11-28/website/05-barang-masuk.png) |
| 6 | Barang Keluar | Form barang keluar dan riwayat terlihat. | [Barang keluar](../../evidence/2026-11-28/website/06-barang-keluar.png) |
| 7 | Kelola Barang | Daftar barang dan tombol aksi terlihat. Beberapa stok berbeda dari halaman Stok Barang; terdapat nilai -3. | [Kelola barang](../../evidence/2026-11-28/website/07-kelola-barang.png) |
| 8 | Laporan Bulanan | Laporan November 2026 tampil. Total masuk 119 dan keluar 36, sama seperti dashboard. Tombol ekspor dan cetak terlihat. | [Laporan](../../evidence/2026-11-28/website/08-laporan-bulanan.png) |
| 9 | Kelola User | Daftar akun, role admin/staff dan tombol tambah user terlihat pada sesi admin. | [Kelola user](../../evidence/2026-11-28/website/09-kelola-user.png) |

## Catatan untuk pengujian berikutnya

- Cek tampilan judul login pada ukuran layar yang sama (D-08).
- Bandingkan stok barang pada dua halaman setelah refresh, tanpa transaksi di antaranya (D-06).
- Telusuri stok Detergen/rinso yang terlihat -3 di Kelola Barang (D-07).
- Cek apakah 56 sisa belum masuk dan 80 qty pending memiliki definisi atau cakupan data yang sama (D-09).
- Lanjutkan uji submit form, filter, perubahan stok, pembatasan role, ekspor dan cetak dengan akun serta database uji.

Screenshot ini membuktikan elemen halaman terlihat, bukan bahwa seluruh tombol, transaksi atau hak akses sudah lulus pengujian. Hasil 25 PASS / 2 FAIL dari pengujian komponen sebelumnya tidak berubah. Jangan memakai data operasional untuk pengujian perubahan stok tanpa izin.
