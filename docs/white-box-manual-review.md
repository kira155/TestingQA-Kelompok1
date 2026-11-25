# White Box Testing Manual – Source PHP

Source PHP tersedia pada folder `cleaning/`. Kelompok telah meninjau struktur kode untuk menentukan jalur yang perlu diuji. Pemeriksaan ini masih berupa review statis; cakupan statement dan branch belum diukur melalui eksekusi.

| ID | Source/percabangan | Jalur uji yang harus dilalui di staging | Expected | Bukti statis | Eksekusi manual |
|---|---|---|---|---|---|
| WB-01 | `actions/transaksi_action.php` – autentikasi, jenis, qty | (a) tanpa sesi (b) sesi valid + jenis invalid (c) qty 0 (d) valid | (a)-(c) ditolak, (d) dicatat | Pemeriksaan source & patch autentikasi | NOT RUN |
| WB-02 | `actions/transaksi_action.php` – stok barang keluar | (a) qty <= stok (b) qty > stok (c) dua request berurutan | Tidak ada stok negatif | Source awal validasi tanpa row lock | NOT RUN |
| WB-03 | `pages/barang.php` – agregasi SQL | Satu barang dengan 2 transaksi dan 2 permintaan | Total masing-masing tidak berlipat 2x | Source awal double LEFT JOIN | NOT RUN |
| WB-04 | `actions/permintaan_action.php` – pemenuhan request | (a) request benar (b) id req 1 vs 11 (c) over-delivery | Setiap request hanya menghitung transaksi miliknya | Source awal `LIKE '%REQ-n%'` | NOT RUN |
| WB-05 | `actions/barang_action.php` – stok awal dan hapus | (a) tambah stok awal 5 (b) hapus barang bertransaksi | Saldo 5; riwayat tetap ada | Source awal insert tidak menulis stok_sekarang | NOT RUN |
| WB-06 | `migrations/20261125_fix_transaksi_update.sql` – trigger update | (a) ubah qty (b) ubah jenis (c) pindah id_barang | Setiap saldo lama/baru sesuai ledger | Trigger dump awal hanya menghitung selisih qty | NOT RUN |

## Pemeriksaan jalur kontrol (contoh WB-01)

- **Keputusan D1:** ada session? Jalur T = lanjut, F = tolak.
- **Keputusan D2:** `jenis ∈ {masuk,keluar}`? T = lanjut, F = tolak.
- **Keputusan D3:** `qty > 0`? T = lanjut, F = tolak.
- **Keputusan D4:** jika jenis keluar, apakah stok mencukupi? T = insert, F = tolak.

**Rencana coverage:** masing-masing sisi True/False dari D1–D4 (decision/branch). Tidak diklaim 100% sebelum dibuktikan dengan rekaman hasil aktual per cabang.

## Format bukti

Untuk setiap WB: salin potongan kode terkait (tanpa rahasia) + input dan output + tanggal tester + log/screenshot runtime + posisi jalur yang diuji. Lakukan pengujian manual di localhost atau staging; tidak perlu framework otomasi.
