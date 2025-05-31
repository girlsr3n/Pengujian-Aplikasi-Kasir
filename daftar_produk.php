<?php
include 'db.php';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Produk - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .action-buttons a {
      margin: 0 5px;
      text-decoration: none;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
    }
    .edit-btn { background-color: #f0ad4e; }
    .delete-btn { background-color: #d9534f; }
    .search-box { margin-bottom: 15px; }
  </style>
  <script>
    function confirmDelete(productName) {
      return confirm('Yakin ingin menghapus produk: ' + productName + '?');
    }

    function searchProducts() {
      let input = document.getElementById('searchInput').value.toLowerCase();
      let rows = document.querySelectorAll("#produkTable tbody tr");
      rows.forEach(row => {
        let name = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
        row.style.display = name.includes(input) ? "" : "none";
      });
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Daftar Produk</h2>

    <?php if ($msg): ?>
      <p style="color: green; font-weight: 600; text-align:center;">
        <?= htmlspecialchars($msg) ?>
      </p>
    <?php endif; ?>

    <a href="tambah_produk.php" class="button">Tambah Produk</a>

    <div class="search-box">
      <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="searchProducts()" />
    </div>

    <table id="produkTable" border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Harga (Rp)</th>
          <th>Stok</th> <!-- Kolom stok -->
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
          if (!$result) {
            echo '<tr><td colspan="5" style="text-align:center; color: red;">Error query: ' . htmlspecialchars($conn->error) . '</td></tr>';
          } else if ($result->num_rows == 0) {
            echo '<tr><td colspan="5" style="text-align:center; font-style: italic;">Belum ada produk.</td></tr>';
          } else {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . number_format($row['harga'], 0, ',', '.') . "</td>";
              echo "<td>" . (int)$row['stok'] . "</td>"; // Tampilkan stok
              echo "<td class='action-buttons'>
                      <a href='tambah_produk.php?id=" . urlencode($row['id']) . "' class='edit-btn'>Edit</a>
                      <a href='hapus_produk.php?id=" . urlencode($row['id']) . "' class='delete-btn' onclick=\"return confirmDelete('".htmlspecialchars(addslashes($row['nama']))."')\">Hapus</a>
                    </td>";
              echo "</tr>";
            }
          }
        ?>
      </tbody>
    </table>
    <br />
    <a href="dashboar.php" class="button">Kembali ke Dashboard</a>
  </div>
</body>
</html>
