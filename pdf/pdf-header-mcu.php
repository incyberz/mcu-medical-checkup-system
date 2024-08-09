<?php
# ===========================================
# HEADER MCU
# ===========================================
$pdf->AddPage(); // header always in new page
$pdf->SetFont(FF, 'B', 12);
$pdf->Cell(0, 10, 'HASIL MEDICAL CHECKUP', 0, 1, 'C');
$pdf->Cell(0, 2, ' ', 'B', 1, 'C'); //border bottom

include 'pdf-header.php';
