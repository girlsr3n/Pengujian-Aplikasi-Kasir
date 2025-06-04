# 🔲 Black Box Testing – Aplikasi Kasir

**Black Box Testing** adalah metode pengujian perangkat lunak yang berfokus pada pengujian fungsionalitas sistem dari sisi pengguna, tanpa melihat kode program atau logika internal. Berikut adalah hasil pengujian pada aplikasi kasir yang mencakup fitur Login, Tambah Produk, dan Transaksi.

---

## 1. 🔐 LOGIN

| Test Case ID | Skenario                | Input Username | Input Password | Output yang Diharapkan                      | Model          |
|--------------|-------------------------|----------------|----------------|---------------------------------------------|----------------|
| TC-1         | Login berhasil          | admin          | admin123       | Masuk ke dashboard                          | Equivalence    |
| TC-2         | Password kosong         | admin          | (kosong)       | Field: password wajib diisi                 | Boundary       |
| TC-3         | Username kosong         | (kosong)       | admin123       | Field: username wajib diisi                 | Boundary       |
| TC-4         | Username & password salah | admin123     | admin          | Error: Username & Password salah            | Equivalence    |

---

## 2. 📦 TAMBAH PRODUK

| Test Case ID | Skenario                     | Input                            | Output yang Diharapkan                              | Model       |
|--------------|------------------------------|----------------------------------|------------------------------------------------------|-------------|
| TC-1         | Tambah produk valid          | Nama, harga, stok                | Produk berhasil ditambahkan                          | Equivalence |
| TC-2         | Nama produk kosong           | -, harga, stok                   | Error: Nama produk wajib diisi                       | Equivalence |
| TC-3         | Harga dikosongkan            | Nama, -, stok                    | Error: Harga wajib diisi                             | Equivalence |
| TC-4         | Stok negatif                 | Nama, harga, stok = -1           | Error: Nilai harus ≥ 0                               | BVA         |
| TC-5         | Harga dan stok tinggi        | Nama, harga = 100000, stok = 9999| Produk berhasil ditambahkan                          | Equivalence |

---

## 3. 💳 TRANSAKSI

| Test Case ID | Skenario                   | Input                                 | Output yang Diharapkan                          | Model          |
|--------------|----------------------------|----------------------------------------|--------------------------------------------------|----------------|
| TC-1         | Transaksi berhasil         | Produk tersedia, stok cukup, bayar cukup | Transaksi berhasil, riwayat tercatat         | Decision Table |
| TC-2         | Jumlah melebihi stok       | Jumlah beli > stok                     | Error: Stok tidak mencukupi                      | Boundary       |
| TC-3         | Field jumlah kosong        | Jumlah = 0                             | Error: Tidak ada item valid yang disimpan        | Boundary       |
| TC-4         | Input jumlah negatif       | -5                                     | Error: Jumlah tidak valid                        | Equivalence    |

---

## ✅ Kesimpulan

- Pengujian dilakukan berdasarkan skenario nyata dari sisi pengguna (end-user).
- Teknik pengujian yang digunakan meliputi:
  - **Equivalence Partitioning**
  - **Boundary Value Analysis (BVA)**
  - **Decision Table Testing**
- Sistem berhasil menampilkan output sesuai spesifikasi dan memberikan pesan error yang tepat pada kondisi yang tidak valid.
