# 📋 Pengujian Black Box Testing pada Aplikasi Kasir

## Model & Tujuan

| Model | Tujuan |
|-------|--------|
| **Equivalence Partitioning** | Uji data valid dan tidak valid dalam kelas ekuivalen |
| **Boundary Value Analysis** | Uji nilai batas (min/max/0) dari input |
| **Robustness Testing** | Uji input ekstrem (panjang, karakter aneh, input kosong) |
| **Decision Table Testing** | Uji kombinasi logika yang menghasilkan keputusan |
| **Behavior Testing (BDD)** | Uji alur penggunaan berdasarkan interaksi pengguna |
| **Sample Testing** | Uji beberapa nilai representatif dalam satu kelas input |

---

## ✅ Equivalence Partitioning

| No | Fitur | Input / Skenario | Expected Output | Status | Screenshot |
|----|-------|------------------|------------------|--------|------------|
| 1 | Login | Username dan password valid | Berhasil login ke dashboard | ✅ | <img src="screenshot blackbox testing/login-berhasil.png" width="200"> |
| 2 | Login | Username salah, password benar | Gagal login, muncul pesan error | ✅ | <img src="screenshot blackbox testing/login-gagal.png" width="200"> |
| 3 | Input Barang | Nama: “Kopi”, Harga: 5000, Stok: 10 | Barang berhasil disimpan | ✅ | <img src="screenshot blackbox testing/input-barang-valid.png" width="200"> |
| 4 | Transaksi | Uang bayar = total belanja | Transaksi berhasil, tidak ada kembalian | ✅ | <img src="screenshot blackbox testing/transaksi-pas.png" width="200"> |
| 5 | Transaksi | Uang bayar > total belanja | Kembalian ditampilkan | ✅ | <img src="screenshot blackbox testing/transaksi-kembalian.png" width="200"> |
| 6 | Tambah Kategori | Nama kategori valid: “Minuman” | Kategori berhasil disimpan | ✅ | <img src="screenshot blackbox testing/kategori-valid.png" width="200"> |
| 7 | Tambah Kategori | Nama kategori kosong | Validasi gagal: “Nama kategori tidak boleh kosong” | ✅ | <img src="screenshot blackbox testing/kategori-kosong.png" width="200"> |
| 8 | Diskon | Nominal diskon valid (misal 10%) | Diskon diterapkan ke total belanja | ✅ | <img src="screenshot blackbox testing/diskon-valid.png" width="200"> |
| 9 | Diskon | Nominal diskon > 100 | Validasi gagal: “Diskon akan otomatis maksimal 100%” | ✅ | <img src="screenshot blackbox testing/diskon-berlebihan.png" width="200"> |

---

## ✅ Boundary Value Analysis

| No | Fitur | Input / Skenario | Expected Output | Status | Screenshot |
|----|-------|------------------|------------------|--------|------------|
| 1 | Input Barang | Harga = -1 | Validasi gagal: "Harga tidak boleh negatif" | ✅ | <img src="screenshot blackbox testing/harga-negatif.png" width="200"> |
| 2 | Tambah Barang | Jumlah = 1 (batas bawah) | Barang ditambahkan ke keranjang | ✅ | <img src="screenshot blackbox testing/jumlah-minimum.png" width="200"> |
| 3 | Tambah Barang | Jumlah = stok maksimum | Barang ditambahkan | ✅ | <img src="screenshot blackbox testing/jumlah-maksimum.png" width="200"> |
| 4 | Transaksi | Uang bayar < total (misal 9500 dari 10000) | Gagal transaksi: "Uang kurang" | ✅ | <img src="screenshot blackbox testing/transaksi-uang-kurang.png" width="200"> |
| 5 | Diskon | Diskon = 0% | Tidak ada potongan, total tetap | ✅ | <img src="screenshot blackbox testing/diskon-nol.png" width="200"> |
| 6 | Diskon | Diskon = 100% | Total belanja menjadi 0 | ✅ | <img src="screenshot blackbox testing/diskon-seratus.png" width="200"> |
| 7 | Diskon | Diskon = -1% | Validasi gagal: “Diskon tidak boleh negatif” | ✅ | <img src="screenshot blackbox testing/diskon-negatif.png" width="200"> |

---

## ✅ Robustness Testing

| No | Fitur | Input / Skenario | Expected Output | Status | Screenshot |
|----|-------|------------------|------------------|--------|------------|
| 1 | Login | Username kosong | Validasi: "Username tidak boleh kosong" | ✅ | <img src="screenshot blackbox testing/login-kosong.png" width="200"> |
| 2 | Input Barang | Nama barang > 255 karakter | Validasi: "Panjang nama melebihi batas" | ✅ | <img src="screenshot blackbox testing/nama-barang-panjang.png" width="200"> |
| 3 | Input Barang | Harga = “abc” (huruf) | Validasi gagal: "Masukkan angka valid" | ✅ | <img src="screenshot blackbox testing/harga-nonangka.png" width="200"> |
| 4 | Transaksi | Uang bayar = “!@#” | Validasi gagal: input tidak sesuai format | ✅ | <img src="screenshot blackbox testing/input-simbol.png" width="200"> |
| 5 | Diskon | Diskon = “abc” (huruf) | Validasi gagal: “Diskon harus berupa angka” | ✅ | <img src="screenshot blackbox testing/diskon-nonangka.png" width="200"> |

