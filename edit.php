<?php
include('config.php'); // Hubungkan ke file konfigurasi database

// Cek apakah form telah disubmit untuk proses update
if (isset($_POST['submit'])) {
    $nidn_lama = $_POST['nidn_lama']; // NIDN yang lama
    $nidn_baru = $_POST['nidn_baru']; // NIDN yang baru
    $nama_dosen = $_POST['nama_dosen'];
    $tgl_mulai_tugas = $_POST['tgl_mulai_tugas'];
    $jenjang_pendidikan = $_POST['jenjang_pendidikan'];
    $bidang_keilmuan = $_POST['bidang_keilmuan'];
    $foto_dosen_lama = $_POST['foto_dosen_lama']; // Foto lama dari form

    // Proses upload foto dosen (jika diupdate)
    $foto_dosen = $_FILES['foto_dosen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto_dosen);

    // Jika ada file foto yang diupload, proses upload
    if (!empty($foto_dosen)) {
        move_uploaded_file($_FILES['foto_dosen']['tmp_name'], $target_file);
        // Update query termasuk foto baru
        $foto_dosen_terpakai = $foto_dosen; // Gunakan foto baru
    } else {
        // Jika tidak ada foto yang diupload, gunakan foto lama
        $foto_dosen_terpakai = $foto_dosen_lama; // Gunakan foto lama
    }

    $query = "UPDATE users SET 
              nidn = '$nidn_baru',
              nama_dosen = '$nama_dosen', 
              tgl_mulai_tugas = '$tgl_mulai_tugas',
              jenjang_pendidikan = '$jenjang_pendidikan', 
              bidang_keilmuan = '$bidang_keilmuan',
              foto_dosen = '$foto_dosen_terpakai' 
              WHERE nidn = '$nidn_lama'";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil update, arahkan kembali ke halaman list.php
        header("Location: list.php");
        exit(); // Pastikan script berhenti setelah redirect
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ambil data dosen berdasarkan NIDN
if (isset($_GET['nidn'])) {
    $nidn = $_GET['nidn'];
    $query = "SELECT * FROM users WHERE nidn = '$nidn'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        echo "Data dosen tidak ditemukan.";
        exit();
    }
}

mysqli_close($conn); // Tutup koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Dosen</title>
    <style>
        /* CSS sama seperti sebelumnya untuk styling form */
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

        /* Styling untuk gambar yang ditampilkan */
        img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

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

<h2>Edit Data Dosen</h2>

<form action="edit.php" method="post" enctype="multipart/form-data">
    <!-- NIDN Lama (Hidden) -->
    <input type="hidden" name="nidn_lama" value="<?php echo $data['nidn']; ?>">

    <!-- Input untuk NIDN Baru -->
    <label for="nidn_baru">NIDN:</label>
    <input type="text" name="nidn_baru" id="nidn_baru" value="<?php echo $data['nidn']; ?>" required>

    <label for="nama_dosen">Nama Dosen:</label>
    <input type="text" name="nama_dosen" id="nama_dosen" value="<?php echo $data['nama_dosen']; ?>" required>

    <label for="tgl_mulai_tugas">Tanggal Mulai Tugas:</label>
    <input type="date" name="tgl_mulai_tugas" id="tgl_mulai_tugas" value="<?php echo $data['tgl_mulai_tugas']; ?>" required>

    <label for="jenjang_pendidikan">Jenjang Pendidikan:</label>
    <select name="jenjang_pendidikan" id="jenjang_pendidikan">
        <option value="S2" <?php echo ($data['jenjang_pendidikan'] == 'S2') ? 'selected' : ''; ?>>S2</option>
        <option value="S3" <?php echo ($data['jenjang_pendidikan'] == 'S3') ? 'selected' : ''; ?>>S3</option>
    </select>

    <label for="bidang_keilmuan">Bidang Keilmuan:</label>
    <input type="text" name="bidang_keilmuan" id="bidang_keilmuan" value="<?php echo $data['bidang_keilmuan']; ?>" required>

    <!-- Tampilan gambar lama -->
    <label>Foto Dosen Saat Ini:</label><br>
    <img src="uploads/<?php echo $data['foto_dosen']; ?>" alt="Foto Dosen">
    <input type="hidden" name="foto_dosen_lama" value="<?php echo $data['foto_dosen']; ?>"><br><br>

    <label for="foto_dosen">Foto Dosen (jika ingin mengubah):</label>
    <input type="file" name="foto_dosen" id="foto_dosen" accept="image/*">

    <input type="submit" name="submit" value="Update Data">
</form>

</body>
</html>
