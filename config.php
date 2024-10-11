<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username = "root";  // Username default di XAMPP adalah "root"
$password = "";  // Password default di XAMPP adalah kosong (blank)
$dbname = "tes_database";  // Pastikan nama database benar

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
