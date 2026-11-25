# Requirements Traceability Matrix (RTM)

Referensi requirement berikut bersifat **kebutuhan teramati dari fitur source**, belum dokumen kebutuhan formal yang disahkan pemilik aplikasi. Status pengujian mencerminkan bukti historis, bukan testing manual baru.

| Req ID | Kebutuhan yang perlu diverifikasi | Pengujian historis | Uji manual/regresi tambahan | Risiko | Status |
|---|---|---|---|---|---|
| RQ-01 | Login hanya dengan kredensial valid dan session aktif | TC-001..TC-007 | MAN-01, MAN-02, REG-010 | High | Historis tercatat; manual NOT RUN |
| RQ-02 | Staff/admin hanya dapat membuka halaman sesuai role | TC-008 | MAN-03, WB-01, REG-006 | Critical | NOT RUN |
| RQ-03 | Daftar stok konsisten dengan ledger transaksi | TC-009..TC-012 | MAN-04, MAN-09, WB-03 | Critical | NOT RUN |
| RQ-04 | Permintaan barang mencatat qty dan request ID yang benar | TC-014..TC-018 | MAN-05, WB-04 | High | NOT RUN |
| RQ-05 | Barang masuk menambah stok dan tidak melampaui request | TC-019 | MAN-06, WB-04 | Critical | NOT RUN |
| RQ-06 | Barang keluar menolak stok kurang dan memperbarui saldo | TC-020..TC-030 | MAN-07, MAN-08, WB-02 | Critical | NOT RUN |
| RQ-07 | Registrasi tidak bisa menjadi admin melalui POST langsung | — | REG-005, REG-006 | Critical | NOT RUN |
| RQ-08 | Perubahan master barang tidak merusak transaksi historis | — | MAN-09, WB-03 | Critical | NOT RUN |
| RQ-09 | Laporan barang menampilkan agregasi masuk/keluar tanpa duplikasi | TC-008, TC-009 (parsial) | MAN-10, WB-03 | High | NOT RUN |
| RQ-10 | Semua form perubahan data hanya menerima request sah dari session pengguna | — | MAN-11, WB-01 | Critical | NOT RUN |

**Aturan penelusuran:** setiap hasil manual harus mencatat Req ID + TC/MAN/WB/REG ID + versi source + actual result + foto/screenshot + bug ID bila FAIL. Requirement ini harus disahkan pemilik atau dinyatakan sebagai asumsi eksplisit.
