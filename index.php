<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Produk - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4ff;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      padding: 30px;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
    }

    .button {
      background-color: #4361ee;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      cursor: pointer;
      display: inline-block;
      transition: background-color 0.3s;
    }

    .button:hover {
      background-color: #3a54d1;
    }

    .search-box {
      margin: 20px 0;
    }

    input[type="text"] {
      padding: 10px;
      width: 100%;
      border-radius: 8px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th {
      background-color: #4361ee;
      color: white;
      padding: 12px;
      text-align: center;
    }

    td {
      padding: 10px;
      text-align: center;
      color: #2c3e50;
    }

    tr:nth-child(even) {
      background-color: #f4f7ff;
    }

    tr:nth-child(odd) {
      background-color: #ffffff;
    }

    .action-buttons a {
      margin: 0 5px;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.9em;
      text-decoration: none;
      display: inline-block;
    }

    .edit-btn {
      background-color: #f0ad4e;
      color: white;
    }

    .edit-btn:hover {
      background-color: #ec971f;
    }

    .delete-btn {
      background-color: #d9534f;
      color: white;
    }

    .delete-btn:hover {
      background-color: #c9302c;
    }

    .empty-message {
      text-align: center;
      font-style: italic;
      color: #888;
      padding: 20px 0;
    }

    .btn-center {
      text-align: center;
      margin-top: 30px;
    }
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

    <!-- Pesan notifikasi -->
    <?php if (isset($_GET['msg'])): ?>
      <p style="color: green; font-weight: 600; text-align:center;">
        <?= htmlspecialchars($_GET['msg']) ?>
      </p>
    <?php endif; ?>

    <a href="tambah_produk.php" class="button">+ Tambah Produk</a>

    <div class="search-box">
      <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="searchProducts()" />
    </div>

    <table id="produkTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Harga (Rp)</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
          if ($result->num_rows == 0) {
            echo '<tr><td colspan="5" class="empty-message">Belum ada produk.</td></tr>';
          } else {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . number_format($row['harga'], 0, ',', '.') . "</td>";
              echo "<td>" . (int)$row['stok'] . "</td>";
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

    <div class="btn-center">
      <a href="dashboar.php" class="button">Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
