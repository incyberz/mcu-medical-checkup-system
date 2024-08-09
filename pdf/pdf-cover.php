<?php
# ===========================================
# HEADER MCU
# ===========================================
$pdf->AddPage(); // header always in new page
$pdf->SetFont(FF, 'B', 12);
$pdf->Cell(0, 10, 'HASIL MEDICAL CHECKUP', 0, 1, 'C');
$pdf->Cell(0, 10, 'TAHUN 2024', 0, 1, 'C');

$pdf->SetY(210);

$arr = [
  'NO. MCU / NIK' => "MCU-$id_pasien / $nikepeg",
  'NAMA' => "$nama_pasien",
  'DEPARTEMEN' => "$ZZZ",
  'TTL' => "$ZZZ",
  'GENDER' => "$ZZZ",
  'ALAMAT' => "$ZZZ",
];

$pdf->SetX(50);
