🧾 Kasir PHP Project with Authentication & Software Testing

📌 Deskripsi Proyek
Proyek ini adalah aplikasi web kasir sederhana berbasis PHP Native yang mencakup fitur-fitur autentikasi pengguna (Login dan Logout), manajemen produk, serta pencatatan transaksi. Selain itu, proyek ini telah melewati proses pengujian perangkat lunak dengan pendekatan Black Box, White Box, dan Grey Box Testing untuk memastikan fungsionalitas dan keamanan sistem berjalan dengan baik.

👥 Partisipan Proyek

Nama	Peran
Siti Rahmah	        👨‍💻  Developer
Rifa Vida Zahrani 	🧪  Black Box Tester
Kharunnisa Dwi W. 	⚙️ White Box Tester
Ratna Santika       🧩 Grey Box Tester

🚀 Fitur Utama

✅ Login & Logout: Autentikasi dasar menggunakan username dan password.
📦 Manajemen Produk: Tambah, edit, dan hapus produk.
🛒 Transaksi: Input jumlah barang, simpan transaksi, tampilkan detail transaksi.
📊 Riwayat: Melihat detail transaksi berdasarkan ID.
🔐 Keamanan Dasar: Penggunaan prepared statements untuk mencegah SQL Injection.

🧪 Pengujian Perangkat Lunak
🔲 1. Black Box Testing (Fungsionalitas)
Pengujian dilakukan tanpa melihat kode sumber.
Fokus pada:
Validasi login dengan input valid dan tidak valid.
Proses tambah produk (input kosong, input harga/stok tidak valid).
Simulasi transaksi dengan dan tanpa produk.
Navigasi antar halaman.

⚪ 2. White Box Testing (Struktur Kode)
Pengujian logika dan struktur kode PHP.
Fokus pada:
Jalur percabangan (if/else) di login, tambah produk, dan proses transaksi.
Validasi input form.
Uji struktur pengulangan (loop) saat pemrosesan transaksi.
Penelusuran variabel (data flow) dan coverage script.

🔳 3. Grey Box Testing (Integrasi & Keamanan)
Pengujian dilakukan dengan pemahaman terbatas terhadap struktur internal.
Fokus pada:
Integrasi antara form dan eksekusi query.
Uji simulasi input tidak sah (injeksi SQL manual, input kosong).
Pengujian keamanan query transaksi dan login.

🛠️ Teknologi yang Digunakan
Backend: PHP Native (tanpa framework)
Frontend: HTML, CSS, JavaScript
Database: MySQL
Tools Uji: Manual Testing, Xdebug, Browser DevTools

📂 Struktur Direktori Utama

├── kasir-php/
│ ├── create_admin.php
│ ├── daftar_produk.php
│ ├── dashboar.php
│ ├── db.php
│ ├── detail_transaksi.php
│ ├── hapus_produk.php
│ ├── index.php
│ ├── kasir_php.sql
│ ├── login.php
│ ├── logout.php
│ ├── proses_transaksi.php
│ ├── script.js
│ ├── simpan_transaksi.php
│ ├── style.css
│ ├── tambah_produk.php
│ ├── transaksi.php
│ └── transaksi_baru.php

⚙️ Cara Menjalankan Proyek

Extract folder kasir-php ke direktori htdocs (jika menggunakan XAMPP).
Import file kasir_php.sql ke database MySQL melalui phpMyAdmin.
Jalankan aplikasi di browser melalui:
http://localhost/kasir-php/login.php

📊 Hasil Uji & Dokumentasi
Hasil pengujian tercatat secara manual oleh tim tester.
Tidak tersedia folder tests/ otomatis, namun hasil pengujian dilaporkan melalui dokumen terpisah (Word/PDF/Excel).
Laporan meliputi:
Studi kasus pengujian login, transaksi, produk.
Simulasi input tidak valid dan manipulasi data.
Pemeriksaan struktur kode (flowchart, data flow, path testing).

📃 Lisensi
Proyek ini bebas digunakan untuk keperluan pembelajaran atau pengembangan, dengan tetap menyertakan atribusi kepada pembuat asli.
