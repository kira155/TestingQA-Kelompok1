# KELOMPOK 1 — TESTING & QUALITY ASSURANCE

<p align="center">
  <strong>UNIVERSITAS PAMULANG</strong><br>
  Program Studi Teknik Informatika<br>
  <strong>Dokumentasi Tugas Kelompok — Testing & Quality Assurance</strong>
</p>

---

## 1. Profil Proyek

Repositori ini digunakan oleh **Kelompok 1** untuk mendokumentasikan tugas mata kuliah **Testing & Quality Assurance**, mulai dari pemilihan objek pengujian, perencanaan, penyusunan kasus uji, pelaksanaan testing, pelaporan temuan, hingga evaluasi hasil pengujian.

Objek pengujian direncanakan berupa **website atau aplikasi UMKM dengan ruang lingkup yang tidak terlalu besar**, agar pengujian dapat dilakukan secara sistematis. Objek yang dipilih akan dicantumkan setelah disepakati kelompok.

| Informasi | Keterangan |
|---|---|
| Perguruan tinggi | Universitas Pamulang |
| Program studi | Teknik Informatika |
| Mata kuliah | Testing & Quality Assurance |
| Kelompok | 1 |
| Objek pengujian | [Nama website/aplikasi UMKM — belum ditentukan] |
| URL / sumber aplikasi | [Diisi setelah objek ditentukan] |
| Acuan dokumentasi | ISO/IEC/IEEE 29119-3:2021 (disesuaikan dengan kebutuhan proyek) |
| Periode pengerjaan | [Semester / tahun akademik] |
| Status proyek | Persiapan dokumentasi |

> **Catatan:** Dokumen ini merupakan kerangka kerja. Data pengujian, hasil konsultasi, dan temuan bug hanya diisi berdasarkan kegiatan yang benar-benar dilakukan.

## 2. Anggota Kelompok

| No. | Nama Anggota | NIM |
|:---:|---|---|
| 1 | Abdul Kholik | 231011400097 |
| 2 | Ahmad Reza | 231011403385 |
| 3 | Akbar Rusmanto | 231011401424 |
| 4 | Adjie Febrianto | 231011400092 |

**Jumlah anggota: 4 mahasiswa.**

Pembagian peran dan penanggung jawab setiap pekerjaan akan ditetapkan melalui kesepakatan kelompok, lalu dicatat dalam laporan progres.

## 3. Tujuan Tugas

1. Menentukan website atau aplikasi UMKM yang layak dijadikan objek pengujian.
2. Mengidentifikasi fitur dan kebutuhan yang akan diuji.
3. Menyusun dokumentasi pengujian dengan mengacu pada **ISO/IEC/IEEE 29119-3:2021**, menggunakan penyesuaian (*tailoring*) sesuai skala tugas.
4. Merancang dan melaksanakan test case serta mendokumentasikan hasil aktual dan status pengujian.
5. Mencatat temuan bug atau insiden beserta bukti yang dapat ditelusuri.
6. Melakukan diskusi atau konsultasi dengan praktisi QA profesional tentang penerapan proses testing di dunia kerja.
7. Memantau dan melaporkan progres secara berkala melalui GitHub.

## 4. Ruang Lingkup Pengujian

Ruang lingkup final ditetapkan setelah objek UMKM dipilih. Berikut kategori yang dapat dipertimbangkan:

| Kategori | Fokus | Keterangan |
|---|---|---|
| Functional Testing | Alur dan keluaran fitur utama | Disesuaikan dengan fitur aplikasi |
| UI / Usability Testing | Tampilan, navigasi, pesan validasi | Dilakukan sesuai kebutuhan |
| Negative Testing | Input tidak valid atau skenario gagal | Dilakukan pada fitur yang relevan |
| Compatibility Testing | Tampilan/perilaku pada browser atau perangkat | Jika lingkungan tersedia |
| Regression Testing | Uji ulang setelah ada perbaikan | Jika terdapat perubahan aplikasi |

