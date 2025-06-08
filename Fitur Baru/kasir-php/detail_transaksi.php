<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: transaksi.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$trans = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$trans) {
    die("Transaksi tidak ditemukan.");
}

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

// Ambil nilai total & diskon
$total = (int)$trans['total'];
$diskon = isset($trans['diskon']) ? (int)$trans['diskon'] : 0;
$totalBayar = $total - $diskon;

// Ambil metode pembayaran
$metodePembayaran = isset($trans['metode_pembayaran']) ? ucfirst(strtolower($trans['metode_pembayaran'])) : 'Tunai';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Struk Transaksi #<?= $id ?> - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: monospace;
      background-color: #f9f9f9;
    }

    .container {
      max-width: 400px;
      margin: 40px auto;
      background: white;
      padding: 20px;
      border: 1px dashed #ccc;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      font-size: 1.2rem;
      margin-bottom: 10px;
    }

    p {
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 5px;
      border-bottom: 1px dotted #ccc;
      font-size: 14px;
    }

    th {
      text-align: left;
      font-weight: bold;
    }

    .total, .diskon {
      text-align: right;
      font-size: 15px;
    }

    .total strong {
      font-size: 16px;
    }

    .center {
      text-align: center;
    }

    .footer-info {
      margin-top: 10px;
      font-size: 14px;
      text-align: right;
    }

    a.button {
      display: block;
      margin-top: 20px;
      background: #007bff;
      color: white;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      text-decoration: none;
    }

    a.button:hover {
      background: #0056b3;
    }

    .line {
      border-top: 1px dashed #aaa;
      margin: 10px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Struk Transaksi #<?= $id ?></h2>
    <div class="line"></div>
    <p><strong>Tanggal:</strong> <?= htmlspecialchars($trans['tanggal']) ?></p>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th style="text-align:right;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($items->num_rows === 0): ?>
          <tr><td colspan="3" class="center"><em>Tidak ada item</em></td></tr>
        <?php else: ?>
          <?php while ($row = $items->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nama']) ?><br><small>@ Rp <?= number_format($row['harga'], 0, ',', '.') ?></small></td>
              <td style="text-align:center;"><?= (int)$row['qty'] ?></td>
              <td style="text-align:right;"><?= number_format($row['harga'] * $row['qty'], 0, ',', '.') ?></td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="line"></div>
    <p class="total">Subtotal: Rp <?= number_format($total, 0, ',', '.') ?></p>
    <?php if ($diskon > 0): ?>
      <p class="diskon">Diskon: -Rp <?= number_format($diskon, 0, ',', '.') ?></p>
    <?php endif; ?>
    <p class="total"><strong>Total Bayar: Rp <?= number_format($totalBayar, 0, ',', '.') ?></strong></p>

    <div class="footer-info">
      <p>Metode: <?= $metodePembayaran ?></p>
      <?php if ($diskon > 0): ?>
        <p>Keterangan: Diskon diterapkan</p>
      <?php endif; ?>
    </div>

    <a href="transaksi.php" class="button">Kembali ke Riwayat</a>
  </div>
</body>
</html>
