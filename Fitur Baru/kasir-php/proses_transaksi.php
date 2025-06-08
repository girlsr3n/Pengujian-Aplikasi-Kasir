<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: transaksi_baru.php");
    exit;
}

$qty = $_POST['qty'] ?? [];

$items = [];
foreach ($qty as $product_id => $jumlah) {
    $jumlah = (int)$jumlah;
    if ($jumlah > 0) {
        // Ambil data produk
        $stmt = $conn->prepare("SELECT harga FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $stmt->close();
            continue; // produk tidak ditemukan, lewati
        }
        $produk = $result->fetch_assoc();
        $stmt->close();

        $harga = $produk['harga'];
        $subtotal = $harga * $jumlah;

        $items[] = [
            'product_id' => $product_id,
            'qty' => $jumlah,
            'harga_satuan' => $harga,
            'subtotal' => $subtotal
        ];
    }
}

if (count($items) === 0) {
    // Tidak ada produk yang dibeli
    echo "Tidak ada produk yang dipilih.";
    echo '<br><a href="transaksi_baru.php">Kembali</a>';
    exit;
}

// Hitung total transaksi
$total = 0;
foreach ($items as $item) {
    $total += $item['subtotal'];
}

// Mulai transaksi database
$conn->begin_transaction();

try {
    // Insert ke tabel transaksi
    $stmt = $conn->prepare("INSERT INTO transaksi (total) VALUES (?)");
    $stmt->bind_param("i", $total);
    $stmt->execute();
    $transaksi_id = $stmt->insert_id;
    $stmt->close();

    // Insert ke tabel transaksi_items
    $stmt = $conn->prepare("INSERT INTO transaksi_items (transaksi_id, product_id, qty, subtotal) VALUES (?, ?, ?, ?)");

    foreach ($items as $item) {
        $stmt->bind_param("iiii", $transaksi_id, $item['product_id'], $item['qty'], $item['subtotal']);
        $stmt->execute();
    }
    $stmt->close();

    $conn->commit();

    // Redirect ke halaman detail transaksi
    header("Location: detail_transaksi.php?id=$transaksi_id");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo "Gagal menyimpan transaksi: " . $e->getMessage();
    echo '<br><a href="transaksi_baru.php">Kembali</a>';
    exit;
}
