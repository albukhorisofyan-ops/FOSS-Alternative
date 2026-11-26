# Panduan Kontribusi

Terima kasih sudah ingin berkontribusi pada AlterFOSS.

## Melaporkan masalah (Issue)

1. Cari dulu di tab **Issues** apakah masalah yang sama sudah dilaporkan.
2. Buka issue baru dengan judul singkat, langkah mereproduksi, hasil yang diharapkan,
   dan hasil sebenarnya (sertakan tangkapan layar bila perlu).

## Mengajukan perubahan (Pull Request)

1. **Fork** repositori ini, lalu clone fork Anda.
2. Buat branch dari `main`, misalnya `feat/nama-fitur` atau `fix/nama-bug`.
3. Lakukan perubahan, lalu pastikan aplikasi tetap berjalan
   (`php artisan migrate:fresh --seed` dan `php artisan test`).
4. Commit dengan format *Conventional Commits*, misalnya:
   `feat: tambah filter kategori`, `fix: perbaiki hitungan TCO`, `docs: perbarui README`.
5. Push ke fork Anda dan buka **Pull Request** ke `main` dengan deskripsi perubahan
   dan nomor issue terkait (mis. `Closes #3`).
6. Minimal satu anggota tim akan mereview. Perbaiki bila ada masukan.

## Aturan

- Jangan melakukan commit langsung ke `main`.
- Satu Pull Request sebaiknya membahas satu hal.
- Menambah data alternatif: sertakan sumber resmi (situs proyek) dan jenis lisensinya.
- Kontribusi kode dilisensikan MIT, kontribusi data dilisensikan CC BY-SA 4.0.
- Bersikaplah sopan dan menghargai sesama kontributor.