# 🧾 Kasir PHP Project with Authentication & Software Testing

## 📌 Deskripsi Proyek
Proyek ini adalah aplikasi web kasir sederhana berbasis **PHP Native** yang mencakup fitur-fitur autentikasi pengguna (**Login dan Logout**), manajemen produk, serta pencatatan transaksi. Selain itu, proyek ini telah melewati proses pengujian perangkat lunak dengan pendekatan **Black Box**, **White Box**, dan **Grey Box Testing** untuk memastikan fungsionalitas dan keamanan sistem berjalan dengan baik.

---

## 👥 Partisipan Proyek

| Nama                   | Peran                  |
|------------------------|------------------------|
| Siti Rahmah            | 👨‍💻 Developer          |
| Rifa Vida Zahrani      | 🧪 Black Box Tester     |
| Khairunnisa Dwi W.      | ⚙️ White Box Tester     |
| Ratna Santika          | 🧩 Grey Box Tester      |

---

## 📦 Fitur Utama

- ✅ Login Admin  
- 📋 Manajemen Barang  
- 💵 Proses Transaksi Penjualan  
- 🧾 Riwayat Transaksi  
- 📈 Laporan Penjualan Harian  
- 🧑‍💼 Manajemen Pengguna (opsional)

---

## 🛠️ Teknologi yang Digunakan

- PHP (Native)  
- MySQL  
- HTML, CSS, JavaScript  
- Bootstrap (untuk tampilan antarmuka)

---

## ⚙️ Cara Menjalankan Proyek

1. **Clone atau Download Proyek**
   - Ekstrak folder `kasir-php` jika file dalam bentuk zip.

2. **Pindahkan ke folder server lokal:**
   - Jika kamu menggunakan XAMPP:
     - Letakkan folder hasil unzip ke dalam `htdocs`.

3. **Import database:**
   - Buka `phpMyAdmin`
   - Buat database baru dengan nama: `kasir`
   - Import file `kasir.sql` yang ada di dalam folder `database/`

4. **Konfigurasi koneksi database:**
   - Edit file `config/koneksi.php` (atau `db.php`) sesuai dengan konfigurasi server lokal:
     ```php
     $host = "localhost";
     $user = "root";
     $pass = "";
     $db   = "kasir";
     ```

5. **Jalankan aplikasi:**
   - Buka browser dan akses:
     ```
     http://localhost/kasir-php/create_admin.php
     ```
     untuk menambahkan data admin awal ke tabel `users`.

   - Selanjutnya, buka:
     ```
     http://localhost/kasir-php/login.php
     ```
     untuk masuk ke sistem.

6. **Akun Login Awal:**
   - **Username:** `admin`  
   - **Password:** `admin123`

---

## 📊 Hasil Uji & Dokumentasi

- Hasil pengujian tercatat secara manual oleh tim tester.
- Laporan hasil uji tersedia dalam dokumen terpisah (Word/PDF/Excel), meliputi:
  - Studi kasus pengujian login, transaksi, produk.
  - Simulasi input tidak valid dan manipulasi data.
  - Pemeriksaan struktur kode (flowchart, data flow, path testing).

---

## 📃 Lisensi

Proyek ini bebas digunakan untuk keperluan pembelajaran atau pengembangan, dengan tetap menyertakan atribusi kepada pembuat asli.
