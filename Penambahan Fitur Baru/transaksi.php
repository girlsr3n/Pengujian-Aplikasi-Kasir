<?php
include 'db.php';

$result = $conn->query("SELECT * FROM transactions ORDER BY id DESC");
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Riwayat Transaksi - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #007bff;
      color: white;
    }

    a.button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: #6c757d;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      text-align: center;
    }

    a.button:hover {
      background: #5a6268;
    }

    .success-msg {
      color: green;
      font-weight: bold;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Riwayat Transaksi</h2>

    <?php if (isset($_GET['msg'])): ?>
      <p class="success-msg"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Total (Rp)</th>
          <th>Waktu</th>
          <th>Detail</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td style="text-align:center"><?= (int)$row['id'] ?></td>
              <td style="text-align:right"><?= number_format($row['total'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($row['tanggal']) ?></td>
              <td><a href="detail_transaksi.php?id=<?= (int)$row['id'] ?>">Lihat</a></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" style="text-align:center; font-style:italic;">Belum ada transaksi.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="dashboar.php" class="button">Kembali ke Dashboard</a>
  </div>
</body>
</html>