**Di luar ruang lingkup:** pengujian yang memerlukan akses tidak sah, mengganggu layanan UMKM, atau membutuhkan izin khusus. Aktivitas pengujian harus dilakukan dengan izin pemilik sistem bila diperlukan.

## 5. Alur Pengerjaan

1. **Persiapan:** menentukan objek UMKM, izin akses, ruang lingkup, dan pembagian tugas.
2. **Analisis:** mengidentifikasi fitur, kebutuhan, risiko, dan prioritas pengujian.
3. **Perencanaan:** menyusun *Test Plan*, lingkungan, dan data uji.
4. **Perancangan:** menulis *Test Case* dan *Test Procedure*.
5. **Eksekusi:** menjalankan pengujian dan menyimpan bukti hasil aktual.
6. **Pelaporan:** mendokumentasikan bug, status pengujian, dan tindak lanjut.
7. **Konsultasi QA:** membandingkan materi perkuliahan dengan praktik pengujian profesional.
8. **Penyelesaian:** menyusun laporan hasil akhir dan mengarsipkan artefak di GitHub.

## 6. Dokumen Pengujian

Dokumentasi disusun dengan **ISO/IEC/IEEE 29119-3:2021** sebagai acuan struktur dan istilah; penerapannya disesuaikan dengan kebutuhan tugas, bukan klaim bahwa semua ketentuan standar telah diaudit atau dipenuhi sepenuhnya.

| Dokumen / artefak | Fungsi |
|---|---|
| Profil objek uji | Identitas UMKM, aplikasi, fitur, dan batasan akses |
| Test Plan | Tujuan, cakupan, strategi, jadwal, risiko, dan kriteria selesai |
| Test Model / daftar kebutuhan | Dasar pemilihan skenario dan keterlacakan fitur |
| Test Case Specification | Prasyarat, data masukan, langkah, dan hasil yang diharapkan |
| Test Procedure | Urutan eksekusi dan persiapan pengujian |
| Test Data & Environment | Kebutuhan data serta perangkat, browser, dan konfigurasi |
| Test Execution Log & Results | Catatan pelaksanaan, hasil aktual, dan status pass/fail/blocked |
| Bug / Incident Report | Langkah reproduksi, severity, priority, bukti, dan status |
| Test Status Report | Ringkasan progres pengujian secara mingguan |
| Test Completion Report | Ringkasan hasil akhir, risiko tersisa, dan kesimpulan |
| Konsultasi QA Profesional | Identitas narasumber sesuai izin, pertanyaan, ringkasan jawaban, refleksi |

Dokumen organisasi seperti *Test Policy* atau *Organizational Test Practices* dapat ditambahkan jika diminta dosen atau memang relevan dengan proyek.

## 7. Konsultasi dengan QA Profesional

Kelompok akan berupaya melakukan konsultasi dengan praktisi yang memiliki pengalaman sebagai QA Tester, Software Tester, atau QA Engineer.

Hal yang akan didiskusikan meliputi metode pengujian di perusahaan, pemakaian dokumen testing, cara memilih teknik pengujian, penanganan bug, serta perbedaan antara teori perkuliahan dan proses testing yang diterapkan di pekerjaan.

| Informasi konsultasi | Isi |
|---|---|
| Nama / inisial narasumber | [Belum diisi] |
| Jabatan dan pengalaman QA | [Belum diisi] |
| Tanggal dan media konsultasi | [Belum diisi] |
| Pokok pembahasan | [Belum diisi] |
| Ringkasan hasil dan pembelajaran | [Belum diisi] |
| Bukti / dokumentasi | [Tautan; berdasarkan izin narasumber] |

Hasil konsultasi akan dicatat sebagai **temuan wawancara**, bukan sebagai pernyataan yang mewakili seluruh industri QA.

## 8. Progres Mingguan

Progres diperiksa setiap minggu. Rencana di bawah ini bersifat contoh dan dapat disesuaikan dengan jadwal dosen.

