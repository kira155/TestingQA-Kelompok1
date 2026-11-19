# Test Plan — Bafageh Bakery

| Elemen | Rincian |
|---|---|
| Project ID | QA-BFB-2026 |
| Version | 1.0 (draft) |
| Dibuat | 19 November 2026 |
| Object | https://bakery.bafageh.com/ |
| Tujuan | Menilai kualitas fungsi publik dan pengalaman penggunaan dari sisi pengguna |
| Pendekatan | Manual Black Box Testing |
| Standar kerangka kualitas | ISO/IEC 25002:2024, Klausul 5, 6, 7.1, 7.4, 8.2.2, 8.2.4 dan 8.2.5 |
| Jumlah kasus | 30 test case |
| Lingkungan | Desktop: Chrome dan Edge; mobile viewport 375×812; desktop viewport 1366×768 |
| Status awal | Not Run |

## A. Tujuan dan ruang lingkup

Uji navigasi halaman (Menu, Galeri, Cara pesan, Cerita, Review, CTA), katalog, gambar, formulir permintaan, tampilan di beberapa resolusi/browser dan waktu respon. Website memuat katalog sekitar 47 produk pada survei awal dan formulir Nama lengkap, Nomor WhatsApp, Tanggal kirim, Pilihan paket serta Catatan. Fitur dan harga dapat berubah; konfirmasi ulang sebelum uji.

**Di luar scope:** login, register, cart, pembayaran, API internal, akses admin, uji penetrasi, load/stress test, dan tindakan yang membutuhkan kredensial/izin khusus.

## B. Quality attributes & kriteria

| Aspek | Kebutuhan pengguna | Kriteria operasional |
|---|---|---|
| Functional Suitability | Pengunjung dapat mencari informasi produk dan menuju form | Tautan dan elemen publik bereaksi sesuai peruntukan; field sesuai aturan bisnis yang terverifikasi |
| Interaction Capability | Pengunjung bisa menavigasi dan memahami pesan | Informasi terbaca, urutan keyboard logis, validasi mudah dipahami |
| Compatibility | Website bisa digunakan di browser dan layar berbeda | Tidak ada overlap/elemen kritis terpotong; navigasi utama dapat digunakan |
| Performance Efficiency | Website terasa responsif | Perbandingan rerata durasi terhadap target awal 5 s untuk homepage dan 2 s untuk navigasi section; **bukan angka dari ISO** |

## C. Teknik desain

- **Equivalence Partitioning:** membagi input valid dan invalid, khususnya nama/WhatsApp.
- **Boundary Value Analysis:** nilai pendek, panjang, tanggal lampau, catatan panjang.
- **Positive / negative testing:** interaksi normal dan input tidak valid.
- **Exploratory & usability inspection:** tampilan, penggunaan keyboard, respons website.

Perlu dibedakan antara **kriteria berdasarkan kebutuhan bisnis yang telah dibuktikan** versus **asumsi best practice**. Jika aturan input tidak diketahui, gunakan `Needs Clarification` dan jangan otomatis menetapkan `Fail`.

## D. Entri dan keluar (entry / exit criteria)

**Entry:** website dapat diakses; browser dan resolusi dicatat; 30 test case disetujui; risiko pengiriman pesan dikenali; izin untuk pengujian submit diperoleh bila diperlukan.

**Exit:** semua kasus yang aman telah dijalankan atau status `Blocked` dijelaskan; evidence terhubung dengan hasil; setiap Fail berisi bug report; simpulan membedakan risiko dan keterbatasan; QA profesional telah memberikan evaluasi.

## E. Environment recording

Isi saat pelaksanaan:
- Tester dan tanggal/jam: [isi]
- OS / perangkat: [isi]
- Chrome: [versi]
- Edge: [versi]
- Resolusi desktop dan mobile: [isi]
- Jenis koneksi / perkiraan kondisi: [isi]
- Website version/snapshot: [isi jika diketahui]
- Izin pemilik usaha untuk submit form: [ya/tidak, bukti izin]

## F. Defect severity

- **Critical**: fungsi bisnis utama tidak dapat diakses dengan dampak tinggi.
- **High**: fitur pemesanan/navigasi utama gagal, tanpa workaround wajar.
- **Medium**: fungsi terganggu namun masih ada workaround.
- **Low**: cosmetic/teks, tanpa dampak signifikan pada penggunaan.

## G. Risks & mitigation

1. Website bisa berubah: catat tanggal pengujian dan snapshot.
2. Pesanan nyata dapat terkirim: **jangan submit tanpa izin**.
3. Aturan validasi tidak terdokumentasi: tandai `Needs Clarification` dan diskusikan dengan QA/pemilik.
4. Hasil waktu muat berbeda antar jaringan: gunakan 3 pengulangan dan catat kondisi.
5. Bukti screenshot dapat mengandung data pribadi: sensor sebelum commit publik.

## H. Deliverables

`test-cases/test-cases.xlsx`, `test-cases/test-cases.csv`, `evidence/`, `bug-reports/`, `results/test-execution-report.md`, `interview/`, README yang terus diperbarui.
