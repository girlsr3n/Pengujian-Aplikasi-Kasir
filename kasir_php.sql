-- Gunakan database yang benar
USE kasir;

-- Tabel produk
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT DEFAULT 0
);

-- Tabel pengguna
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir') DEFAULT 'kasir'
);

-- Tabel transaksi utama
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total INT NOT NULL
);

-- Tabel detail transaksi
CREATE TABLE IF NOT EXISTS transaction_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL,
    harga INT NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES products(id) ON DELETE CASCADE
);
