<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produk_ids = $_POST['produk_id'] ?? [];
    $qtys = $_POST['qty'] ?? [];

    // Tambahan
    $metode = $_POST['metode_pembayaran'] ?? 'tunai';
    $jumlah_bayar = isset($_POST['jumlah_bayar']) ? floatval($_POST['jumlah_bayar']) : 0;
    $kembalian = 0;

    if (count($produk_ids) == 0 || count($qtys) == 0) {
        die("Tidak ada data produk yang dikirim.");
    }

    $items = [];
    $total = 0;

    foreach ($produk_ids as $index => $id) {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $qty = isset($qtys[$index]) ? filter_var($qtys[$index], FILTER_VALIDATE_INT) : 0;

        if ($id === false || $qty === false || $qty <= 0) continue;

        $stmt = $conn->prepare("SELECT id, nama, harga, stok, kategori, diskon FROM products WHERE id = ?");
        if (!$stmt) die("Prepare gagal: " . $conn->error);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($row['stok'] < $qty) {
                die("Stok tidak cukup untuk produk \"" . htmlspecialchars($row['nama']) . "\".");
            }

            $harga = $row['harga'];
            $diskon = $row['diskon'] ?? 0;
            if (!is_numeric($harga)) continue;

            $harga_diskon = $harga * (1 - $diskon / 100);
            $subtotal = $harga_diskon * $qty;

            $items[] = [
                'produk_id' => $id,
                'nama' => $row['nama'],
                'kategori' => $row['kategori'],
                'harga' => $harga,
                'diskon' => $diskon,
                'harga_diskon' => $harga_diskon,
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }

        $stmt->close();
    }

    if (count($items) === 0) {
        die("Tidak ada item valid yang disimpan.");
    }

    // Validasi pembayaran (Tunai)
    if ($metode === 'tunai') {
        if ($jumlah_bayar < $total) {
            die("Uang tunai tidak cukup untuk membayar total belanja.");
        }
        $kembalian = $jumlah_bayar - $total;
    } else {
        $jumlah_bayar = $total; // QRIS dianggap pas
    }

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');

    // Perbaikan di sini: tambahkan kolom metode_pembayaran
    $stmt = $conn->prepare("INSERT INTO transactions (tanggal, total, metode_pembayaran) VALUES (?, ?, ?)");
    if (!$stmt) die("Prepare gagal saat insert transaksi: " . $conn->error);
    $stmt->bind_param("sds", $tanggal, $total, $metode);
    if (!$stmt->execute()) {
        die("Gagal menyimpan transaksi: " . $stmt->error);
    }
    $transaksi_id = $stmt->insert_id;
    $stmt->close();

    $stmt_item = $conn->prepare("INSERT INTO transaction_items (transaksi_id, produk_id, qty, harga) VALUES (?, ?, ?, ?)");
    if (!$stmt_item) die("Prepare gagal saat insert item: " . $conn->error);
    $stmt_stok = $conn->prepare("UPDATE products SET stok = stok - ? WHERE id = ?");
    if (!$stmt_stok) die("Prepare gagal saat update stok: " . $conn->error);

    foreach ($items as $item) {
        $stmt_item->bind_param("iiii", $transaksi_id, $item['produk_id'], $item['qty'], $item['harga']);
        $stmt_item->execute();

        $stmt_stok->bind_param("ii", $item['qty'], $item['produk_id']);
        $stmt_stok->execute();
    }

    $stmt_item->close();
    $stmt_stok->close();

    // STRUK HTML
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <title>Struk Belanja</title>
        <style>
            body {
                font-family: monospace;
                max-width: 480px;
                margin: auto;
                padding: 20px;
                background: #fff;
            }
            h2 {
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 12px;
            }
            th, td {
                border-bottom: 1px dashed #333;
                padding: 6px 8px;
                text-align: left;
            }
            th {
                border-bottom: 2px solid #000;
            }
            .right {
                text-align: right;
            }
            .total-row td {
                font-weight: bold;
                border-top: 2px solid #000;
            }
            .summary {
                margin-top: 16px;
                font-size: 14px;
            }
            .summary p {
                margin: 4px 0;
            }
            button.print-btn {
                margin-top: 20px;
                padding: 10px 0;
                width: 100%;
                font-size: 16px;
                background: #4361ee;
                color: white;
                border: none;
                border-radius: 8px;
                cursor: pointer;
            }
            button.print-btn:hover {
                background: #3a54d1;
            }
        </style>
    </head>
    <body>
        <h2>Struk Belanja</h2>
        <p><strong>Tanggal:</strong> <?= htmlspecialchars($tanggal) ?></p>
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="right">Harga Asli</th>
                    <th class="right">Diskon (%)</th>
                    <th class="right">Jumlah</th>
                    <th class="right">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama']) ?></td>
                    <td><?= htmlspecialchars($item['kategori']) ?></td>
                    <td class="right"><?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td class="right"><?= $item['diskon'] ?>%</td>
                    <td class="right"><?= $item['qty'] ?></td>
                    <td class="right"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="right">Total Bayar</td>
                    <td class="right"><?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="summary">
            <p><strong>Metode Pembayaran:</strong> <?= strtoupper(htmlspecialchars($metode)) ?></p>
            <p><strong>Dibayar:</strong> Rp <?= number_format($jumlah_bayar, 0, ',', '.') ?></p>
            <?php if ($metode === 'tunai'): ?>
                <p><strong>Kembalian:</strong> Rp <?= number_format($kembalian, 0, ',', '.') ?></p>
            <?php endif; ?>
        </div>

        <button class="print-btn" onclick="window.print()">Cetak Struk</button>
        <p style="text-align:center; margin-top: 16px;">
            <a href="index.php">Kembali ke Daftar Produk</a>
        </p>
    </body>
    </html>
    <?php
    exit;
} else {
    echo "Akses tidak valid.";
}
