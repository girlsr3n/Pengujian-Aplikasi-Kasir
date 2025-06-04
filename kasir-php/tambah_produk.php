<?php
include 'db.php';

$error = "";
$nama = "";
$harga = "";
$stok = 0;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data jika mode edit (GET request dengan id)
if ($id > 0 && $_SERVER["REQUEST_METHOD"] == "GET") {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $nama = $row['nama'];
        $harga = $row['harga'];
        $stok = $row['stok'];
    } else {
        $error = "Produk tidak ditemukan.";
    }
    $stmt->close();
}

// Tangani form submit (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama = trim($_POST['nama']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];

    if (empty($nama)) {
        $error = "Nama produk wajib diisi.";
    } elseif ($harga <= 0) {
        $error = "Harga harus lebih dari 0.";
    } elseif ($stok < 0) {
        $error = "Stok tidak boleh negatif.";
    } else {
        if ($id > 0) {
            // UPDATE produk
            $stmt = $conn->prepare("UPDATE products SET nama = ?, harga = ?, stok = ? WHERE id = ?");
            $stmt->bind_param("siii", $nama, $harga, $stok, $id);
            if ($stmt->execute()) {
                header("Location: daftar_produk.php?msg=Produk berhasil diperbarui");
                exit;
            } else {
                $error = "Gagal memperbarui produk: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // INSERT produk baru
            $stmt = $conn->prepare("INSERT INTO products (nama, harga, stok) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $nama, $harga, $stok);
            if ($stmt->execute()) {
                header("Location: daftar_produk.php?msg=Produk berhasil ditambahkan");
                exit;
            } else {
                $error = "Gagal menambahkan produk: " . $stmt->error;
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
    <title><?= $id > 0 ? 'Edit Produk' : 'Tambah Produk' ?> - Kasir PHP</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .container { max-width: 500px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; font-size: 1rem; border: 1px solid #aaa; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 16px; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; }
        button:hover { background-color: #218838; }
        .error { color: red; font-weight: 600; text-align: center; margin-bottom: 15px; }
        a.button { display: inline-block; margin-top: 15px; text-decoration: none; background: #007bff; color: white; padding: 8px 14px; border-radius: 4px; }
        a.button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?= $id > 0 ? 'Edit Produk' : 'Tambah Produk' ?></h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="form-group">
                <label for="nama">Nama Produk:</label>
                <input type="text" id="nama" name="nama" required value="<?= htmlspecialchars($nama) ?>" />
            </div>
            <div class="form-group">
                <label for="harga">Harga (Rp):</label>
                <input type="number" id="harga" name="harga" min="1" required value="<?= (int)$harga ?>" />
            </div>
            <div class="form-group">
                <label for="stok">Stok:</label>
                <input type="number" id="stok" name="stok" min="0" required value="<?= (int)$stok ?>" />
            </div>
            <button type="submit">Simpan</button>
        </form>

        <a href="index.php" class="button">Kembali ke Daftar Produk</a>
    </div>
</body>
</html>
