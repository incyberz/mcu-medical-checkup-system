<?php
# ===========================================
# HEADER
# ===========================================
$pdf->AddPage(); // header always in new page
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'HASIL URINE LENGKAP', 0, 1, 'C');
$pdf->Cell(0, 2, ' ', 'B', 1, 'C'); //border bottom

include 'pdf-header.php';

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 200, 200);
$pdf->Cell(0, LHB, 'U R I N A L I S A ', '-', 1, 'L');
$pdf->Cell(0, LHB, 'Makroskopik ', '-', 1, 'L');
