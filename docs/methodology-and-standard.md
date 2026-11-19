# Pemetaan Metodologi ke ISO/IEC 25002:2024

**Standar acuan:** ISO/IEC 25002:2024, *Systems and software engineering — Systems and software Quality Requirements and Evaluation (SQuaRE) — Quality model overview and usage*.

**Keterangan metodologis:** Dokumen 25002 memberikan cara memahami dan menerapkan model kualitas pada kebutuhan, pengukuran, dan evaluasi. Untuk software product quality, 25002 merujuk keluarga model kualitas ISO/IEC 25010. Manual Black Box Testing adalah **teknik eksekusi pilihan proyek**, bukan metode yang ditetapkan oleh 25002.

| Klausul | Prinsip | Bukti penerapan pada proyek |
|---|---|---|
| 5 | Model kualitas disesuaikan dengan konteks penggunaan | Hanya fitur dan konteks website bakery publik dipilih |
| 6 | Karakteristik, subkarakteristik, measures membantu persyaratan dan evaluasi | Menentukan aspek kualitas dan kriteria observasi |
| 7.1 | Model terstruktur pada target entity, karakteristik dan measures | Target website; pengelompokan kasus; target observasi |
| 7.4 | Prioritas menyesuaikan konteks | Form dan navigasi prioritas High, kosmetik Low |
| 8.2.2 | Menentukan quality requirements dari kebutuhan stakeholder | Expected Result test case; status asumsi bila perlu klarifikasi |
| 8.2.4 | Mengevaluasi kualitas melalui test cases yang sesuai | 30 kasus manual Black Box; actual/status/evidence |
| 8.2.5 | Mendefinisikan ukuran kualitas | Ringkasan pass/fail, cakupan eksekusi, rata-rata durasi |

## Contoh traceability

| Stakeholder need | Aspek | Test case | Evidence | Kriteria |
|---|---|---|---|---|
| Melihat produk dan harga | Functional Suitability | TC-002, TC-009, TC-010 | TC-xxx.png | Menu muncul dan informasi dapat dibaca |
| Menghubungi toko dengan data tepat | Functional Suitability | TC-013 s.d. TC-022 | TC-xxx.png | Validasi sesuai kebutuhan yang disepakati |
| Mengakses via smartphone | Compatibility | TC-026 | TC-026.png | Tanpa overlap/terpotong |
| Navigasi mudah | Interaction Capability | TC-023 s.d. TC-025 | TC-xxx.png | Fokus/feedback jelas |
| Website responsif | Performance Efficiency | TC-029, TC-030 | Lembar pengukuran | Dibandingkan target internal terdokumentasi |

## Catatan kehati-hatian

- Tidak boleh mengklaim situs sudah lolos audit ISO hanya berdasarkan 30 test case manual.
- Standar tidak mewajibkan 30 kasus, 5 detik, ataupun 2 detik. Itu pilihan desain proyek.
- ISO/IEC 25002:2024 bukan standar berlabel IEEE. Bila dosen meminta IEEE wajib, lengkapi referensi IEEE atau ISO/IEC/IEEE tahun 2022–2026.
- File standar PDF bersifat berlisensi pribadi dan **tidak dipublikasi** dalam repository ini.
