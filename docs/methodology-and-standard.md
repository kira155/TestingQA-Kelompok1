# Acuan dan metode pengujian

Acuan: ISO/IEC/IEEE 29119-5:2024, *Software and systems engineering — Software testing — Part 5: Keyword-driven testing*, edisi ke-2, Desember 2024.

Acuan yang tersedia adalah preview 14 halaman (sampai klausul 5.2.1). Klausul 6–8 dan lampiran hanya terlihat pada daftar isi. Kelompok menggunakan konsep yang dapat diperiksa pada klausul 3, 4 dan 5; belum mengklaim full maupun tailored conformance karena persyaratan klausul 7 belum dapat diperiksa.

## Metode yang dipakai
Black Box digunakan untuk mengecek fitur dari sisi pengguna. White Box digunakan untuk menelusuri kode PHP, kondisi dan alur transaksi. Keyword-driven testing (KDT) digunakan untuk menyusun langkah pengujian yang dapat dipakai ulang. Kami memilih pelaksanaan manual untuk tugas kelompok; hasil Playwright dan pemeriksaan lokal sebelumnya menjadi bukti tambahan, bukan hasil KDT manual.

| Bagian preview | Penerapan pada tugas |
|---|---|
| 3.1, 3.6, 3.16, 3.26 | Precondition, data, expected result, actual result, status berdasarkan perbandingan |
| 3.8–3.14 | Library keyword berbentuk kata kerja; parameter; keyword sederhana dan composite |
| 3.15, 5.1 | Eksekusi manual dengan browser dan data uji |
| 3.25 dan 5.1 (Figure 1, halaman standar 7/PDF 13) | Test procedure berisi urutan test case; keyword case memetakan langkah manual |
| 5.2.1 | Domain layer (MasukAkun, CekBatasStok) dan test interface layer (IsiField, PilihOpsi, PeriksaData) |
| 4 | Batas penerapan dicatat; klaim kesesuaian belum dilakukan |

## Cara kerja
1. Tentukan fitur, kondisi, data awal dan hasil yang diharapkan.
2. Pilih keyword dari library dan susun urutannya. Keyword composite diuraikan mengikuti instruksi library.
3. Jalankan test procedure secara manual, isi hasil aktual, status, tester, tanggal dan bukti.
4. Catat temuan dan uji ulang sesudah perbaikan. Jangan mengubah catatan hasil lama.

Contoh TC-007: `MasukAkun(akun uji, password)` terdiri dari membuka login, mengisi username dan password, menekan Login, lalu mengecek dashboard. Password diambil dari akun uji dan tidak disimpan di repository.

## Batas penerapan
Pilihan kelompok: CSV/Excel sebagai library, data dan lembar kerja; browser dan XAMPP sebagai lingkungan manual; pemeriksaan hasil oleh anggota. Tidak membangun framework KDT otomatis, tool bridge atau execution engine. Alasan: lingkup tugas, waktu, dan fokus manual. Risiko: langkah manual dapat terlewat; gunakan urutan, review silang dan bukti. Persetujuan kelompok atas pilihan ini masih perlu dicatat. Klausul 6–8 tidak dievaluasi karena teks lengkap belum tersedia; daftar isi hanya dipakai sebagai konteks. Tidak menempelkan PDF berhak cipta ke repo.

Referensi: ISO/IEC/IEEE 29119-5:2024, *Software and systems engineering — Software testing — Part 5: Keyword-driven testing*, edisi ke-2, Desember 2024.
