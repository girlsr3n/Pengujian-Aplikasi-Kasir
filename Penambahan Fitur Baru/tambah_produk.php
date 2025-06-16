<?php
session_start();
include 'db.php';

$error = '';
$success = '';
$nama = '';
$harga = '';
$stok = '';
$kategori = '';
$diskon = '';

$isEdit = false; // EDIT: flag untuk mode edit
$id = $_GET['id'] ?? null;

if ($id !== null) {
    $id = (int)$id;
    if ($id > 0) {
        // EDIT: ambil data produk jika mode edit
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $produk = $result->fetch_assoc();
            $nama = $produk['nama'];
            $harga = $produk['harga'];
            $stok = $produk['stok'];
            $kategori = $produk['kategori'];
            $diskon = $produk['diskon'];
            $isEdit = true;
        } else {
            $error = "Produk dengan ID $id tidak ditemukan.";
        }
        $stmt->close();
    } else {
        $error = "ID produk tidak valid.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // EDIT: ambil id dari hidden input jika ada (untuk update)
    $idPost = $_POST['id'] ?? null;

    $nama = trim($_POST['nama'] ?? '');
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';
    $kategori = trim($_POST['kategori'] ?? '');
    $diskon = $_POST['diskon'] ?? '';

    if (strlen($nama) < 3) {
        $error = 'Nama produk harus minimal 3 karakter.';
    } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $harga)) {
        $error = 'Harga harus berupa angka positif dengan maksimal 2 desimal.';
    } elseif (!preg_match('/^\d+$/', $stok)) {
        $error = 'Stok harus berupa bilangan bulat positif.';
    } elseif (strlen($kategori) < 3) {
        $error = 'Kategori harus minimal 3 karakter.';
    } elseif (!preg_match('/^\d+$/', $diskon) || (int)$diskon < 0 || (int)$diskon > 100) {
        $error = 'Diskon harus berupa angka antara 0 hingga 100.';
    }

    if (!$error) {
        $harga = (float)$harga;
        $stok = (int)$stok;
        $diskon = (int)$diskon;

        if ($idPost !== null && (int)$idPost > 0) {
            // EDIT: update data produk jika mode edit
            $stmt = $conn->prepare("UPDATE products SET nama=?, harga=?, stok=?, kategori=?, diskon=? WHERE id=?");
            $stmt->bind_param('sdisii', $nama, $harga, $stok, $kategori, $diskon, $idPost);
            if ($stmt->execute()) {
                $success = "Produk berhasil diupdate.";
                $isEdit = true;
                $id = (int)$idPost;
            } else {
                $error = "Gagal mengupdate produk: " . $conn->error;
            }
            $stmt->close();
        } else {
            // INSERT produk baru
            $stmt = $conn->prepare("INSERT INTO products (nama, harga, stok, kategori, diskon) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sdisi', $nama, $harga, $stok, $kategori, $diskon);
            if ($stmt->execute()) {
                $success = "Produk berhasil ditambahkan.";
                $nama = $harga = $stok = $kategori = $diskon = '';
                $isEdit = false;
            } else {
                $error = "Gagal menyimpan produk: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?> - Kasir PHP</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4ff;
      margin: 0; padding: 20px;
    }
    .container {
      max-width: 480px;
      margin: 40px auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      padding: 30px;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 24px;
    }
    label {
      display: block;
      margin: 12px 0 6px;
      font-weight: 600;
      color: #1e3a8a;
    }
    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      box-sizing: border-box;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 14px;
      background-color: #4361ee;
      color: white;
      font-weight: 600;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #3a54d1;
    }
    .error {
      background: #fdecea;
      color: #d32f2f;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 16px;
      text-align: center;
    }
    .success {
      background: #e6ffed;
      color: #2f6627;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 16px;
      text-align: center;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #4361ee;
      font-weight: 600;
    }
  </style>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const hargaInput = document.getElementById('harga');
      const stokInput = document.getElementById('stok');
      const diskonInput = document.getElementById('diskon');

      hargaInput.addEventListener('input', () => {
        hargaInput.value = hargaInput.value.replace(/[^0-9.]/g, '');
        const parts = hargaInput.value.split('.');
        if (parts.length > 2) {
          hargaInput.value = parts[0] + '.' + parts[1];
        }
        if (parts[1]?.length > 2) {
          hargaInput.value = parts[0] + '.' + parts[1].substring(0, 2);
        }
      });

      stokInput.addEventListener('input', () => {
        stokInput.value = stokInput.value.replace(/[^0-9]/g, '');
      });

      diskonInput.addEventListener('input', () => {
        diskonInput.value = diskonInput.value.replace(/[^0-9]/g, '');
        if (diskonInput.value > 100) diskonInput.value = 100;
      });
    });
  </script>
</head>
<body>
  <div class="container">
    <h2><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>" />
      <?php endif; ?>

      <label for="nama">Nama Produk</label>
      <input type="text" id="nama" name="nama" required minlength="3"
             placeholder="Masukkan nama produk" value="<?= htmlspecialchars($nama) ?>" />

      <label for="harga">Harga (Rp)</label>
      <input type="number" id="harga" name="harga" required min="0" step="0.01"
             placeholder="Masukkan harga produk" value="<?= htmlspecialchars($harga) ?>" />

      <label for="stok">Stok</label>
      <input type="number" id="stok" name="stok" required min="0" step="1"
             placeholder="Masukkan stok produk" value="<?= htmlspecialchars($stok) ?>" />

      <label for="kategori">Kategori</label>
      <input type="text" id="kategori" name="kategori" required minlength="3"
             placeholder="Masukkan kategori produk" value="<?= htmlspecialchars($kategori) ?>" />

      <label for="diskon">Diskon (%)</label>
      <input type="number" id="diskon" name="diskon" required min="0" max="100" step="1"
             placeholder="Masukkan diskon (0-100%)" value="<?= htmlspecialchars($diskon) ?>" />

      <button type="submit"><?= $isEdit ? 'Update Produk' : 'Simpan Produk' ?></button>
    </form>

    <a href="index.php">← Kembali ke Daftar Produk</a>
  </div>
</body>
</html>
