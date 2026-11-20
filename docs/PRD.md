# PRD AlterFOSS

**Product Requirements Document** · Proyek Akhir Open Source (P14–P16) · Program Studi Sistem Informasi, UINSI Samarinda

| Item | Isi |
|---|---|
| Nama proyek | AlterFOSS (Direktori Alternatif Open Source) |
| Repositori | https://github.com/albukhorisofyan-ops/FOSS-Alternative |
| Versi dokumen | 1.0 |
| Status | Disetujui kelompok, dipakai sebagai acuan pengerjaan P15 |

**Tim dan penanggung jawab**

| Anggota | Nama | NIM | Akun GitHub | Work Package |
|---|---|---|---|---|
| Anggota 1 | | | | WP-1 (Issue #1) |
| Anggota 2 | | | | WP-2 (Issue #2) |
| Anggota 3 | | | | WP-3 (Issue #3) |
| Anggota 4 | | | | WP-4 (Issue #4), WP-5 (Issue #5) bersama |

> Cara memakai dokumen ini: baca **Bagian 1–8** (umum, wajib dibaca semua), lalu baca **Work Package milik Anda** (WP-n sama dengan Issue #n di GitHub). Bagian 9–11 berisi urutan kerja, risiko, dan checklist akhir.

---

# BAGIAN UMUM

## 1. Ringkasan Produk

### 1.1 Deskripsi dan tujuan
AlterFOSS adalah aplikasi web direktori yang membantu pengguna menemukan alternatif open source dari perangkat lunak berbayar, lengkap dengan informasi lisensi, panduan migrasi, dan kalkulator penghematan biaya.

Tujuan produk:
- Mempermudah sekolah, kampus, dan UMKM beralih dari perangkat lunak berbayar atau bajakan ke alternatif open source yang legal.
- Memberi informasi lisensi yang jelas agar pengguna tahu hak dan kewajibannya.
- Menunjukkan potensi penghematan biaya lisensi secara kuantitatif.

### 1.2 Masalah yang diselesaikan
Pengguna sulit mengetahui alternatif open source yang setara, lisensinya, dan cara berpindahnya. Latar belakang dan data pendukung ada di Proposal P14 (bagian 2) dan tidak diulang di sini.

### 1.3 Pengguna

| Peran | Kemampuan |
|---|---|
| Pengunjung (tanpa login) | Mencari dan melihat katalog, melihat detail alternatif, memakai kalkulator, mengirim usulan alternatif |
| Admin (login) | Semua kemampuan pengunjung, ditambah mengelola data perangkat lunak berbayar dan memoderasi usulan |

Hanya ada **satu jenis akun login**, yaitu admin. Tidak ada pendaftaran pengguna umum.

## 2. Ruang Lingkup

### 2.1 Fitur yang dikerjakan

| Kode | Fitur | WP |
|---|---|---|
| F1 | Katalog perangkat lunak berbayar dengan pencarian nama dan filter kategori | WP-2 |
| F2 | Halaman detail: alternatif FOSS, jenis lisensi, panduan migrasi | WP-2 |
| F3 | Kalkulator penghematan biaya (TCO sederhana) | WP-3 |
| F4 | Login admin, CRUD data perangkat lunak berbayar, moderasi usulan | WP-4 |
| F5 | Formulir usulan alternatif baru dari pengunjung | WP-4 |
| Fondasi | Skema database, model, seeder, layout | WP-1 |
| Dokumentasi | README, CONTRIBUTING, LICENSE | WP-5 |

### 2.2 Di luar ruang lingkup (tidak dikerjakan)
- Pendaftaran dan login pengguna umum
- CRUD alternatif open source dan kategori melalui antarmuka (data ini hanya dari seeder)
- Persetujuan usulan yang otomatis menambah data alternatif (persetujuan hanya mengubah status)
- Unggah gambar atau logo, multi-bahasa, notifikasi email, REST API

## 3. Alur Utama Pengguna

**Pengunjung:** membuka katalog, mencari atau memfilter, membuka detail, menekan "Hitung penghematan" menuju kalkulator, atau mengirim usulan lewat "Usulkan Alternatif".

**Admin:** membuka halaman login, masuk, mengelola daftar perangkat lunak berbayar (tambah, ubah, hapus), lalu membuka "Usulan masuk" untuk menyetujui atau menolak usulan.

## 4. Tumpukan Teknologi

| Komponen | Ketentuan |
|---|---|
| Bahasa | PHP **8.3 atau lebih baru** (disyaratkan `composer.json`) |
| Framework | Laravel **13** |
| Database | MySQL atau MariaDB |
| Antarmuka | Blade + Bootstrap 5.3.3 (CDN, tanpa build front-end) |
| Server lokal | `php artisan serve` (XAMPP hanya untuk menyalakan MySQL) |
| Lisensi | Kode **MIT**, data direktori **CC BY-SA 4.0** |

Prasyarat lokal: PHP 8.3+, Composer, MySQL/MariaDB berjalan, Git. Cek versi dengan `php -v`.

## 5. Struktur Direktori dan Penamaan

### 5.1 Struktur target (setelah semua WP selesai)

```
FOSS-Alternative/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/SoftwareController.php     (WP-4)
│   │   ├── AuthController.php               (WP-4)
│   │   ├── CatalogController.php            (WP-2)
│   │   ├── SuggestionController.php         (WP-4)
│   │   └── TcoController.php                (WP-3)
│   ├── Models/                              (WP-1)
│   │   ├── Category.php
│   │   ├── FossAlternative.php
│   │   ├── ProprietarySoftware.php
│   │   ├── Suggestion.php
│   │   └── User.php                         (bawaan Laravel)
│   └── Providers/AppServiceProvider.php     (WP-2)
├── database/
│   ├── migrations/                          (WP-1, 4 berkas baru)
│   └── seeders/                             (WP-1)
├── resources/views/
│   ├── layouts/app.blade.php                (WP-1)
│   ├── catalog/                             (WP-2)
│   ├── tco/                                 (WP-3)
│   └── auth/, suggestions/, admin/          (WP-4)
├── routes/
│   ├── web.php                              (WP-1)
│   ├── catalog.php                          (WP-2)
│   ├── tco.php                              (WP-3)
│   └── admin.php                            (WP-4)
├── docs/PRD.md                              (dokumen ini)
└── README.md, CONTRIBUTING.md, LICENSE, LICENSE-DATA.md   (WP-5)
```

### 5.2 Konvensi penamaan

| Objek | Aturan | Contoh |
|---|---|---|
| Model | Tunggal, PascalCase | `ProprietarySoftware` |
| Tabel | Jamak, snake_case | `proprietary_software` |
| Controller | PascalCase + `Controller` | `CatalogController` |
| View | folder per fitur, snake/kebab | `catalog/index.blade.php` |
| Nama route | `fitur.aksi` | `catalog.index`, `admin.software.create` |
| Branch | `jenis/deskripsi-singkat` | `feat/catalog-search` |

### 5.3 Kepemilikan berkas bersama (agar tidak konflik merge)

Berkas di bawah ini **hanya boleh diubah oleh pemiliknya**. Jika anggota lain butuh perubahan, minta pemiliknya atau buat Issue baru.

| Berkas | Pemilik |
|---|---|
| `routes/web.php`, `layouts/app.blade.php`, `DatabaseSeeder.php`, migrasi, model | WP-1 |
| `routes/catalog.php`, `AppServiceProvider.php` | WP-2 |
| `routes/tco.php` | WP-3 |
| `routes/admin.php` | WP-4 |
| `README.md`, `CONTRIBUTING.md`, `LICENSE*` | WP-5 |

## 6. Skema Database (kontrak bersama)

Skema ini dipegang bersama. **Jangan mengubah nama tabel atau kolom** tanpa kesepakatan seluruh tim.

### 6.1 Relasi
- `categories` 1 — N `proprietary_software`
- `proprietary_software` 1 — N `foss_alternatives`
- `suggestions` berdiri sendiri (tidak berelasi)
- `users` bawaan Laravel, dipakai untuk akun admin

### 6.2 Definisi tabel

**categories**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | Nama kategori |
| slug | string, unik | |
| timestamps | | |

**proprietary_software**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| category_id | FK → categories | Hapus berantai |
| name | string | Nama perangkat lunak berbayar |
| vendor | string, null | |
| price_per_year | decimal(14,0), default 0 | Rupiah per lisensi per tahun |
| description | text, null | |
| timestamps | | |

**foss_alternatives**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| proprietary_software_id | FK → proprietary_software | Hapus berantai |
| name | string | Nama alternatif open source |
| license | string | Mis. `GPL-3.0-or-later` |
| website | string, null | Situs resmi |
| description | text, null | |
| migration_guide | text, null | Langkah singkat berpindah |
| timestamps | | |

**suggestions**

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | Alternatif yang diusulkan |
| proprietary_name | string | Perangkat lunak berbayar yang digantikan |
| website | string, null | |
| note | text, null | |
| status | string, default `pending` | `pending`, `approved`, `rejected` |
| timestamps | | |

### 6.3 Data awal dan akun
Seeder mengisi kategori, perangkat lunak berbayar, dan alternatifnya, serta satu akun admin pengembangan (`admin@alterfoss.test`, kata sandi `password`). Harga di seeder adalah **contoh ilustrasi** dan harus diganti dengan data yang dapat diverifikasi sebelum dipakai di laporan.

## 7. Alur Kerja Git

### 7.1 Aturan dasar
- **Dilarang** commit langsung ke `main`. Branch `main` dilindungi, semua perubahan melalui Pull Request.
- Satu WP = satu branch = satu PR (boleh lebih dari satu PR jika dipecah).
- Setiap anggota membuat **minimal 3 commit** pada branch-nya.

### 7.2 Langkah kerja standar tiap anggota
1. `git checkout main` lalu `git pull origin main`
2. Buat branch sesuai WP (nama pada bagian WP)
3. Kerjakan, lalu commit bertahap (jangan satu commit besar)
4. `git push -u origin nama-branch`
5. Buka Pull Request ke `main`, tulis `Closes #n` pada deskripsi
6. Minta review minimal satu anggota lain, perbaiki jika ada masukan
7. Setelah disetujui, merge. Lalu semua anggota `git pull origin main` sebelum melanjutkan

### 7.3 Format commit (Conventional Commits)

Bentuk: `jenis(lingkup): deskripsi singkat dalam bahasa Indonesia`

| Jenis | Dipakai untuk |
|---|---|
| `feat` | Fitur baru |
| `fix` | Perbaikan bug |
| `docs` | Dokumentasi |
| `chore` | Pekerjaan pendukung (konfigurasi, dependensi) |
| `refactor` | Perapian kode tanpa mengubah perilaku |

### 7.4 Format Pull Request
- **Judul:** sama dengan commit utama, mis. `feat(catalog): katalog, pencarian, dan detail alternatif`
- **Deskripsi:** (1) ringkasan perubahan, (2) `Closes #n`, (3) cara menguji, (4) tangkapan layar bila ada tampilan baru

### 7.5 Konflik merge
Konflik diminimalkan dengan aturan kepemilikan berkas (5.3). Jika tetap terjadi: `git pull origin main` ke branch sendiri, selesaikan penanda konflik, jalankan ulang aplikasi untuk memastikan masih berjalan, lalu commit dan push.

## 8. Definisi Selesai (berlaku untuk semua WP)

Sebuah WP dianggap selesai bila **semua** poin terpenuhi:
- [ ] Minimal 3 commit bermakna pada branch WP
- [ ] Seluruh kriteria penerimaan WP terpenuhi
- [ ] Aplikasi berjalan setelah `php artisan migrate:fresh --seed` tanpa galat
- [ ] Tidak ada galat PHP atau pesan error di browser pada alur WP
- [ ] Tampilan terbaca baik di layar ponsel
- [ ] Setiap formulir memakai token CSRF dan validasi sisi server
- [ ] Pull Request direview dan di-merge ke `main`
- [ ] Issue terkait tertutup otomatis (`Closes #n`)

---

# BAGIAN PER WORK PACKAGE

---

## WP-1 · Fondasi Database (Issue #1)

| Item | Isi |
|---|---|
| Penanggung jawab | Anggota 1 |
| Branch | `feat/database-schema` |
| Milestone | M2 |
| Status ketergantungan | **Memblokir WP-2, WP-3, WP-4.** Harus di-merge lebih dulu. |

### Tujuan
Menyediakan fondasi yang dipakai semua anggota: skema database, model, data awal, layout bersama, dan kerangka rute per fitur.

### Berkas yang dibuat atau diubah
- `database/migrations/` (4 berkas baru: categories, proprietary_software, foss_alternatives, suggestions, dengan urutan penamaan yang menjamin `categories` dan `proprietary_software` terbentuk sebelum `foss_alternatives`)
- `app/Models/Category.php`, `ProprietarySoftware.php`, `FossAlternative.php`, `Suggestion.php`
- `database/seeders/AlterfossSeeder.php` dan `DatabaseSeeder.php`
- `resources/views/layouts/app.blade.php`
- `routes/web.php` (memuat tiga berkas rute fitur) dan berkas kosong `routes/catalog.php`, `routes/tco.php`, `routes/admin.php`
- `.env` lokal (tidak di-commit)

### Spesifikasi
- Skema tepat seperti Bagian 6.
- Model memiliki relasi: `Category` memiliki banyak `ProprietarySoftware`; `ProprietarySoftware` milik satu `Category` dan memiliki banyak `FossAlternative`; `FossAlternative` milik satu `ProprietarySoftware`.
- Model `ProprietarySoftware` menetapkan nama tabel `proprietary_software` secara eksplisit.
- Seeder idempoten (aman dijalankan berulang) dan memuat minimal 5 perangkat lunak berbayar dengan alternatifnya, plus akun admin dev.
- Layout menyediakan bagian `title` dan `content`, navbar (Katalog, Kalkulator TCO, Usulkan Alternatif, Masuk/Keluar Admin), serta menampilkan pesan flash `session('status')`.
- Tautan navbar memakai alamat URL langsung (`/`, `/kalkulator`, `/saran`, `/login`, `/logout`, `/admin`) supaya halaman tidak error walau rute fitur lain belum ada.
- `routes/web.php` hanya berisi pemuatan tiga berkas rute fitur.

### Kontrak dengan WP lain
- **Menyediakan:** tabel, model, data awal, layout, dan berkas rute kosong.
- **Memakai:** tidak ada.

### Langkah kerja dan commit yang disarankan

| Urutan | Pesan commit |
|---|---|
| 1 | `feat(db): tambah migrasi tabel categories, proprietary_software, foss_alternatives, suggestions` |
| 2 | `feat(db): tambah model Eloquent dan relasinya` |
| 3 | `feat(db): tambah seeder data awal dan akun admin dev` |
| 4 | `feat(ui): tambah layout dasar dan berkas rute per fitur` |

Judul PR: `feat(db): skema database, model, seeder, dan layout dasar`

### Kriteria penerimaan
- [ ] `php artisan migrate:fresh --seed` berhasil pada database kosong
- [ ] Keempat tabel dan data contoh terlihat di phpMyAdmin
- [ ] Relasi model berfungsi (mis. mengambil alternatif dari sebuah perangkat lunak melalui `tinker`)
- [ ] Menjalankan seeder dua kali tidak menggandakan data
- [ ] Halaman apa pun yang memakai layout tidak error

### Cara menguji
1. Buat database kosong `alterfoss`, atur `.env`.
2. Jalankan `php artisan migrate:fresh --seed`.
3. Periksa tabel di phpMyAdmin.
4. Jalankan `php artisan tinker` dan uji relasi (ambil satu perangkat lunak beserta alternatifnya).

---

## WP-2 · Katalog, Pencarian, dan Detail (Issue #2)

| Item | Isi |
|---|---|
| Penanggung jawab | Anggota 2 |
| Branch | `feat/catalog-search` |
| Milestone | M2 |
| Status ketergantungan | Menunggu WP-1 ter-merge |

### Tujuan
Pengunjung dapat mencari perangkat lunak berbayar dan melihat alternatif open source beserta lisensi dan panduan migrasinya.

### Berkas yang dibuat atau diubah
- `app/Http/Controllers/CatalogController.php`
- `routes/catalog.php`
- `resources/views/catalog/index.blade.php` dan `show.blade.php`
- `app/Providers/AppServiceProvider.php` (mengaktifkan paginasi Bootstrap 5)

### Spesifikasi
- **Rute:** `GET /` (nama `catalog.index`) dan `GET /software/{software}` (nama `catalog.show`).
- **Daftar (`/`):**
  - Pencarian berdasarkan nama lewat parameter `q` (pencocokan sebagian, tidak peka huruf besar).
  - Filter kategori lewat parameter `kategori` (id kategori).
  - Pencarian dan filter dapat digabung.
  - Urut berdasarkan nama, paginasi 9 item per halaman, parameter pencarian tetap terbawa saat pindah halaman.
  - Tiap kartu menampilkan nama, vendor, kategori, dan daftar nama alternatif, serta tautan ke detail.
  - Pesan "Tidak ada hasil." bila kosong.
- **Detail:**
  - Menampilkan nama, vendor, kategori, estimasi harga per lisensi per tahun (format rupiah ribuan), dan deskripsi.
  - Setiap alternatif menampilkan nama, **lencana lisensi**, deskripsi, panduan migrasi, dan tautan situs resmi (dibuka di tab baru dengan `rel="noopener"`).
  - Tombol "Hitung penghematan" menuju `/kalkulator?software={id}`.
  - Id yang tidak ada menghasilkan halaman 404.
- Tampilan memakai layout dari WP-1.

### Kontrak dengan WP lain
- **Memakai:** model dan relasi dari WP-1 (`ProprietarySoftware`, `Category`, `FossAlternative`).
- **Menyediakan:** tautan `/kalkulator?software={id}` yang dibaca WP-3.

### Langkah kerja dan commit yang disarankan

| Urutan | Pesan commit |
|---|---|
| 1 | `feat(catalog): tambah CatalogController dan rute katalog` |
| 2 | `feat(catalog): tambah tampilan daftar dengan pencarian dan filter kategori` |
| 3 | `feat(catalog): tambah halaman detail alternatif` |
| 4 | `chore: aktifkan paginasi Bootstrap 5` |

Judul PR: `feat(catalog): katalog, pencarian, dan detail alternatif`

### Kriteria penerimaan
- [ ] Mencari "photoshop" menampilkan hasil yang benar
- [ ] Filter kategori bekerja sendiri maupun bersama pencarian
- [ ] Paginasi tampil rapi dan tidak menghilangkan kata kunci
- [ ] Detail menampilkan lisensi dan panduan migrasi setiap alternatif
- [ ] `/software/9999` menghasilkan 404
- [ ] Tampilan rapi di layar ponsel

### Cara menguji
Buka `/`, cari beberapa kata kunci (ada dan tidak ada), coba tiap kategori, buka beberapa detail, lalu persempit jendela browser untuk menguji tampilan ponsel.

---

## WP-3 · Kalkulator Penghematan (Issue #3)

| Item | Isi |
|---|---|
| Penanggung jawab | Anggota 3 |
| Branch | `feat/tco-calculator` |
| Milestone | M2 |
| Status ketergantungan | Menunggu WP-1 ter-merge |

### Tujuan
Menghitung estimasi penghematan dengan membandingkan biaya lisensi perangkat lunak berbayar dengan biaya migrasi ke alternatif open source.

### Berkas yang dibuat atau diubah
- `app/Http/Controllers/TcoController.php`
- `routes/tco.php`
- `resources/views/tco/index.blade.php`

### Spesifikasi
- **Rute:** `GET /kalkulator` (nama `tco.index`) dan `POST /kalkulator` (nama `tco.hitung`).
- **Input:**

| Input | Aturan |
|---|---|
| Perangkat lunak berbayar | Wajib, harus ada di database |
| Jumlah PC | Wajib, bilangan bulat 1 sampai 100.000 |
| Jumlah tahun | Wajib, bilangan bulat 1 sampai 10 |
| Biaya migrasi/pelatihan FOSS (Rp) | Opsional, angka ≥ 0, default 0 |

- **Rumus:**
  - Biaya lisensi = harga per tahun × jumlah PC × jumlah tahun
  - Biaya alternatif open source = biaya migrasi (lisensi FOSS dianggap Rp0)
  - Estimasi penghematan = biaya lisensi dikurangi biaya alternatif
- **Hasil:** tabel berisi tiga angka di atas, format rupiah dengan pemisah ribuan, serta catatan bahwa ini estimasi sederhana.
- Bila parameter `?software={id}` ada pada GET, perangkat lunak tersebut terpilih otomatis.
- Setelah perhitungan, isian formulir tetap terisi dan hasil tampil di halaman yang sama.
- Bila biaya migrasi lebih besar dari biaya lisensi (penghematan negatif), tampilkan pesan yang jelas, mis. "Pada skenario ini belum ada penghematan." (butir ini belum ada pada kerangka kode awal dan menjadi tugas anggota 3).
- Pesan galat validasi tampil di samping isian yang bermasalah.

### Kontrak dengan WP lain
- **Memakai:** kolom `price_per_year` pada `proprietary_software` (WP-1) dan tautan dari halaman detail (WP-2).
- **Menyediakan:** tidak ada.

### Langkah kerja dan commit yang disarankan

| Urutan | Pesan commit |
|---|---|
| 1 | `feat(tco): tambah TcoController dan validasi input` |
| 2 | `feat(tco): tambah formulir kalkulator` |
| 3 | `feat(tco): tampilkan hasil perhitungan dan format rupiah` |
| 4 | `fix(tco): tangani skenario tanpa penghematan` |

Judul PR: `feat(tco): kalkulator penghematan biaya`

### Kriteria penerimaan
- [ ] Contoh: harga Rp1.500.000, 100 PC, 3 tahun, migrasi 0 menghasilkan Rp450.000.000
- [ ] Input kosong, negatif, tahun 11, atau PC 0 ditolak dengan pesan galat
- [ ] Format rupiah mudah dibaca
- [ ] Skenario penghematan negatif ditangani
- [ ] Pilihan terisi otomatis dari tautan `?software=`
- [ ] Minimal 3 commit, PR di-merge

### Cara menguji
Uji hitungan dengan angka sederhana, coba semua input tidak valid, buka kalkulator lewat tombol di halaman detail WP-2, dan coba biaya migrasi yang sangat besar.

---

## WP-4 · Admin, CRUD, dan Usulan (Issue #4)

| Item | Isi |
|---|---|
| Penanggung jawab | Anggota 4 |
| Branch | `feat/admin-suggestions` |
| Milestone | M2 |
| Status ketergantungan | Menunggu WP-1 ter-merge |

### Tujuan
Admin dapat masuk, mengelola data perangkat lunak berbayar, dan memoderasi usulan dari pengunjung. Pengunjung dapat mengirim usulan.

### Berkas yang dibuat atau diubah
- `app/Http/Controllers/AuthController.php`, `SuggestionController.php`, `Admin/SoftwareController.php`
- `routes/admin.php`
- `resources/views/auth/login.blade.php`
- `resources/views/suggestions/create.blade.php`
- `resources/views/admin/software/` (index, create, edit, dan bagian formulir bersama)
- `resources/views/admin/suggestions/index.blade.php`

### Spesifikasi

**Rute**

| Metode dan URL | Nama | Akses |
|---|---|---|
| GET `/login` | `login` | Tamu (yang sudah login dialihkan) |
| POST `/login` | `login.attempt` | Tamu |
| POST `/logout` | `logout` | Semua |
| GET, POST `/saran` | `suggestions.create`, `suggestions.store` | Publik |
| `/admin` | `admin.home` | Admin, dialihkan ke daftar software |
| Resource `/admin/software` (tanpa show) | `admin.software.*` | Admin |
| GET `/admin/suggestions` | `admin.suggestions.index` | Admin |
| PATCH `/admin/suggestions/{id}` | `admin.suggestions.update` | Admin |

- Rute bernama `login` **wajib ada** karena dipakai middleware `auth` untuk mengalihkan tamu.
- **Login:** validasi email dan kata sandi; bila berhasil, sesi diperbarui dan pengguna dialihkan ke tujuan semula atau daftar software; bila gagal, kembali ke form dengan pesan "Email atau kata sandi salah." dan email tetap terisi.
- **Logout:** sesi dihapus dan token diperbarui, lalu dialihkan ke `/`.
- **CRUD perangkat lunak berbayar:**
  - Daftar (paginasi 15): nama, kategori, harga per tahun, tombol Ubah dan Hapus (Hapus dengan konfirmasi).
  - Validasi: kategori wajib ada, nama wajib (maks. 255), vendor opsional, harga wajib angka ≥ 0, deskripsi opsional (maks. 2000).
  - Pesan flash sukses setelah tambah, ubah, hapus.
- **Formulir usulan (publik):** nama alternatif wajib, nama perangkat lunak berbayar wajib, situs opsional (harus URL valid), catatan opsional (maks. 2000). Setelah terkirim, tampil pesan terima kasih. Status awal `pending`.
- **Moderasi:** daftar usulan terbaru (paginasi 15) dengan status, serta tombol Setujui dan Tolak. Status hanya boleh `approved` atau `rejected`. Persetujuan **hanya mengubah status**, tidak menambah data alternatif.
- Seluruh halaman `/admin/*` tidak dapat dibuka tanpa login.

### Kontrak dengan WP lain
- **Memakai:** `User` dan akun admin dari seeder, `ProprietarySoftware`, `Category`, `Suggestion` (WP-1), serta layout (WP-1) yang sudah memiliki tautan `/login`, `/logout`, `/admin`.
- **Menyediakan:** halaman admin dan formulir usulan yang ditautkan dari navbar.

### Langkah kerja dan commit yang disarankan

| Urutan | Pesan commit |
|---|---|
| 1 | `feat(auth): tambah login dan logout admin` |
| 2 | `feat(admin): tambah CRUD perangkat lunak berbayar` |
| 3 | `feat(suggestions): tambah formulir usulan publik` |
| 4 | `feat(admin): tambah moderasi usulan` |

Judul PR: `feat(admin): login admin, CRUD software, dan moderasi usulan`

### Kriteria penerimaan
- [ ] `/admin` dan `/admin/software` mengalihkan ke login jika belum masuk
- [ ] Login dengan akun seeder berhasil, kata sandi salah ditolak
- [ ] Tambah, ubah, dan hapus data berjalan dan tervalidasi
- [ ] Input tidak valid menampilkan pesan galat tanpa menghilangkan isian
- [ ] Usulan dari pengunjung muncul di daftar admin dan statusnya dapat diubah
- [ ] Nilai status selain `approved` atau `rejected` ditolak
- [ ] Logout bekerja dan halaman admin kembali terkunci

### Cara menguji
Uji tanpa login, login benar dan salah, lakukan satu siklus CRUD penuh, kirim usulan sebagai pengunjung (tanpa login) lalu moderasi sebagai admin, dan coba isian tidak valid pada setiap formulir.

---

## WP-5 · Dokumentasi Repositori (Issue #5)

| Item | Isi |
|---|---|
| Penanggung jawab | Anggota 4, dibantu Anggota 1 |
| Branch | `docs/readme-contributing` |
| Milestone | M2 |
| Status ketergantungan | Pengerjaan teks bisa paralel, **tangkapan layar menunggu WP-2, 3, 4 ter-merge** |

### Tujuan
Dokumentasi repositori yang profesional agar proyek mudah dipasang dan dikontribusikan (30% rubrik P15).

### Berkas yang dibuat atau diubah
- `README.md`, `CONTRIBUTING.md`, `LICENSE`, `LICENSE-DATA.md`
- `docs/` (tangkapan layar aplikasi)
- (Opsional) menghapus `AGENTS.md` dan `CLAUDE.md` bawaan kerangka Laravel

### Spesifikasi

**README.md** wajib memuat, berurutan:
1. Judul (dan logo bila ada) beserta lencana lisensi dan framework
2. Deskripsi singkat
3. Daftar fitur unggulan
4. Prasyarat sistem (PHP **8.3+**, Composer, MySQL/MariaDB)
5. Panduan instalasi langkah demi langkah (clone, `composer install`, salin `.env.example`, `key:generate`, buat database, atur `.env`, `migrate --seed`)
6. Cara menjalankan (`php artisan serve`) dan akun admin pengembangan
7. Tangkapan layar aplikasi (katalog, detail, kalkulator, admin)
8. Lisensi (MIT untuk kode, CC BY-SA 4.0 untuk data)
9. Kredit tim pengembang (nama, NIM, peran)

- **README harus bersih dari teks bawaan Laravel** ("About Laravel", "Learning Laravel", dll.).
- **CONTRIBUTING.md:** cara melaporkan issue, cara fork dan membuat branch, format commit, cara mengajukan PR, dan aturan (tidak boleh commit ke `main`).
- **LICENSE:** teks MIT lengkap. Nama pemegang hak cipta boleh diubah menjadi nama tim (mis. "AlterFOSS Contributors").
- **LICENSE-DATA.md:** keterangan CC BY-SA 4.0 untuk data direktori.

### Kontrak dengan WP lain
- **Memakai:** hasil WP-2, 3, 4 untuk tangkapan layar dan daftar fitur yang akurat.
- **Menyediakan:** panduan instalasi yang diuji oleh seluruh tim.

### Langkah kerja dan commit yang disarankan

| Urutan | Pesan commit |
|---|---|
| 1 | `docs: bersihkan README dari teks bawaan Laravel dan tulis deskripsi` |
| 2 | `docs: tambah prasyarat, panduan instalasi, dan cara menjalankan` |
| 3 | `docs: tambah CONTRIBUTING.md` |
| 4 | `docs: tambah tangkapan layar dan kredit tim` |

Judul PR: `docs: README, CONTRIBUTING, dan lisensi`

### Kriteria penerimaan
- [ ] Orang yang belum pernah melihat proyek dapat menjalankannya hanya dengan mengikuti README (**uji clone dari nol**)
- [ ] Semua tautan dan perintah di README benar
- [ ] GitHub mendeteksi lisensi MIT pada halaman depan repositori
- [ ] Tangkapan layar tampil di README dan halaman depan repositori rapi
- [ ] Tidak tersisa teks bawaan Laravel di README
- [ ] Minimal 3 commit, PR di-merge

### Cara menguji
Pada folder baru, `git clone` repositori lalu ikuti README apa adanya sampai aplikasi tampil. Catat setiap langkah yang gagal atau membingungkan, lalu perbaiki README.

---

# BAGIAN PENUTUP

## 9. Urutan Kerja dan Ketergantungan

```
WP-1 (fondasi)  ──merge──►  WP-2, WP-3, WP-4 (paralel)  ──merge──►  WP-5 (tangkapan layar, uji clone)
```

1. **WP-1 dikerjakan dan di-merge lebih dulu.** Selama menunggu, Anggota 2–4 boleh membaca spesifikasi dan merancang, tetapi belum membuat branch kerja.
2. **WP-2, WP-3, WP-4 berjalan paralel** setelah `git pull origin main`. Karena berkas rute dipisah, ketiganya tidak saling mengunci.
3. **WP-5:** teks README dan CONTRIBUTING bisa ditulis sejak awal, tangkapan layar diambil setelah WP-2, 3, 4 ter-merge.
4. Titik rawan konflik: `layouts/app.blade.php` dan `AppServiceProvider.php`. Hanya pemiliknya (5.3) yang boleh mengubahnya.

## 10. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| PHP di XAMPP di bawah 8.3 | `composer install` gagal | Cek `php -v` di awal, perbarui PHP jika perlu |
| MySQL XAMPP belum menyala | `migrate` gagal terhubung | Nyalakan modul MySQL, cek `DB_*` di `.env` |
| Urutan migrasi salah | Galat foreign key | Pastikan nama berkas migrasi mengurutkan `categories` dan `proprietary_software` lebih dulu |
| Konflik merge | Waktu terbuang | Patuhi kepemilikan berkas (5.3), `git pull origin main` rutin |
| `.env` ikut ter-commit | Kebocoran konfigurasi | `.env` sudah ada di `.gitignore`, jangan dipaksa `git add -f` |
| Harga contoh dianggap data resmi | Laporan tidak akurat | Ganti harga seeder dengan data yang dapat diverifikasi |
| Anggota terlambat | PR menumpuk di akhir | Merge WP-1 secepatnya, jadwalkan batas internal per WP |

## 11. Checklist Akhir Sebelum P16

- [ ] Semua 5 PR sudah di-merge dan riwayat PR terlihat di GitHub
- [ ] Tidak ada commit langsung ke `main` setelah proteksi aktif (kecuali commit awal inisialisasi)
- [ ] Setiap anggota memiliki minimal 3 commit
- [ ] Uji **clone dari nol** pada folder baru berhasil mengikuti README
- [ ] README dan CONTRIBUTING lengkap, lisensi terdeteksi, lencana tampil
- [ ] Tangkapan layar halaman depan repositori, fitur berjalan, dan riwayat PR sudah diambil
- [ ] Seluruh Issue tertutup, papan proyek berstatus Done
- [ ] Harga dan lisensi pada seeder sudah diverifikasi
- [ ] Laporan P15 (`Tugas15_DokumentasiFinalFOSS_KelompokXX.pdf`) memuat salinan README dan CONTRIBUTING serta tautan repositori