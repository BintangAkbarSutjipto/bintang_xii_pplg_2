<?php
include('config.php');
require 'vendor/autoload.php'; // Pastikan Anda telah menginstall PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing; // Tambahkan namespace untuk Drawing

// Buat Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set judul kolom
$sheet->setCellValue('A1', 'NIDN');
$sheet->setCellValue('B1', 'Nama Dosen');
$sheet->setCellValue('C1', 'Tanggal Mulai Tugas');
$sheet->setCellValue('D1', 'Jenjang Pendidikan');
$sheet->setCellValue('E1', 'Bidang Keilmuan');
$sheet->setCellValue('F1', 'Foto'); // Tambahkan kolom untuk foto

// Query untuk mengambil data dosen
$query = "SELECT * FROM users ORDER BY nidn ASC";
$result = mysqli_query($conn, $query);

// Memasukkan data ke dalam sheet
$rowNumber = 2; // Mulai dari baris kedua karena baris pertama untuk header
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['nidn']);
    $sheet->setCellValue('B' . $rowNumber, $row['nama_dosen']);
    $sheet->setCellValue('C' . $rowNumber, $row['tgl_mulai_tugas']);
    $sheet->setCellValue('D' . $rowNumber, $row['jenjang_pendidikan']);
    $sheet->setCellValue('E' . $rowNumber, $row['bidang_keilmuan']);

    // Masukkan foto
    $foto_path = 'uploads/' . $row['foto_dosen']; // Path ke foto dosen
    if (file_exists($foto_path)) {
        // Tambahkan gambar ke sheet
        $drawing = new Drawing();
        $drawing->setName('Foto Dosen');
        $drawing->setDescription('Foto Dosen');
        $drawing->setPath($foto_path);
        $drawing->setHeight(50); // Atur tinggi gambar
        $drawing->setCoordinates('F' . $rowNumber); // Set koordinat kolom F
        $drawing->setWorksheet($sheet); // Tempatkan gambar ke dalam worksheet

        // Mengatur tinggi baris agar sesuai dengan foto
        $sheet->getRowDimension($rowNumber)->setRowHeight(50); // Sesuaikan tinggi baris

        // Mengatur lebar kolom untuk foto
        $sheet->getColumnDimension('F')->setWidth(20); // Sesuaikan lebar kolom dengan ukuran foto
    } else {
        // Jika tidak ada foto, isi dengan teks 'Tidak Ada'
        $sheet->setCellValue('F' . $rowNumber, 'Tidak Ada');
        $sheet->getRowDimension($rowNumber)->setRowHeight(15); // Tinggi baris default

        // Mengatur lebar kolom untuk foto
        $sheet->getColumnDimension('F')->setWidth(20); // Lebar kolom untuk teks 'Tidak Ada'
    }

    $rowNumber++;
}

// Mengatur lebar kolom agar lebih rapi
$sheet->getColumnDimension('A')->setWidth(15); // Lebar kolom NIDN
$sheet->getColumnDimension('B')->setWidth(25); // Lebar kolom Nama Dosen
$sheet->getColumnDimension('C')->setWidth(20); // Lebar kolom Tanggal Mulai Tugas
$sheet->getColumnDimension('D')->setWidth(20); // Lebar kolom Jenjang Pendidikan
$sheet->getColumnDimension('E')->setWidth(25); // Lebar kolom Bidang Keilmuan
$sheet->getColumnDimension('F')->setWidth(20); // Lebar kolom Foto

// Menulis file Excel ke output
$writer = new Xlsx($spreadsheet);
$filename = 'data_dosen.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>
