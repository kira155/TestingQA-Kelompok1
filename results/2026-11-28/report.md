# Hasil Pengujian – 28 November 2026

## Pengujian yang dilakukan

Pengujian menggunakan source Cleaning versi 24 November. Beberapa fungsi diperiksa melalui HTTP lokal dan tampilan halaman menggunakan Chromium. Database MySQL belum bisa dijalankan di lingkungan pengujian, sehingga sebagian kasus menggunakan data simulasi dan bukan transaksi yang tersimpan di database asli.

| Pengujian | PASS | FAIL | Total |
|---|---:|---:|---:|
| HTTP lokal | 3 | 0 | 3 |
| HTTP dengan simulasi database | 17 | 2 | 19 |
| Tampilan offline | 5 | 0 | 5 |
| Total | 25 | 2 | 27 |

Hasil lengkap: [test case dan actual result](component-results.csv), [log pengujian](execution-console.txt), [screenshot](../../evidence/2026-11-28/).

## Hasil pengecekan source code

Dari 10 pemeriksaan kode, 5 sesuai dan 5 memiliki temuan. Validasi login, registrasi, setup Admin, jumlah transaksi dan jumlah permintaan sudah memiliki pengecekan di kode. Temuan lainnya dicatat sebagai berikut:

| ID | Temuan | Keterangan |
|---|---|---|
| D-01 | Transaksi tanpa login | Request masih mencoba INSERT pada database simulasi |
| D-02 | Akses halaman Kelola User | Staff ditolak, tetapi status HTTP masih 200 |
| D-03 | Input barang | Tipe data satuan/stok tidak sesuai; stok negatif belum ditolak |
| D-04 | Perubahan stok | Risiko stok tidak konsisten saat transaksi bersamaan |
| D-05 | ID permintaan | Risiko salah mencocokkan ID dengan awalan yang sama |

D-01 dan D-02 ditemukan saat uji lokal. D-03 sampai D-05 masih berupa temuan review kode yang perlu dibuktikan dengan MySQL. Semua masih OPEN. Rincian lokasi kode ada di [white box review](white-box-review.csv) dan [daftar bug](../../bug-reports/defects-2026-11-28.md).

## Pengujian ulang setelah update website

Dari 10 rencana regression, 6 masih sebagian, 1 PASS melalui HTTP lokal, 1 PASS dengan simulasi database dan 2 terhambat karena database belum tersedia. [Lihat status masing-masing kasus](regression-status.csv).

## Kesimpulan sementara

Beberapa validasi sudah berjalan, tetapi akses pengguna dan proses transaksi masih perlu diperiksa lebih lanjut. Pengujian manual end-to-end belum selesai. Tahap berikutnya adalah menyiapkan database uji di XAMPP, mengulang skenario transaksi, memperbaiki temuan yang terbukti dan melengkapi wawancara QA.

[Catatan teknis dan batasan pengujian](technical-notes.md).

Revisi dokumentasi: acuan ISO/IEC/IEEE 29119-5:2024. Hasil komponen ini adalah hasil historis, tidak dihitung sebagai eksekusi manual KDT. Lihat [kasus baru](../../test-cases/keyword-test-cases.csv).

Update bukti website: [filter stok dan logout](stock-filter-review.md), lima screenshot tambahan. Hasil pengujian komponen tetap 25 PASS / 2 FAIL.