| Minggu | Target kegiatan | Status | Bukti / tautan |
|:---:|---|---|---|
| 1 | Menentukan calon aplikasi UMKM dan kebutuhan tugas | Belum dimulai | — |
| 2 | Menentukan objek, cakupan, dan pembagian tugas | Belum dimulai | — |
| 3 | Menyusun Test Plan dan identifikasi fitur | Belum dimulai | — |
| 4 | Menyusun Test Case, prosedur, data, dan lingkungan uji | Belum dimulai | — |
| 5 | Melaksanakan pengujian tahap awal dan membuat bukti | Belum dimulai | — |
| 6 | Melanjutkan pengujian, bug report, dan uji ulang bila ada | Belum dimulai | — |
| 7 | Melaksanakan konsultasi QA dan merangkum pembahasan | Belum dimulai | — |
| 8 | Menyusun Test Completion Report dan finalisasi GitHub | Belum dimulai | — |

**Pilihan status:** Belum dimulai · Dalam proses · Selesai · Tertunda.

Setiap minggu, catat tanggal pemeriksaan, pekerjaan yang selesai, kendala, rencana berikutnya, penanggung jawab, serta tautan *commit* atau dokumen pendukung di folder `07-Progres-Mingguan/`.

## 9. Struktur Repositori

```text
KELOMPOK-1-TESTING-QA/
├── README.md
├── 01-Profil-Objek-UMKM/
├── 02-Test-Plan/
├── 03-Test-Case-dan-Prosedur/
├── 04-Test-Execution-dan-Hasil/
├── 05-Bug-Report-dan-Bukti/
├── 06-Konsultasi-QA/
├── 07-Progres-Mingguan/
├── 08-Laporan-Akhir/
└── 09-Referensi/
```

> Nama folder di atas adalah **rencana struktur**. Folder dapat dibuat bertahap; GitHub hanya menampilkan folder yang memiliki berkas di dalamnya.

## 10. Tools yang Digunakan

| Tools | Kegunaan |
|---|---|
| GitHub | Pengumpulan tugas, kolaborasi, riwayat perubahan, dan pemantauan progres |
| Microsoft Word / Excel | Penyusunan dokumen dan tabel hasil pengujian |
| Browser / Developer Tools | Menjalankan pengujian website serta memeriksa informasi teknis yang diizinkan |
| Screenshot / screen recording | Bukti pendukung hasil pengujian |
| Tools tambahan | [Diisi jika benar-benar digunakan] |

## 11. Ketentuan Kolaborasi

- Setiap anggota mengerjakan tanggung jawab yang telah disepakati.
- Setiap perubahan dokumen dicatat melalui *commit* GitHub yang menjelaskan isi perubahan.
- Test case diberi ID unik dan hasilnya dicatat berdasarkan eksekusi nyata.
- Bug dilaporkan dengan langkah reproduksi, hasil aktual, hasil yang diharapkan, dan bukti jika tersedia.
- Data pribadi, password, token, dan informasi internal UMKM tidak diunggah ke repositori publik.
- Perubahan yang berdampak pada hasil bersama didiskusikan terlebih dahulu.
- Laporan progres diperbarui sebelum pemeriksaan mingguan.

## 12. Referensi

1. ISO/IEC/IEEE 29119-3:2021 — *Software and systems engineering — Software testing — Part 3: Test documentation*.
2. Materi perkuliahan Testing & Quality Assurance — [Lengkapi nama dosen / sumber].
3. Dokumen kebutuhan aplikasi atau informasi yang diizinkan oleh pihak UMKM — [Lengkapi setelah objek ditetapkan].
4. Hasil konsultasi QA profesional — [Lengkapi setelah konsultasi berlangsung].

---

<p align="center">
  <strong>KELOMPOK 1 — TEKNIK INFORMATIKA</strong><br>
  Universitas Pamulang<br>
  <em>Testing with Evidence, Reporting with Integrity.</em>
</p>

**© 2026 Kelompok 1 — Universitas Pamulang**
