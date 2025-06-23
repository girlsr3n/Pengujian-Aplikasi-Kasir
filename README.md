# 🧾 Pengujian Aplikasi Kasir - PHP Native

## 📌 Deskripsi Proyek
Aplikasi ini merupakan sistem kasir berbasis **PHP Native** yang sederhana namun fungsional. Aplikasi mendukung proses transaksi, manajemen produk, serta fitur login dan logout untuk keamanan pengguna. Pengujian dilakukan dengan pendekatan **Black Box**, **White Box**, dan **Grey Box Testing**.

---

## 👥 Tim Proyek

| Nama                  | Peran                  |
|-----------------------|------------------------|
| Siti Rahmah           | 👨‍💻 Developer           |
| Rifa Vida Zahrani     | 🧪 Black Box Tester      |
| Khairunnisa Dwi W.     | ⚙️ White Box Tester      |
| Ratna Santika         | 🧩 Grey Box Tester       |

---

## 🚀 Fitur Utama

- ✅ Login & Logout pengguna
- 📦 Manajemen produk: tambah, edit, hapus
- 🛒 Transaksi: input produk dan kuantitas
- 📊 Riwayat & detail transaksi
- 🖨️ Cetak struk belanja
- 🔐 Keamanan dasar (prepared statement)

---

## 🧪 Pengujian

### 🔲 Black Box Testing
- Validasi login berhasil/gagal
- Tambah produk (input valid/tidak valid)
- Simulasi transaksi dengan dan tanpa produk
- Uji navigasi

### ⚪ White Box Testing
- Uji logika PHP: `if`, `loop`, validasi input
- Penelusuran variabel (data flow)
- Coverage jalur eksekusi

### 🔳 Grey Box Testing
- Uji integrasi frontend ↔ backend
- Cek keamanan query (injeksi, input kosong)
- Uji kombinasi fungsionalitas

---

## 🛠️ Teknologi

- PHP Native (tanpa framework)
- MySQL (phpMyAdmin)
- HTML, CSS, JavaScript
- XAMPP, Xdebug, DevTools

---

## 📁 Struktur Direktori

```
kasir-php/
├── create_admin.php
├── dashboar.php
├── db.php
├── detail_transaksi.php
├── hapus_produk.php
├── index.php
├── kasir_php.sql
├── login.php
├── logout.php
├── proses_transaksi.php
├── script.js
├── simpan_transaksi.php
├── style.css
├── tambah_produk.php
├── transaksi.php
└── transaksi_baru.php

```

## ⚙️ Cara Menjalankan

1. Ekstrak folder `kasir-php` ke `htdocs` (XAMPP).
2. Import `kasir_php.sql` ke database lewat phpMyAdmin.
3. Jalankan via browser:
---
   **http://localhost/kasir-php/login.php**
---

## 📑 Dokumentasi Pengujian

File dokumentasi pengujian tersedia di repo GitHub:
- `Black Box Testing.md`
- `White Box Testing.md`
- `Grey Box Testing.md`

Berisi studi kasus, hasil uji, dan analisis kesalahan.

---

## 📃 Lisensi

Bebas digunakan untuk pembelajaran. Sertakan atribusi kepada pengembang asli.
