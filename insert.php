<?php
include('config.php'); // Hubungkan ke file konfigurasi database

if (isset($_POST['submit'])) {
    $nidn = $_POST['nidn'];
    $nama_dosen = $_POST['nama_dosen'];
    $tgl_mulai_tugas = $_POST['tgl_mulai_tugas'];
    $jenjang_pendidikan = $_POST['jenjang_pendidikan'];
    $bidang_keilmuan = $_POST['bidang_keilmuan'];

    // Proses upload foto
    $foto_dosen = $_FILES['foto_dosen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto_dosen);

    // Pindahkan file yang diupload ke folder uploads
    if (move_uploaded_file($_FILES['foto_dosen']['tmp_name'], $target_file)) {
        // Foto berhasil diupload
    } else {
        // Jika gagal upload, beri nilai kosong
        $foto_dosen = null;
    }

    // Cek apakah NIDN sudah ada
    $check_query = "SELECT * FROM users WHERE nidn = '$nidn'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "NIDN sudah terdaftar. Silakan gunakan NIDN yang berbeda.";
    } else {
        // Query untuk memasukkan data dosen
        $query = "INSERT INTO users (nidn, nama_dosen, tgl_mulai_tugas, jenjang_pendidikan, bidang_keilmuan, foto_dosen) 
                  VALUES ('$nidn', '$nama_dosen', '$tgl_mulai_tugas', '$jenjang_pendidikan', '$bidang_keilmuan', '$foto_dosen')";

        if (mysqli_query($conn, $query)) {
            // Redirect ke halaman list.php setelah berhasil tambah data
            header("Location: list.php");
            exit(); // Pastikan script berhenti setelah redirect
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn); // Tutup koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Dosen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        select,
        input[type="file"] {
            width: 97%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Tambahan gaya untuk mempercantik */
        input:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        select {
        width: 101%; /* Modifikasi lebar input jenjang pendidikan */
    }
    </style>
</head>
<body>

<h2>Tambah Data Dosen</h2>

<form action="insert.php" method="post" enctype="multipart/form-data">
    <label for="nidn">NIDN:</label>
    <input type="text" name="nidn" id="nidn" required>

    <label for="nama_dosen">Nama Dosen:</label>
    <input type="text" name="nama_dosen" id="nama_dosen" required>

    <label for="tgl_mulai_tugas">Tanggal Mulai Tugas:</label>
    <input type="date" name="tgl_mulai_tugas" id="tgl_mulai_tugas" required>

    <label for="jenjang_pendidikan">Jenjang Pendidikan:</label>
    <select name="jenjang_pendidikan" id="jenjang_pendidikan">
        <option value="S2">S2</option>
        <option value="S3">S3</option>
    </select>

    <label for="bidang_keilmuan">Bidang Keilmuan:</label>
    <input type="text" name="bidang_keilmuan" id="bidang_keilmuan" required>

    <label for="foto_dosen">Foto Dosen:</label>
    <input type="file" name="foto_dosen" id="foto_dosen" accept="image/*">

    <input type="submit" name="submit" value="Tambah Dosen">
</form>

</body>
</html>
