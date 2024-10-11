<?php
include('config.php');
require('fpdf/fpdf.php');

// Query untuk mengambil data dosen
$query = "SELECT * FROM users ORDER BY nidn ASC";
$result = mysqli_query($conn, $query);

// Membuat PDF baru
$pdf = new FPDF();
$pdf->SetMargins(15, 10, 10); // Atur margin kiri, atas, kanan
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);

// Judul kolom dengan lebar sel yang lebih kecil
$pdf->Cell(15, 10, 'NIDN', 1, 0, 'C');
$pdf->Cell(30, 10, 'Nama Dosen', 1, 0, 'C');
$pdf->Cell(30, 10, 'Tgl Mulai Tugas', 1, 0, 'C');
$pdf->Cell(20, 10, 'Jenjang', 1, 0, 'C');
$pdf->Cell(30, 10, 'Bidang Keilmuan', 1, 0, 'C');
$pdf->Cell(40, 10, 'Foto', 1, 0, 'C');
$pdf->Ln();

// Mengisi data ke PDF
$pdf->SetFont('Arial', '', 9);
while ($row = mysqli_fetch_assoc($result)) {
    $nidn = $row['nidn'];
    $nama_dosen = $row['nama_dosen'];
    $tgl_mulai_tugas = $row['tgl_mulai_tugas'];
    $jenjang_pendidikan = $row['jenjang_pendidikan'];
    $bidang_keilmuan = $row['bidang_keilmuan'];
    
    // Masukkan foto
    $foto_path = 'uploads/' . $row['foto_dosen']; // Ganti 'foto_dosen' dengan nama kolom yang sesuai
    $img_width = 0;
    $img_height = 0;
    
    if (file_exists($foto_path)) {
        // Dapatkan ukuran gambar
        list($width, $height) = getimagesize($foto_path);
        
        // Ukuran maksimum untuk foto di PDF
        $max_width = 35; // Lebar maksimum gambar
        $max_height = 25; // Tinggi maksimum gambar

        // Hitung rasio gambar
        $ratio = $width / $height;
        
        // Sesuaikan ukuran gambar dengan batas maksimum
        if ($width > $max_width || $height > $max_height) {
            if ($ratio > 1) {
                // Lebar lebih besar dari tinggi
                $img_width = $max_width;
                $img_height = $max_width / $ratio;
            } else {
                // Tinggi lebih besar dari lebar
                $img_height = $max_height;
                $img_width = $max_height * $ratio;
            }
        } else {
            // Gunakan ukuran asli
            $img_width = $width;
            $img_height = $height;
        }

        // Buat sel untuk foto dengan ukuran yang disesuaikan
        $photo_cell_height = max(30, $img_height); // Tinggi sel berdasarkan tinggi gambar dan sedikit lebih panjang

        // Menampilkan data dalam sel
        $pdf->Cell(15, $photo_cell_height, $nidn, 1);
        $pdf->Cell(30, $photo_cell_height, $nama_dosen, 1);
        $pdf->Cell(30, $photo_cell_height, $tgl_mulai_tugas, 1);
        $pdf->Cell(20, $photo_cell_height, $jenjang_pendidikan, 1);
        $pdf->Cell(30, $photo_cell_height, $bidang_keilmuan, 1);
        
        // Sel untuk foto
        $pdf->Cell(40, $photo_cell_height, '', 1); // Buat sel untuk foto

        // Menempatkan gambar di tengah sel
        $xPos = $pdf->GetX() - 40 + (40 - $img_width) / 2; // Geser posisi X ke dalam sel foto dan pusatkan
        $yPos = $pdf->GetY() + (max(30, $img_height) - $img_height) / 2; // Menyelaraskan gambar vertikal
        $pdf->Image($foto_path, $xPos, $yPos, $img_width, $img_height); // Tempatkan gambar
    } else {
        $photo_cell_height = 25; // Tinggi sel ketika tidak ada gambar
        $pdf->Cell(15, $photo_cell_height, $nidn, 1);
        $pdf->Cell(30, $photo_cell_height, $nama_dosen, 1);
        $pdf->Cell(30, $photo_cell_height, $tgl_mulai_tugas, 1);
        $pdf->Cell(20, $photo_cell_height, $jenjang_pendidikan, 1);
        $pdf->Cell(30, $photo_cell_height, $bidang_keilmuan, 1);
        $pdf->Cell(40, $photo_cell_height, 'Tidak Ada', 1); // Jika tidak ada foto
    }
    
    $pdf->Ln($photo_cell_height); // Pindah ke baris berikutnya, tinggi diatur
}

// Output PDF
$pdf->Output();
mysqli_close($conn);
?>
