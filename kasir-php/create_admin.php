<?php
include 'db.php';

// Data input admin
$username = 'admin';
$passwordPlain = 'admin123cc';  // Ganti sesuai kebutuhan
$role = 'admin';

// Validasi panjang password
if (strlen($passwordPlain) < 8 || strlen($passwordPlain) > 32) {
    echo "Password harus terdiri dari 8 hingga 32 karakter.";
    exit;
}

// Cek apakah username sudah ada
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Username sudah terdaftar.";
    exit;
}

// Hash password dan simpan
$hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);

$stmtInsert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmtInsert->bind_param('sss', $username, $hashedPassword, $role);

if ($stmtInsert->execute()) {
    echo "User admin berhasil dibuat.";
} else {
    echo "Gagal membuat user: " . $conn->error;
}
?>
