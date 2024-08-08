<?php
if ($pasien['hasil']) {
  $pdf->Cell(0, LHB, "KESIMPULAN: ", 'TLRB', 1, 'C');
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->SetTextColor(0, 150, 0);
  $pdf->Cell(0, LHB * 2,  $arr_kesimpulan[$pasien['hasil']], 'TLRB', 1, 'C');
} elseif ($pasien['hasil'] === 0 || $pasien['hasil'] === '0') {
  $pdf->Cell(0, LHB, "KESIMPULAN: ", 'TLRB', 1, 'C');
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->SetTextColor(200, 0, 0);
  $pdf->Cell(0, LHB * 2,  $arr_kesimpulan[$pasien['hasil']], 'TLRB', 1, 'C');
} else {
  $pdf->SetTextColor(255, 0, 0);
  $pdf->Cell(0, LHB, "KESIMPULAN: --belum disimpulkan--", 'TLRB', 1);
}

if (in_array('kesimpulan', $arr_page_at)) {
  // echo "<br>kesimpulan ----------------";
  $pdf->SetY(273);
  $pdf->SetFont('Arial', '', 8);
  $pdf->SetTextColor(0, 0, 0);
  foreach ($arr_page_at as $k3 => $v3) {
    if ($v3 == 'kesimpulan') {
      $page = $k3 + 1;
      break;
    }
  }
  $pdf->Cell(
    0,
    3,
    "MCU-$id_pasien --- Page $page of $total_page",
    $border_debug,
    0,
    'C'
  );
}
