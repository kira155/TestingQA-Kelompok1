# Test Procedure

Urutan kasus di bawah mengikuti rancangan kelompok, bukan urutan eksekusi yang sudah terjadi. Gunakan [keyword steps](../test-cases/keyword-steps.csv) dan [keyword library](../test-cases/keyword-library.csv).

| ID | Bagian | Urutan test case | Persiapan | Setelah pengujian |
|---|---|---|---|---|
| TP-01 | Login dan akses | TC-001 sampai TC-007; FU-001, FU-002; E2E-004 | Akun dummy; sesi bersih; uji negatif sebelum positif | Keluar akun dan hapus data dummy bila ada |
| TP-02 | Stok dan permintaan | TC-008 sampai TC-018; FU-005 sampai FU-007; FU-009; E2E-001; ST-001 sampai ST-003 | Login admin/staff sesuai hak; snapshot stok dan permintaan | Pulihkan snapshot staging setelah tes |
| TP-03 | Barang masuk/keluar dan laporan | TC-019 sampai TC-030; FU-003, FU-004; E2E-002, E2E-003 | Login admin; siapkan stok 35 dan 2 untuk BVA atau sesuaikan ekspektasi sebelum eksekusi | Pulihkan stok dan transaksi dummy |
| TP-04 | Regresi versi website | REG-001 sampai REG-010; FU-008 | Staging versi terbaru, akun uji, browser desktop/mobile, backup | Logout; hapus akun dummy; pulihkan snapshot |

Sebelum setiap kasus, penuhi precondition pada daftar kasus. Isi actual result berdasarkan pengamatan, bukan dengan menyalin expected result. Jika precondition tidak terpenuhi, tandai BLOCKED. Jangan menjalankan transaksi pada database produksi. Untuk batas stok historis, pakai data dummy yang sama atau sepakati nilai aktual dan expected result sebelum pengujian.
