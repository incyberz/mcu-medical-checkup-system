<?php
# ===========================================
# HEADER MCU
# ===========================================
$pdf->AddPage(); // header always in new page
$pdf->SetFont(FF, 'B', 16);
$pdf->Cell(0, 30, ' ', 0, 1, 'C'); // spacer
$pdf->Cell(0, 10, 'HASIL MEDICAL CHECKUP', 0, 1, 'C');
$pdf->Cell(0, 10, 'TAHUN 2024', 0, 1, 'C');


$ttl = '-';
if ($pasien['tanggal_lahir']) $ttl = hari_tanggal($pasien['tanggal_lahir'], 1, 0, 0);
if ($pasien['tempat_lahir']) $ttl = "$pasien[tempat_lahir], $ttl";

if (!$pasien['gender']) {
  die("Gender pasien masih kosong, nama [ $pasien[nama] ]");
} else {
  $gender = ucwords($pasien['gender']);
}

$departemen = $pasien['departemen'] ?? '-';
$alamat = $pasien['alamat'] ?? '-';
$nikepeg = $pasien['nikepeg'] ?? '-';
$tanggal_periksa = hari_tanggal($pasien['awal_periksa'], 1, 0, 0);

$arr = [
  'NO. MCU / NIK' => "MCU-$id_pasien / $nikepeg",
  'NAMA' => "$nama_pasien ($gender)",
  'DEPARTEMEN' => "$departemen",
  'TTL' => $ttl,
  'TGL PERIKSA' => "$tanggal_periksa",
  'ALAMAT' => "$alamat",
];

// echo '<pre>';
// var_dump($arr);
// echo '</pre>';

$h = LH * 1.3;
$pdf->SetFont(FF, 'B', 10);
$pdf->SetY(214);
foreach ($arr as $key => $value) {
  $pdf->SetX(55);
  $pdf->Cell(30, $h, $key, '', $ln0, 'L');
  $pdf->Cell(5, $h, ':', '', $ln0, 'L');
  $pdf->Cell(70, $h, $value, '', $ln1, 'L');
}
