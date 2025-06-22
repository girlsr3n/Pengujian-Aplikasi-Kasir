<?php
include 'db.php';

// Ambil semua produk yang stoknya lebih dari 0
$result = $conn->query("SELECT * FROM products WHERE stok > 0 ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Transaksi Baru - Kasir PHP</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      font-family: Arial, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 50px 20px;
    }

    .container {
      width: 100%;
      max-width: 800px;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #007bff;
      color: white;
      text-align: center;
    }

    td input[type="number"] {
      width: 70px;
      padding: 5px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .subtotal {
      text-align: right;
    }

    #totalBelanja {
      font-size: 1.5rem;
      font-weight: bold;
      color: #007bff;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    select, input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #0056b3;
    }

    a.button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #6c757d;
      color: white;
      text-decoration: none;
      text-align: center;
      border-radius: 4px;
    }

    a.button:hover {
      background-color: #5a6268;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Transaksi Baru</h2>
    <form method="post" action="simpan_transaksi.php" onsubmit="return cekValidasiTunai();">
      <table id="produkTable">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Harga (Rp)</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Diskon (%)</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <?= htmlspecialchars($row['nama']) ?>
                <input type="hidden" name="produk_id[]" value="<?= $row['id'] ?>" />
                <input type="hidden" name="harga[]" value="<?= $row['harga'] ?>" />
                <input type="hidden" name="diskon[]" value="<?= $row['diskon'] ?>" />
              </td>
              <td style="text-align:right"><?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td style="text-align:center"><?= $row['stok'] ?></td>
              <td><?= htmlspecialchars($row['kategori']) ?></td>
              <td style="text-align:center"><?= $row['diskon'] ?>%</td>
              <td>
                <input type="number" name="qty[]" value="0" min="0" max="<?= $row['stok'] ?>" onchange="hitungTotal()" />
              </td>
              <td class="subtotal" style="text-align:right">Rp 0</td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <h3>Total Belanja: <span id="totalBelanja">Rp 0</span></h3>
      <input type="hidden" name="total_harga" id="total_harga_hidden" value="0" />

      <div class="form-group">
        <label for="metode_pembayaran">Metode Pembayaran:</label>
        <select name="metode_pembayaran" id="metode_pembayaran">
          <option value="tunai">Tunai</option>
          <option value="qris">QRIS</option>
        </select>
      </div>

      <div class="form-group">
        <label for="jumlah_bayar">Jumlah Uang Tunai (jika tunai):</label>
        <input type="number" name="jumlah_bayar" id="jumlah_bayar" min="0" placeholder="Masukkan jumlah bayar" />
      </div>

      <button type="submit">Simpan Transaksi</button>
    </form>

    <br />
    <a href="dashboar.php" class="button">Kembali ke Dashboard</a>
  </div>

  <script>
    function formatRupiah(angka) {
      return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function hitungTotal() {
      let rows = document.querySelectorAll('#produkTable tbody tr');
      let total = 0;

      rows.forEach(row => {
        let harga = parseFloat(row.querySelector('input[name="harga[]"]').value);
        let diskon = parseFloat(row.querySelector('input[name="diskon[]"]').value);
        let qty = parseInt(row.querySelector('input[name="qty[]"]').value) || 0;

        let hargaSetelahDiskon = harga - (harga * diskon / 100);
        let subtotal = hargaSetelahDiskon * qty;

        row.querySelector('.subtotal').innerText = formatRupiah(subtotal);
        total += subtotal;
      });

      document.getElementById('totalBelanja').innerText = formatRupiah(total);
      document.getElementById('total_harga_hidden').value = Math.round(total);
    }

    function cekValidasiTunai() {
      const metode = document.getElementById('metode_pembayaran').value;
      const jumlahBayar = parseInt(document.getElementById('jumlah_bayar').value || '0');
      const total = parseInt(document.getElementById('total_harga_hidden').value);

      if (metode === 'tunai' && jumlahBayar < total) {
        alert('Jumlah uang tunai tidak mencukupi untuk total belanja!');
        return false;
      }
      return true;
    }

    const metodeSelect = document.getElementById('metode_pembayaran');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');

    metodeSelect.addEventListener('change', function () {
      if (this.value === 'tunai') {
        jumlahBayarInput.disabled = false;
        jumlahBayarInput.required = true;
      } else {
        jumlahBayarInput.disabled = true;
        jumlahBayarInput.required = false;
        jumlahBayarInput.value = '';
      }
    });

    window.addEventListener('DOMContentLoaded', () => {
      if (metodeSelect.value === 'qris') {
        jumlahBayarInput.disabled = true;
        jumlahBayarInput.required = false;
      }
      hitungTotal(); // Hitung saat awal buka
    });
  </script>
</body>
</html>
