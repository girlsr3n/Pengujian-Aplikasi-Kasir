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
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #eef3fb;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
    }

    .container {
      background: white;
      padding: 40px;
      max-width: 960px;
      width: 100%;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #1e293b;
      margin-bottom: 20px;
    }

    .button {
      background-color: #3f5efb;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      border: none;
      cursor: pointer;
      margin-bottom: 20px;
    }

    .button:hover {
      background-color: #2c4acb;
    }

    .search-box input[type="text"] {
      width: 100%;
      padding: 12px;
      font-size: 1rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background-color: #3f5efb;
      color: white;
      padding: 12px;
      text-align: center;
    }

    td {
      padding: 12px;
      text-align: center;
      color: #1e293b;
    }

    tr:nth-child(even) {
      background-color: #f5f7ff;
    }

    tr:nth-child(odd) {
      background-color: #ffffff;
    }

    .action-buttons a {
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 0.9em;
      text-decoration: none;
      font-weight: 500;
      margin: 0 4px;
    }

    .edit-btn {
      background-color: #f59e0b;
      color: white;
    }

    .edit-btn:hover {
      background-color: #d97706;
    }

    .delete-btn {
      background-color: #ef4444;
      color: white;
    }

    .delete-btn:hover {
      background-color: #dc2626;
    }

    .empty-message {
      text-align: center;
      font-style: italic;
      color: #888;
      padding: 20px;
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
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll("#produkTable tbody tr");
      rows.forEach(row => {
        const name = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
        row.style.display = name.includes(input) ? "" : "none";
      });
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Daftar Produk</h2>

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
          <th>Kategori</th> <!-- Tambah kolom kategori -->
          <th>Diskon (%)</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
          if ($result->num_rows === 0) {
            echo '<tr><td colspan="7" class="empty-message">Belum ada produk.</td></tr>';
          } else {
            while ($row = $result->fetch_assoc()) {
              $id = (int) $row['id'];
              $nama = htmlspecialchars($row['nama']);
              $harga = number_format($row['harga'], 0, ',', '.');
              $stok = (int) $row['stok'];
              $kategori = htmlspecialchars($row['kategori']);
              $diskon = (int) $row['diskon'];

              echo "<tr>";
              echo "<td>$id</td>";
              echo "<td>$nama</td>";
              echo "<td>$harga</td>";
              echo "<td>$stok</td>";
              echo "<td>$kategori</td>";
              echo "<td>$diskon%</td>";
              echo "<td class='action-buttons'>
                      <a href='tambah_produk.php?id=$id' class='edit-btn'>Edit</a>
                      <a href='hapus_produk.php?id=$id' class='delete-btn' onclick=\"return confirmDelete('$nama')\">Hapus</a>
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
