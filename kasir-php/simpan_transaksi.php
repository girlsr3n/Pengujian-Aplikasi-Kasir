<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produk_ids = isset($_POST['produk_id']) ? $_POST['produk_id'] : [];
    $qtys = isset($_POST['qty']) ? $_POST['qty'] : [];

    if (count($produk_ids) == 0 || count($qtys) == 0) {
        die("Tidak ada data produk yang dikirim.");
    }

    $items = [];
    $total = 0;

    foreach ($produk_ids as $index => $id) {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $qty = isset($qtys[$index]) ? filter_var($qtys[$index], FILTER_VALIDATE_INT) : 0;

        if ($id === false || $qty === false || $qty <= 0) continue;

        // Ambil produk dari database
        $stmt = $conn->prepare("SELECT id, nama, harga, stok FROM products WHERE id = ?");
        if (!$stmt) die("Prepare gagal: " . $conn->error);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($row['stok'] < $qty) {
                die("Transaksi gagal: Stok tidak cukup untuk produk \"" . htmlspecialchars($row['nama']) . "\".");
            }

            $harga = $row['harga'];
            if (!is_numeric($harga)) continue;

            $subtotal = $harga * $qty;

            $items[] = [
                'produk_id' => $id,
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }

        $stmt->close();
    }

    if (count($items) === 0) {
        die("Tidak ada item valid yang disimpan.");
    }

    // Set zona waktu ke WIB (Asia/Jakarta)
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');

    // Simpan ke tabel transactions
    $stmt = $conn->prepare("INSERT INTO transactions (tanggal, total) VALUES (?, ?)");
    if (!$stmt) die("Prepare gagal saat insert transaksi: " . $conn->error);
    $stmt->bind_param("si", $tanggal, $total);
    if (!$stmt->execute()) {
        die("Gagal menyimpan transaksi: " . $stmt->error);
    }
    $transaksi_id = $stmt->insert_id;
    $stmt->close();

    // Siapkan statement insert item dan update stok
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

    header("Location: index.php?msg=Transaksi berhasil disimpan");
    exit;
} else {
    echo "Akses tidak valid.";
}