---

## ✅ Decision Table Testing

| No | Fitur | Input / Skenario | Expected Output | Status | Screenshot |
|----|-------|------------------|------------------|--------|------------|
| 1 | Tambah Keranjang | Barang tersedia + stok cukup | Barang berhasil ditambahkan | ✅ | <img src="screenshot blackbox testing/tambah-keranjang.png" width="200"> |
| 2 | Diskon | Total belanja > 0 + diskon valid | Total belanja dikurangi diskon | ✅ | <img src="screenshot blackbox testing/diskon-berhasil.png" width="200"> |
| 3 | Diskon | Total belanja ≥ 3000 + diskon valid | Diskon diterapkan, subtotal sesuai | ✅ | <img src="screenshot blackbox testing/diskon-subtotal.png" width="200"> |
| 4 | Diskon | Diskon tidak diinput | Transaksi diproses tanpa potongan | ✅ | <img src="screenshot blackbox testing/diskon-tidak-ada.png" width="200"> |

---

## ✅ Behavior Driven Testing (BDD)

| No | Fitur | Input / Skenario | Expected Output | Status | Screenshot |
|----|-------|------------------|------------------|--------|------------|
| 1 | Transaksi | User memilih barang → input jumlah → klik bayar | Transaksi berhasil dan total ditampilkan | ✅ | <img src="screenshot blackbox testing/transaksi-sukses.png" width="200"> |
| 2 | Cetak Struk | Setelah klik bayar → klik cetak struk | Struk transaksi muncul dalam bentuk PDF/cetak | ✅ | <img src="screenshot blackbox testing/cetak-struk.png" width="200"> |
| 3 | Logout | Klik tombol logout | Sistem logout dan kembali ke halaman login | ✅ | <img src="screenshot blackbox testing/logout.png" width="200"> |

---

## ✅ Sample Testing

| No | Fitur | Input / Skenario | Expected Output | Status | Screenshot |
|----|-------|------------------|------------------|--------|------------|
| 1 | Input Barang | Nama = “Kopi”, “123kopi”, “kopi123” | Semua diterima sebagai input valid | ✅ | <img src="screenshot blackbox testing/nama-beragam.png" width="200"> |
| 2 | Input Barang | Nama = “kopi”, Harga = 0, Stok = 5 | Valid: Harga boleh nol | ✅ | <img src="screenshot blackbox testing/harga-nol.png" width="200"> |
| 3 | Input Barang | Nama = “teh”, Harga = 5000, Stok = 0 | Valid: Stok boleh nol | ✅ | <img src="screenshot blackbox testing/stok-nol.png" width="200"> |

## 🧾 Kesimpulan

Berdasarkan pengujian menggunakan berbagai metode dalam **Black Box Testing**, berikut kesimpulan yang dapat diambil:

### ✅ Fitur Berjalan Sesuai Harapan
Seluruh fitur utama aplikasi seperti **login**, **input barang**, **transaksi**, **diskon**, dan **cetak struk** berhasil diuji dan berjalan dengan baik, sesuai dengan skenario input yang diberikan.

### ✅ Validasi Input Bekerja dengan Baik
Pengujian menggunakan **Equivalence Partitioning**, **Boundary Value Analysis**, dan **Robustness Testing** menunjukkan bahwa aplikasi mampu menangani **input valid maupun tidak valid** secara tepat dengan pesan kesalahan yang jelas.

### ✅ Logika Keputusan Diuji Secara Lengkap
Melalui **Decision Table Testing**, semua kombinasi kondisi yang mungkin terjadi dalam proses diskon dan penambahan keranjang telah diuji, dan aplikasi menunjukkan **perilaku yang benar**.

### ✅ Skenario Pengguna Berhasil Dijalankan
**Behavior Driven Testing (BDD)** membuktikan bahwa **alur penggunaan dari perspektif pengguna akhir**, mulai dari memilih barang hingga logout, berjalan **mulus tanpa kendala**.

### ✅ Input Umum Terverifikasi dengan Baik
Pengujian **Sample Testing** memastikan bahwa **data-data umum dan kombinasi variasi nama, harga, serta stok** diterima dengan baik oleh sistem.

---

### 📌 Ringkasan
Aplikasi kasir ini telah **berhasil melewati semua pengujian fungsional** dengan metode Black Box Testing. Sistem terbukti **stabil terhadap berbagai jenis input** dan dapat **diandalkan untuk operasional pengguna sehari-hari**.

