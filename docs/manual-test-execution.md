# Test Case Manual – Black Box dan Regression (NOT RUN)

**Instruksi:** jalankan pada salinan DB/staging, bukan data produksi. Isi kolom tanggal, tester, Actual Result, Status (PASS/FAIL/BLOCKED), screenshot ID, dan Bug ID berdasarkan fakta. Expected Result adalah **usulan test basis** yang harus ditinjau pemilik aplikasi.

| ID | Requirement | Langkah manual inti | Expected Result | Prioritas | Status |
|---|---|---|---|---|---|
| MAN-01 | RQ-01 | Buka login, coba kosong/salah/benar dan logout | Ditolak pada input tidak valid; pengguna benar masuk; logout menutup sesi | High | NOT RUN |
| MAN-02 | RQ-01 | Login, akses URL private setelah logout dan setelah timeout | Halaman private tidak terbuka tanpa sesi valid | High | NOT RUN |
| MAN-03 | RQ-02 | Staff mencoba membuka dashboard, Barang, Kelola User, serta direct POST admin | Fungsi admin tidak bisa diakses/diubah oleh staff | Critical | NOT RUN |
| MAN-04 | RQ-03 | Catat saldo stok awal; masuk dan keluar satu barang (data uji) | Perubahan saldo sesuai matematis masuk − keluar di UI dan DB | Critical | NOT RUN |
| MAN-05 | RQ-04 | Buat permintaan baru, lalu buat permintaan lain dengan ID yang memiliki prefiks sama | Total penerimaan masing-masing request tetap terpisah | High | NOT RUN |
| MAN-06 | RQ-05 | Penuhi request secara bertahap, coba lebih dari sisa | Total penerimaan <= jumlah diminta; ditolak saat melebihi | Critical | NOT RUN |
| MAN-07 | RQ-06 | Coba qty 0, -1, 1, sama stok, stok+1 | Hanya nilai sah dan stok cukup yang diterima server | Critical | NOT RUN |
| MAN-08 | RQ-06 | Dua user mengeluarkan stok hampir bersamaan (staging saja) | Tidak terjadi stok negatif atau race condition | Critical | NOT RUN |
| MAN-09 | RQ-03/RQ-08 | Tambah barang dengan stok_awal positif; coba hapus barang dengan transaksi | Saldo awal benar; histori transaksi tidak ikut terhapus | Critical | NOT RUN |
| MAN-10 | RQ-09 | Barang dengan beberapa transaksi dan beberapa permintaan → cek ringkasan | Total masuk/keluar dan permintaan tidak terlipat/tergandakan | High | NOT RUN |
| MAN-11 | RQ-10 | Kirim POST tanpa token CSRF lalu dengan token sah (staging) | POST tanpa token ditolak; token sah diterima | Critical | NOT RUN |

## Form eksekusi untuk setiap percobaan

- **ID kasus / Req ID:**
- **Versi code/build / tanggal/waktu:**
- **Tester / perangkat / browser:**
- **Prasyarat dan data awal (tanpa kredensial):**
- **Test data & langkah lengkap:**
- **Expected Result:**
- **Actual Result (berdasarkan pengamatan):**
- **Status:** NOT RUN / PASS / FAIL / BLOCKED
- **Evidence (path):**
- **Bug ID dan tindakan retest:**

## Kriteria keluar manual testing

1. Semua P0 dieksekusi atau berstatus BLOCKED dengan alasan tertulis.
2. Setiap FAIL punya bug report dan langkah reproduksi.
3. Ada screenshot sebelum/sesudah dan bukti data uji untuk transaksi, tanpa data pribadi.
4. Hasil tidak menyebut sekadar validasi atribut HTML sebagai bukti validasi sisi server.
5. Test completion report dibuat setelah selesai dan ada keputusan risiko sisa.
