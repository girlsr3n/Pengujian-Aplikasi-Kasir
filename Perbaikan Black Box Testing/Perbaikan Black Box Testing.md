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
|----|-------|-------------------|------------------|--------|------------|
| 1 | Login | Username dan password valid | Berhasil login ke dashboard | ✅ | <img src="screenshots/screenshot blackbox testing/login-berhasil.png" width="150"> |
| 2 | Login | Username salah, password benar | Gagal login, muncul pesan error | ✅ | <img src="screenshots/screenshot blackbox testing/login-gagal.png" width="150"> |
| 3 | Input Barang | Nama: “Kopi”, Harga: 5000, Stok: 10 | Barang berhasil disimpan | ✅ | <img src="screenshots/screenshot blackbox testing/input-barang-valid.png" width="150"> |
| 4 | Transaksi | Uang bayar = total belanja | Transaksi berhasil, tidak ada kembalian | ✅ | <img src="screenshots/screenshot blackbox testing/transaksi-pas.png" width="150"> |
| 5 | Transaksi | Uang bayar > total belanja | Kembalian ditampilkan | ✅ | <img src="screenshots/screenshot blackbox testing/transaksi-kembalian.png" width="150"> |
<!-- Lanjutkan sesuai dengan baris lain yang sudah saya berikan di atas -->
