# Rencana Regression Testing — Cleaning Stock Management

**Tanggal pembaruan source:** 24 November 2026  
**Jenis perubahan:** UI footer, registrasi, pembatasan setup admin, navigasi mobile, dan redirect  
**Status pengujian fungsional (server/browser):** **BELUM DIEKSEKUSI** untuk versi terbaru. 

Rencana ini merupakan test case tambahan dan **tidak menambah jumlah PASS pada sesi 23 November 2026**. Uji di staging dengan akun dan database uji sebelum deploy ke produksi. Jangan memakai akun pengguna nyata tanpa izin.

| ID | Area | Langkah pengujian | Expected result | Prioritas | Status |
|---|---|---|---|---|---|
| REG-001 | Footer dashboard | Login sebagai Staff, buka Dashboard, Stok, Permintaan, Barang Masuk, Barang Keluar | Footer konsisten, menampilkan “Cleaning Stock Management”, dan tidak menampilkan kredit kelompok | Medium | NOT RUN |
| REG-002 | Footer halaman login | Buka `/cleaning/auth/login.php` tanpa login | Footer produk tampil, tahun sesuai server, tidak ada teks “Created by Kelompok 1” | Medium | NOT RUN |
| REG-003 | Responsive | Buka halaman utama pada 375px dan 768px | Teks footer terbaca; tidak menutup konten; tidak memunculkan horizontal overflow dari footer | Medium | NOT RUN |
| REG-004 | Menu seluler | Pada lebar kurang dari 768px, buka menu; tekan Esc dan klik area luar | Menu menutup; atribut `aria-expanded` konsisten | Medium | NOT RUN |
| REG-005 | Registrasi Staff | Daftar akun baru pada halaman registrasi | Akun dibuat sebagai `staff`, login berhasil bila database tersedia | High | NOT RUN |
| REG-006 | Tolak role Admin dari registrasi | Kirim POST registrasi dengan `role=admin` menggunakan akun uji baru | Backend **mengabaikan** role dari request; akun tetap `staff` | Critical | NOT RUN |
| REG-007 | Akses setup admin via HTTP | Akses `/cleaning/scripts/create_admin.php` tanpa sesi | HTTP 403; tidak ada formulir pembuatan admin di browser | Critical | NOT RUN |
| REG-008 | Setup admin CLI | Dengan izin pengelola, jalankan script dari terminal dan variabel lingkungan yang valid | Admin berhasil dibuat tanpa menampilkan password; username duplikat ditolak | High | NOT RUN |
| REG-009 | Redirect registrasi | Akses register saat sesi sudah login | Dialihkan ke `/cleaning/pages/dashboard.php` | Low | NOT RUN |
| REG-010 | Regression utama | Jalankan kembali TC-001 s.d. TC-030 sesuai izin dan lingkungan uji | Fitur login, stok, filter, permintaan, dan transaksi tetap berfungsi | High | NOT RUN |

## Pemeriksaan yang sudah dilakukan secara lokal

- [x] **PHP lint**: 27/27 file PHP aplikasi tidak memiliki syntax error pada 24 November 2026.
- [x] Pemeriksaan source: `role` untuk registrasi ditentukan sebagai `staff` di backend.
- [x] Pemeriksaan source: eksekusi `create_admin.php` melalui HTTP diputus dengan kode 403 sebelum koneksi database.
- [x] Pemeriksaan source: footer pada `includes/footer.php` dan halaman login tidak lagi menggunakan kredit kelompok.
- [ ] Uji integrasi database dan browser setelah deploy versi baru.
- [ ] Bukti screenshot setiap REG-xxx setelah uji sungguhan.

**Catatan:** keberhasilan pemeriksaan kode dan lint tidak membuktikan regresi fungsional bebas masalah atau keamanan sistem sepenuhnya.
