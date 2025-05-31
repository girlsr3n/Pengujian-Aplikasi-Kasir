<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: transaksi.php");
    exit;
}

$id = (int)$_GET['id'];

// Ambil data transaksi (pastikan tabel transactions dan kolom tanggal, total)
$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$trans = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$trans) {
    die("Transaksi tidak ditemukan.");
}

// Ambil item transaksi
$stmt = $conn->prepare("
    SELECT ti.qty, ti.harga, p.nama 
    FROM transaction_items ti
    JOIN products p ON ti.produk_id = p.id
    WHERE ti.transaksi_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Transaksi #<?= $id ?> - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h2>Detail Transaksi #<?= $id ?></h2>
    <p><strong>Tanggal:</strong> <?= htmlspecialchars($trans['tanggal']) ?></p>
    <p><strong>Total:</strong> Rp <?= number_format($trans['total'], 0, ',', '.') ?></p>

    <table>
      <thead>
        <tr><th>Produk</th><th>Harga (Rp)</th><th>Qty</th><th>Subtotal (Rp)</th></tr>
      </thead>
      <tbody>
        <?php if ($items->num_rows === 0): ?>
          <tr><td colspan="4" style="text-align:center; font-style: italic;">Tidak ada item.</td></tr>
        <?php else: ?>
          <?php while ($row = $items->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td style="text-align:right"><?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td style="text-align:center"><?= (int)$row['qty'] ?></td>
              <td style="text-align:right"><?= number_format($row['harga'] * $row['qty'], 0, ',', '.') ?></td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <br />
    <a href="transaksi.php" class="button">Kembali ke Riwayat</a>
  </div>
</body>
</html>
