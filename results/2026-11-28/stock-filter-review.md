# Filter stok dan logout – 28 November 2026

Acuan: ISO/IEC/IEEE 29119-5:2024. Bukti berasal dari lima screenshot pengguna; bukan eksekusi ulang oleh penyusun laporan. Nama akun pada screenshot stok ditutup sebelum disimpan.

| ID | Yang dicek | Hasil terlihat | Bukti |
|---|---|---|---|
| TC-010 | Peralatan | 28 barang, baris terlihat Peralatan | [Screenshot](../../evidence/2026-11-28/website/10-filter-peralatan.png) |
| ST-001 | Menipis | 13 barang; stok terlihat 1–4 | [Screenshot](../../evidence/2026-11-28/website/11-filter-menipis.png) |
| ST-002 | Cukup | 25 barang; stok terlihat >4 | [Screenshot](../../evidence/2026-11-28/website/12-filter-cukup.png) |
| ST-003 | Habis | 13 barang; stok terlihat 0 | [Screenshot](../../evidence/2026-11-28/website/13-filter-habis.png) |
| E2E-004 | Logout | Login tampil dengan notifikasi logout | [Screenshot](../../evidence/2026-11-28/website/14-logout.png) |

Jumlah filter status cocok dengan ringkasan dashboard sebelumnya: 13 + 13 + 25 = 51. Kategori Peralatan: 7 + 7 + 14 = 28. Saat filter status aktif, angka pada tab lain menjadi nol dan tab Semua menunjukkan jumlah hasil filter. Perlu dikonfirmasi apakah tab memang dimaksudkan menampilkan jumlah hasil aktif atau jumlah semua barang; belum dinyatakan sebagai bug.

Judul login terlihat utuh pada screenshot logout (FU-008), tetapi perlu diuji ulang pada ukuran desktop sebelumnya dan mobile. Footer REG-002 terlihat sebagian. Status ketujuh kasus terkait adalah PARTIAL; 49 kasus lain masih NOT RUN dalam format KDT. Hasil historis tidak berubah. Uji akses dashboard setelah logout, seluruh baris filter, dan cek ulang defect masih perlu dilanjutkan. Identitas tester dan versi build belum dicatat.
