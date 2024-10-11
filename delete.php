<?php
include('config.php'); // Hubungkan dengan file koneksi database

// Periksa apakah parameter 'nidn' ada di URL
if (isset($_GET['nidn'])) {
    $nidn = $_GET['nidn'];

    // Query untuk menghapus data dosen berdasarkan NIDN
    $query = "DELETE FROM users WHERE nidn = '$nidn'";
    
    if (mysqli_query($conn, $query)) {
        // Jika query berhasil, redirect ke halaman list.php
        header("Location: list.php");
    } else {
        // Jika ada error pada query
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika parameter 'nidn' tidak ada di URL
    echo "ID tidak ditemukan.";
}

mysqli_close($conn); // Tutup koneksi database
?>
