# 🧪 Laporan Perbaikan Gray Box Testing - Aplikasi Kasir

---

## 1. Orthogonal Testing

### Fitur Login

| No | Skenario Uji            | Input Username | Input Password | Langkah Pengujian                       | Hasil yang Diharapkan      | Status |
|----|-------------------------|----------------|----------------|-----------------------------------------|-----------------------------|--------|
| 1  | Login                   | admin          | admin123       | Pengujian isi form login dan klik login | Dialihkan ke Dashboard     | valid  |
| 2  | Username dan Password   | admin          | ratna25803     | Pengujian isi form login dan klik login | Dialihkan ke Dashboard     | valid  |

---

## 2. Regression Testing

> Regression Testing dilakukan setelah penambahan fitur berikut:
- Fitur **Diskon**
- Fitur **Kategori**
- Fitur **Metode Pembayaran**
- Fitur **Cetak Struk**

![Struk Transaksi](Strukbelanja.2.jpeg)
![Tampilan Transaksi](Transaksibaru.2.jpeg)

### 📌 Fitur Diskon
- Diskon ditampilkan dalam bentuk persentase di tabel transaksi.
- Diskon diambil dari data produk dan dihitung otomatis saat perhitungan subtotal.
- Membantu strategi promosi dan pengelolaan harga produk.


---

### 📌 Fitur Kategori
- Fitur kategori digunakan untuk mengelompokkan jenis produk seperti ATK, Minuman, Makanan, dsb.
- Ditampilkan dalam form tambah/edit produk dan tabel transaksi.
- Berguna untuk pengelompokan dan pencarian data.


---

### 📌 Fitur Metode Pembayaran
- Pengguna bisa memilih metode pembayaran saat transaksi, seperti:
  - Tunai
  - Transfer Bank
  - QRIS
  - E-Wallet
- Metode ini membantu pencatatan dan laporan penjualan yang lebih akurat.


---

### 📌 Fitur Cetak Struk
- Setelah transaksi selesai, sistem menampilkan struk berisi:
  - Nomor Transaksi
  - Tanggal dan Waktu
  - Item yang dibeli
  - Jumlah, Harga, dan Subtotal
  - Total Belanja dan Metode Pembayaran
- Struk dapat dicetak atau disimpan sebagai bukti pembayaran.



---

## 3. Pattern Testing

### 3.1 Menguji Fungsional Dasar
- Aplikasi kasir dapat dibuka dan ditampilkan dengan baik.
- Penambahan produk baru dengan data lengkap ditampilkan dengan benar.
- Transaksi berhasil dilakukan dengan perhitungan total dan diskon yang sesuai.
- Edit dan hapus produk bekerja dengan benar.
- Struk transaksi menampilkan data lengkap dan sesuai.

### 3.2 Menguji Batasan dan Skenario Tidak Terduga
- Input nama produk yang sangat panjang atau kosong.
- Harga diisi dengan teks atau karakter tidak valid.
- Diskon melebihi 100% atau diisi dalam format salah seperti "sepuluh persen".
- Nilai stok negatif atau kosong.
- Duplikasi produk dengan nama sama.
- Transaksi dilakukan tanpa memilih produk apa pun.

### 3.3 Menguji Performa dan Stabilitas
- Tambahkan ratusan data produk, sistem tetap stabil.
- Pencarian produk berjalan cepat dan akurat.
- Transaksi dilakukan berulang kali tanpa crash.
- Cetak struk beberapa kali tidak menyebabkan error.
- Aplikasi kompatibel di berbagai browser dan perangkat.

### 3.4 Menguji Kegunaan dan Pengalaman Pengguna
- Antarmuka pengguna mudah dimengerti.
- Proses input data dan transaksi berjalan lancar.
- Navigasi antar halaman jelas dan terstruktur.
- Tampilan harga, diskon, dan total belanja mudah dibaca.
- Uji coba oleh pengguna awam menunjukkan pengalaman pengguna yang baik.

---

## 📌 Penutup
Pengujian dilakukan untuk memastikan bahwa semua fitur berjalan dengan baik, stabil, dan mudah digunakan oleh pengguna. Fitur-fitur tambahan seperti diskon, kategori, metode pembayaran, dan cetak struk memberikan nilai tambah dan mempermudah proses transaksi pada aplikasi kasir.


