# Laporan Evaluasi Pengujian Cleaning Stock Management

**Kelompok 1 – Testing & Quality Assurance**  
**Tanggal review:** 25 November 2026

## 1. Tujuan dan metode

Evaluasi dilakukan untuk meninjau kelengkapan pengujian website inventaris Cleaning Stock Management. Peninjauan mencakup dokumen QA, source PHP, struktur database, serta bukti pengujian terdahulu. Pembahasan menggunakan pendekatan ISO/IEC/IEEE 29119 Part 2, Part 3, dan Part 4. Referensi Stuart Reid merupakan materi pengantar standar, sehingga evaluasi ini bukan penilaian sertifikasi ISO.

## 2. Hasil evaluasi terhadap ISO 29119

| Bagian | Hasil peninjauan | Pekerjaan lanjutan |
|---|---|---|
| Part 2 – Proses | Rencana dan prioritas pengujian telah diperbarui | Eksekusi, pemantauan, retest, dan penutupan pengujian |
| Part 3 – Dokumentasi | Test case, RTM, laporan temuan dan format bukti tersedia | Isi actual result, tester, tanggal dan bukti manual |
| Part 4 – Teknik | Rancangan Black Box dan White Box telah disusun | Uji batas input, keputusan, percabangan, dan jalur yang dipilih |

## 3. Temuan dan tindak lanjut

| Temuan | Tindakan yang disiapkan |
|---|---|
| Endpoint transaksi belum mewajibkan autentikasi | Pemeriksaan sesi dan CSRF |
| Saldo barang baru tidak mengikuti stok awal | Penyesuaian penyimpanan saldo awal |
| Riwayat transaksi dapat hilang ketika barang dihapus | Pembatasan penghapusan dan migrasi foreign key |
| Transaksi bersamaan berpotensi membuat saldo tidak konsisten | Penguncian baris dan transaksi database |
| JOIN dapat menggandakan total | Pemisahan agregasi sebelum JOIN |
| Pencarian ID permintaan dapat cocok dengan ID lain | Pencocokan token REQ secara tepat |
| Trigger UPDATE belum menangani seluruh perubahan transaksi | Perbaikan dampak nilai OLD dan NEW |
| Penghapusan massal berisiko menghilangkan histori | Menonaktifkan pembersihan otomatis |

Review offline database sebelumnya menunjukkan 20 dari 51 barang memiliki selisih antara saldo tersimpan dan hasil perhitungan dari stok awal serta transaksi. Penyebab dan saldo yang benar belum ditetapkan karena diperlukan pemeriksaan histori serta stok fisik. Data rinci operasional tidak disertakan dalam repository; audit tidak mengubah database asli.

## 4. Perkembangan pengujian

Sesi otomatis tanggal 23 November 2026 menghasilkan 29 PASS, 0 FAIL, 0 SKIP, dan 1 warning. Repository menyimpan 30 skenario historis dengan catatan PASS, termasuk dua catatan terdahulu TC-001 dan TC-002. Bukti khusus TC-002 belum ditemukan. Review source dan pemeriksaan sintaks PHP telah dilakukan, tetapi keduanya belum membuktikan keberhasilan alur aplikasi ketika terhubung ke database.

## 5. Pekerjaan yang belum selesai

| Pekerjaan | Status |
|---|---|
| Menyiapkan dan memverifikasi lingkungan lokal | NOT RUN |
| Menjalankan Black Box manual dan mencatat bukti | NOT RUN |
| Menjalankan White Box manual dan mengukur cakupan jalur | NOT RUN |
| Menjalankan regresi setelah perbaikan | NOT RUN |
| Rekonsiliasi selisih stok dan verifikasi migrasi | NOT RUN |
| Melengkapi bukti TC-002 | BELUM LENGKAP |
| Wawancara serta validasi QA profesional | BELUM DILAKUKAN |
| Menyelesaikan laporan akhir dan persetujuan hasil | BELUM SELESAI |
| Verifikasi referensi jurnal sesuai ketentuan dosen | BELUM LENGKAP |

## 6. Kesimpulan

Penyusunan dokumen dan perbaikan source telah memberikan dasar yang lebih teratur untuk pengujian. Kesesuaian fungsional versi terbaru masih harus dibuktikan melalui pelaksanaan Black Box dan White Box secara manual. Hasil aktual, bukti, serta status temuan perlu dilengkapi sebelum kelompok dapat menyatakan kegiatan pengujian selesai.

## Referensi

- ISO/IEC/IEEE 29119-2:2021, *Software testing – Test processes*. https://www.iso.org/standard/79428.html
- ISO/IEC/IEEE 29119-3:2021, *Software testing – Test documentation*. https://www.iso.org/standard/79429.html
- ISO/IEC/IEEE 29119-4:2021, *Software testing – Test techniques*. https://www.iso.org/standard/79430.html
- S. Reid, *ISO/IEC/IEEE 29119: The New International Software Testing Standards*, materi pengantar dari lampiran tugas.
