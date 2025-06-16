<?php
include 'db.php';

if (isset($_GET['id'])) {
  $id = (int)$_GET['id'];
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    header("Location: index.php?msg=Produk berhasil dihapus");
    exit();
  } else {
    echo "Gagal menghapus produk: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "ID tidak ditemukan.";
}
?>
