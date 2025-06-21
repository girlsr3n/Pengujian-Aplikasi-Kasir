<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Data jumlah produk
$resProduk = $conn->query("SELECT COUNT(*) as total_produk FROM products");
if (!$resProduk) {
    die("Query error (products count): " . $conn->error);
}
$jumlahProduk = $resProduk->fetch_assoc()['total_produk'] ?? 0;

// Data total transaksi dan total pendapatan
$resTransaksi = $conn->query("
    SELECT 
        COUNT(DISTINCT t.id) as total_transaksi, 
        IFNULL(SUM(ti.qty * ti.harga), 0) as total_pendapatan
    FROM transactions t
    LEFT JOIN transaction_items ti ON t.id = ti.transaksi_id
");
if (!$resTransaksi) {
    die("Query error (transactions summary): " . $conn->error);
}
$dataTransaksi = $resTransaksi->fetch_assoc();
$jumlahTransaksi = $dataTransaksi['total_transaksi'] ?? 0;
$totalPendapatan = $dataTransaksi['total_pendapatan'] ?? 0;

// Grafik penjualan 7 hari terakhir
$data = [];
$query = "
    SELECT 
        DATE(t.tanggal) as tanggal, 
        IFNULL(SUM(ti.qty * ti.harga), 0) as total_harian
    FROM transactions t
    LEFT JOIN transaction_items ti ON t.id = ti.transaksi_id
    WHERE t.tanggal >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(t.tanggal)
    ORDER BY DATE(t.tanggal) ASC
";
$result = $conn->query($query);
if (!$result) {
    die("Query error (7-day sales): " . $conn->error);
}
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Laporan Produk Terlaris
$laporan = [];
$queryLaporan = "
    SELECT 
        p.nama as produk, 
        SUM(ti.qty) as total_qty,
        SUM(ti.qty * ti.harga) as total_pendapatan
    FROM transaction_items ti
    JOIN products p ON ti.produk_id = p.id
    GROUP BY ti.produk_id
    ORDER BY total_qty DESC
    LIMIT 5
";
$resLaporan = $conn->query($queryLaporan);
if (!$resLaporan) {
    die("Query error (produk terlaris): " . $conn->error);
}
while ($row = $resLaporan->fetch_assoc()) {
    $laporan[] = $row;
}

// Produk dengan stok ≤ 2 (stok habis / hampir habis)
$stokHabis = [];
$resStok = $conn->query("SELECT nama, stok FROM products WHERE stok <= 2 ORDER BY stok ASC");
if ($resStok) {
    while ($row = $resStok->fetch_assoc()) {
        $stokHabis[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - Kasir PHP</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
      color: #333;
    }
    .topbar {
      background-color: #26418f;
      color: white;
      padding: 16px 30px;
      font-size: 22px;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logout-link {
      background-color: #c0392b;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 8px;
    }
    .container {
      display: flex;
      max-width: 1200px;
      margin: 20px auto;
      gap: 20px;
      padding: 0 15px;
    }
    .sidebar {
      width: 220px;
      background-color: #26418f;
      border-radius: 10px;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      padding: 10px 16px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: transparent;
    }
    .sidebar a:hover {
      background-color: #1e2f6f;
    }
    .content {
      flex: 1;
    }
    h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #26418f;
      text-align: center;
    }
    .main-section {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      align-items: stretch;
    }
    .card {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      text-align: center;
    }
    .card i {
      font-size: 24px;
      color: #26418f;
      margin-bottom: 10px;
    }
    .card h3 {
      font-size: 1.8rem;
      color: #26418f;
      margin: 0;
    }
    .card p {
      margin-top: 8px;
      font-size: 0.9rem;
      color: #666;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    canvas {
      width: 100% !important;
      height: 200px !important;
    }
    .laporan-section {
      margin-top: 30px;
    }
    .laporan-section h3 {
      text-align: center;
      color: #26418f;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: white;
      border-radius: 10px;
      overflow: hidden;
    }
    table th, table td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    table th {
      background-color: #26418f;
      color: white;
    }
    /* Highlight baris stok habis (0) dengan latar merah muda */
    tr[style*="background-color: #fdecea;"] td {
      color: #c0392b;
      font-weight: bold;
    }
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-around;
      }
      .sidebar a {
        flex: 1 1 40%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <div class="topbar">
    <div><i class="fas fa-cash-register"></i> Dashboard Kasir</div>
    <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
  <div class="container">
    <nav class="sidebar">
      <a href="index.php"><i class="fas fa-list"></i> Daftar Produk</a>
      <a href="transaksi_baru.php"><i class="fas fa-plus"></i> Transaksi Baru</a>
      <a href="transaksi.php"><i class="fas fa-history"></i> Riwayat Transaksi</a>
      <a href="tambah_produk.php"><i class="fas fa-plus-square"></i> Tambah Produk</a>
      <a href="logout.php" style="background-color: #c0392b;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <div class="content">
      <h2>Statistik Penjualan</h2>
      <div class="main-section">
        <div class="card">
          <i class="fas fa-money-bill-wave"></i>
          <h3>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h3>
          <p>Total Pendapatan</p>
        </div>
        <div class="card">
          <i class="fas fa-boxes"></i>
          <h3><?= $jumlahProduk ?></h3>
          <p>Jumlah Produk</p>
        </div>
        <div class="card">
          <i class="fas fa-receipt"></i>
          <h3><?= $jumlahTransaksi ?></h3>
          <p>Transaksi</p>
        </div>
        <div class="card">
          <i class="fas fa-chart-bar"></i>
          <h3 style="font-size: 1rem;">Grafik 7 Hari</h3>
          <canvas id="penjualanChart"></canvas>
        </div>
      </div>

      <!-- Tabel Produk Stok Hampir Habis -->
      <?php if (!empty($stokHabis)): ?>
        <div class="laporan-section">
          <h3 style="color: #c0392b; text-align: center;">⚠️ Produk Stok Hampir Habis</h3>
          <table>
            <thead>
              <tr>
                <th>Nama Produk</th>
                <th>Sisa Stok</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($stokHabis as $item): ?>
                <tr style="<?= $item['stok'] == 0 ? 'background-color: #fdecea;' : '' ?>">
                  <td><?= htmlspecialchars($item['nama']) ?></td>
                  <td><?= $item['stok'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <div class="laporan-section">
        <h3>Produk Terlaris</h3>
        <table>
          <thead>
            <tr>
              <th>Produk</th>
              <th>Total Terjual</th>
              <th>Total Pendapatan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($laporan as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['produk']) ?></td>
                <td><?= $item['total_qty'] ?></td>
                <td>Rp <?= number_format($item['total_pendapatan'], 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    const ctx = document.getElementById('penjualanChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_column($data, 'tanggal')) ?>,
        datasets: [{
          label: 'Total Penjualan (Rp)',
          data: <?= json_encode(array_map('floatval', array_column($data, 'total_harian'))) ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          borderRadius: 5,
          maxBarThickness: 40,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  </script>
</body>
</html>
