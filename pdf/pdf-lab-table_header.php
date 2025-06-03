<?php
$koloms = [
  'PEMERIKSAAN' => 60, // width 60%
  'HASIL' => 20,
  'H/L' => 20,
  'SATUAN' => 30,
  'NILAI NORMAL' => 60
];

$pdf->SetFont(FF, 'B', 6);
$pdf->SetTextColor(100, 100, 100);
foreach ($koloms as $k => $w) {
  $ln = $k == 'NILAI NORMAL' ? $ln1 : $ln0;
  $pdf->Cell($w, LHB, $k, 'B', $ln, 'L');
}
$pdf->Cell(0, 1,  ' ', '-', $ln1, 'L'); // spacer
