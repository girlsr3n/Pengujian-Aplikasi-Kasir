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
</head>
<body>
  <div class="container">
    <h2>Riwayat Transaksi</h2>

    <?php if (isset($_GET['msg'])): ?>
      <p style="color: green; font-weight: 600; text-align:center;"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <table>
      <thead>
        <tr><th>ID</th><th>Total (Rp)</th><th>Waktu</th><th>Detail</th></tr>
      </thead>
      <tbody>
        <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td style='text-align:center'>" . (int)$row['id'] . "</td>
                      <td style='text-align:right'>" . number_format($row['total'], 0, ',', '.') . "</td>
                      <td>" . htmlspecialchars($row['tanggal']) . "</td>
                      <td><a href='detail_transaksi.php?id=" . (int)$row['id'] . "'>Lihat</a></td>
                    </tr>";
            }
          } else {
            echo '<tr><td colspan="4" style="text-align:center; font-style: italic;">Belum ada transaksi.</td></tr>';
          }
        ?>
      </tbody>
    </table>
    <br />
    <a href="dashboar.php" class="button">Kembali ke Dashboard</a>
  </div>
</body>
</html>
