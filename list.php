<?php
include('config.php'); // Koneksi ke database

// Cek apakah ada pencarian yang dilakukan
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // Query untuk mencari data berdasarkan NIDN, Nama Dosen, atau Bidang Keilmuan
    $query = "SELECT * FROM users WHERE nidn LIKE '%$searchQuery%' OR nama_dosen LIKE '%$searchQuery%' OR bidang_keilmuan LIKE '%$searchQuery%' ORDER BY nidn ASC";
} else {
    // Tampilkan semua data jika tidak ada pencarian
    $query = "SELECT * FROM users ORDER BY nidn ASC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #4cae4c;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .export-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .export-buttons button {
            background-color: #007bff;
        }

        .export-buttons button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        img {
            border-radius: 5px;
        }

        .action-links {
            text-align: center;
        }

        /* Menambahkan CSS untuk link Edit dan Delete */
        .action-links a {
            color: black; /* Warna hitam untuk Edit & Delete */
            margin: 0 5px;
        }

        .action-links a:hover {
            text-decoration: underline; /* Efek underline saat hover */
        }
    </style>
</head>
<body>

<h2>Daftar Dosen</h2>

<!-- Form pencarian -->
<form method="GET" action="list.php">
    <input type="text" name="search" placeholder="Cari NIDN, Nama Dosen, atau Bidang Keilmuan" value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button type="submit">Cari</button>
</form>

<!-- Tombol tambah dan export -->
<div class="export-buttons">
    <a href="insert.php"><button><i class="fas fa-plus"></i> Tambah Data Dosen</button></a>
    <a href="export_excel.php"><button><i class="fas fa-file-excel"></i> Export to Excel</button></a>
    <a href="export_pdf.php"><button><i class="fas fa-file-pdf"></i> Export to PDF</button></a>
</div>

<!-- Tabel Data -->
<table>
    <thead>
        <tr>
            <th>NIDN</th>
            <th>Nama Dosen</th>
            <th>Tanggal Mulai Tugas</th>
            <th>Jenjang Pendidikan</th>
            <th>Bidang Keilmuan</th>
            <th>Foto Dosen</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nidn']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
                    <td><?php echo htmlspecialchars($row['tgl_mulai_tugas']); ?></td>
                    <td><?php echo htmlspecialchars($row['jenjang_pendidikan']); ?></td>
                    <td><?php echo htmlspecialchars($row['bidang_keilmuan']); ?></td>
                    <td><img src="uploads/<?php echo htmlspecialchars($row['foto_dosen']); ?>" alt="Foto Dosen" width="100"></td>
                    <td class="action-links">
                        <a href="edit.php?nidn=<?php echo htmlspecialchars($row['nidn']); ?>">Edit</a> |
                        <a href="delete.php?nidn=<?php echo htmlspecialchars($row['nidn']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Tidak ada data yang ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
mysqli_close($conn); // Tutup koneksi database
?>
