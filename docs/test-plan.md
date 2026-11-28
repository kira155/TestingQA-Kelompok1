# Test Plan – Cleaning Service System

Revisi 28 November 2026 | Kelompok 1 | Acuan ISO/IEC/IEEE 29119-5:2024

## Tujuan dan ruang lingkup
Menguji login, hak akses, stok, permintaan, barang masuk/keluar, pengelolaan barang, laporan bulanan dan tampilan. Metode utama Black Box dan review/penelusuran White Box manual. Keyword-driven testing dipakai untuk menyusun langkah. Penetration test, load test dan perubahan data produksi tidak termasuk ruang lingkup.

## Rencana test case
| Bagian | Jumlah | Keterangan |
|---|---:|---|
| TC | 30 | Konversi kasus historis ke keyword |
| REG | 10 | Regresi perubahan footer, registrasi, navigasi |
| FU | 9 | Cek ulang D-01 sampai D-09 |
| E2E | 4 | Alur transaksi, laporan, logout |
| Total | 53 | Rencana eksekusi manual, belum dijalankan ulang |

White Box: 10 pemeriksaan kode yang sudah tercatat, dilaporkan terpisah; lanjutkan uji cabang dengan data uji. Tidak ada klaim coverage kode tanpa pengukuran.

## Lingkungan
XAMPP/PHP dan MySQL/MariaDB staging, browser desktop dan mobile, akun dummy admin/staff, backup data awal. Versi source, browser, tanggal dan tester diisi saat eksekusi. Hasil historis Playwright dan simulasi HTTP/DOM tetap ada di folder results. Tidak memakai data produksi untuk transaksi uji.

## Langkah kerja
Gunakan [library](../test-cases/keyword-library.csv), [kasus](../test-cases/keyword-test-cases.csv), [urutan keyword](../test-cases/keyword-steps.csv) dan [test procedure](test-procedures.md). Prioritaskan akses dan konsistensi stok, kemudian fitur lain. Untuk jumlah barang gunakan variasi kosong, nol, negatif, batas stok dan satu di atas batas. Untuk review kode telusuri kondisi, cabang dan query terkait.

## Kriteria masuk dan selesai
Sebelum tes: source dan database uji tersedia, akun diizinkan, kebutuhan/expected result disepakati. Setelah tes: semua kasus dicatat sebagai PASS, FAIL atau BLOCKED dengan actual result dan bukti; temuan dihubungkan ke defect report; uji ulang setelah fix. NOT RUN berarti belum dieksekusi, bukan gagal. Hasil lama dan hasil manual baru dicatat terpisah.

## Pembagian kerja
Lihat [rencana anggota](pembagian-tugas.md); penugasan dan hasil disetujui masing-masing anggota. Wawancara QA masih belum dilakukan.

Acuan yang tersedia adalah preview 14 halaman (sampai klausul 5.2.1). Klausul 6–8 dan lampiran hanya terlihat pada daftar isi. Kelompok menggunakan konsep yang dapat diperiksa pada klausul 3, 4 dan 5; belum mengklaim full maupun tailored conformance karena persyaratan klausul 7 belum dapat diperiksa.
