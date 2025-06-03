# Kasir-PHP

**Kasir-PHP** adalah aplikasi kasir sederhana berbasis web yang dibangun menggunakan bahasa pemrograman PHP dan MySQL. Aplikasi ini memungkinkan pengguna untuk mengelola transaksi penjualan, data barang, dan laporan keuangan harian.

## 📦 Fitur Utama

- ✅ Login Admin
- 📋 Manajemen Barang
- 💵 Proses Transaksi Penjualan
- 🧾 Riwayat Transaksi
- 📈 Laporan Penjualan Harian
- 🧑‍💼 Manajemen Pengguna (opsional)

## 🛠️ Teknologi yang Digunakan

- PHP (Native)
- MySQL
- HTML, CSS, JavaScript
- Bootstrap (untuk tampilan antarmuka)


2. Pindahkan ke folder server lokal:
Jika kamu menggunakan XAMPP:
- Letakkan folder hasil unzip ke dalam htdocs

3. Import database:
- Buka phpMyAdmin
- Buat database baru, kasir
- Import file kasir.sql yang ada di dalam folder database/

4. Konfigurasi koneksi database:
Edit file config/koneksi.php sesuai dengan konfigurasi server lokal, db.php
- Salin
- Edit
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kasir";

5. Jalankan aplikasi:
Buka browser dan akses:
http://localhost/kasir-php/create_admin.php
untuk menambahkan di database tabel users, user = admin dan password = admin123, selanjutnya http://localhost/kasir-php/login.php

6. Akun Login
Username admin
Password admin123
