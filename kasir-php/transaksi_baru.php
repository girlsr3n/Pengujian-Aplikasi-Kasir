<?php
include 'db.php';

// Hanya ambil produk dengan stok > 0
$result = $conn->query("SELECT * FROM products WHERE stok > 0 ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Transaksi Baru - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>Transaksi Baru</h2>

    <form method="post" action="simpan_transaksi.php">
      <table>
        <thead>
          <tr><th>Produk</th><th>Harga (Rp)</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td style="text-align:right"><?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td>
                <input type="hidden" name="produk_id[]" value="<?= $row['id'] ?>" />
                <input type="number" name="qty[]" value="0" min="0" max="<?= $row['stok'] ?>" />
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <br />
      <button type="submit">Simpan Transaksi</button>
    </form>
    <br />
    <a href="dashboar.php" class="button">Kembali ke Dashboard</a>
  </div>
</body>
</html>
