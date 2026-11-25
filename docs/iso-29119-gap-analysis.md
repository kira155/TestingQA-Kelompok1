# Audit Kesenjangan ISO/IEC/IEEE 29119 – Cleaning Stock Management

**Tanggal:** 25 November 2026  
**Basis:** repo QA, source PHP, database SQL yang diberikan, artikel Stuart Reid (2013) dan katalog ISO seri 29119 edisi 2021.  
**Batasan:** audit source dan dokumen secara offline; tidak ada login website, tes browser, tes MariaDB, atau wawancara baru.

| No | Acuan | Kondisi file awal | Perbaikan yang sudah dilakukan | Sisa pekerjaan / bukti wajib | Status |
|---|---|---|---|---|---|
| 1 | Part 2 – Test planning | Rencana ada tetapi bertentangan dengan metode historis otomatis vs manual | Memisahkan metode aktual dan rencana manual | Setujui scope, aktor, versi build, prioritas & jadwal | PARSIAL |
| 2 | Part 2 – Monitoring/control | Hanya rekap PASS historis | Gap, prioritas dan daftar risiko dibuat | Tanggal eksekusi, tester, status tiap kasus manual, review berkala | BELUM |
| 3 | Part 2 – Test execution | 29 PASS Playwright historis; bukan manual | Hasil historis dipertahankan tanpa klaim baru | Eksekusi Black Box manual di staging + evidence | BELUM |
| 4 | Part 2 – Incident reporting | Bug report template belum diisi | Temuan source review terdokumentasi | Verifikasi di runtime, severity final, fix/retest, closure | PARSIAL |
| 5 | Part 2 – Test completion | Laporan belum mengukur exit criteria manual | Status aktual diperjelas | Signoff dan completion report setelah semua test diputuskan | BELUM |
| 6 | Part 3 – Test documentation | README, CSV, report rusak karena merge conflict | Konflik diselesaikan; CSV/verifier pulih | Isi actual result manual, tes gagal dan bukti | PARSIAL |
| 7 | Part 3 – Traceability | Hubungan requirement–test–evidence belum terpusat | RTM dibuat | Lengkapi requirement formal dari pemilik | PARSIAL |
| 8 | Part 3 – Test environment | Hanya info Playwright historis | Checklist staging & data test dibuat | Catat build, OS, browser, resolusi, akun uji, dump sanitasi, readiness | BELUM |
| 9 | Part 4 – Black Box | Teknik BVA/EP disebut; hasil dominan validasi DOM | Rencana manual termasuk submit, DB, role, error handling | Uji alur bisnis end-to-end secara manual | BELUM |
| 10 | Part 4 – White Box | Sebelumnya dinyatakan belum ada source | Source PHP ditinjau, jalur dan masalah ditemukan | Jalankan test cabang/keputusan manual; bukti langkah dan hasil | BELUM |
| 11 | Kebutuhan tugas eksternal | Pertanyaan wawancara sudah ada | Form validasi tetap disiapkan | Wawancara profesional QA nyata dengan bukti dan izin | BELUM |
| 12 | Konsistensi artefak | Git merge conflict + verifier crash | Parser, kolom, evidence/link, konflik diperbaiki | Jalankan pemeriksaan lagi setelah perubahan lanjutan | DITANGANI |

## Prioritas penyelesaian

**P0 – wajib sebelum klaim selesai:** (1) backup DB + salinan staging, (2) uji manual login/akses dan transaksi stok masuk/keluar beserta DB, (3) verifikasi temuan keamanan dan integritas stok, (4) catat actual result + bukti + tester, (5) retest versi yang diperbaiki.

**P1 – penting untuk evaluasi akademik:** (6) jalankan White Box manual pada percabangan yang dipilih, (7) perbarui RTM dan tingkat cakupan requirement, (8) buat laporan akhir dengan residual risk & persetujuan, (9) wawancara QA profesional sesuai arahan dosen.

**P2 – pelengkap:** (10) evidence TC-002, (11) rapikan spesifikasi nonfungsional (responsiveness, usability, security functional), (12) referensi akademik jurnal sesuai ketentuan dosen setelah divalidasi sumber aslinya.

## Kesimpulan

Dokumen dan rancangan test case sudah diperbarui. Namun, tugas QA manual masih perlu dilanjutkan karena versi source terbaru belum diuji melalui Black Box dan White Box. Hasil otomatis terdahulu tetap dicatat sebagai riwayat, terpisah dari hasil manual.
