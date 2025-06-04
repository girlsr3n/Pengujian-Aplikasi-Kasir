<?php
// db.php
// Jangan panggil session_start() di sini

$servername = "localhost";
$username = "root";
$password = "";
$database = "kasir";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
