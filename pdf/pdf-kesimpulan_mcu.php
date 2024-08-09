<?php
if ($pasien['hasil']) {
  $pdf->Cell(0, LHB, "KESIMPULAN: ", 'TLRB', 1, 'C');
  $pdf->SetFont(FF, 'B', 16);
  $pdf->SetTextColor(0, 150, 0);
  $pdf->Cell(0, LHB * 2,  $arr_kesimpulan[$pasien['hasil']], 'TLRB', 1, 'C');
} elseif ($pasien['hasil'] === 0 || $pasien['hasil'] === '0') {
  $pdf->Cell(0, LHB, "KESIMPULAN: ", 'TLRB', 1, 'C');
  $pdf->SetFont(FF, 'B', 16);
  $pdf->SetTextColor(200, 0, 0);
  $pdf->Cell(0, LHB * 2,  $arr_kesimpulan[$pasien['hasil']], 'TLRB', 1, 'C');
} else {
  $pdf->SetTextColor(255, 0, 0);
  $pdf->Cell(0, LHB, "KESIMPULAN: --belum disimpulkan--", 'TLRB', 1);
}



# ============================================================
# PRINTED AT 
# ============================================================
$pdf->SetFont(FF, '', FS);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 2, ' ', '-', $ln1, 'L'); // spacer
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');
$pdf->Cell(0, LH, 'Printed at: Bekasi, ' . hari_tanggal('', 1, 0), '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');
$pdf->Cell(0, LH, 'Dokter Pemeriksa: dr. Mutiara Putri Camelia ', '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');


# ============================================================
# QR 
# ============================================================
if ($id_perusahaan) {
  $src = "qr/$id_perusahaan.jpg";
  if (file_exists($src)) {
    $pdf->Cell(
      0,
      LH,
      // $pdf->Image("qr/$id_perusahaan", $x, $y, $w, $h, '', 'link'),
      $pdf->Image($src, null, null, 20, null, '', 'link'),
      '-',
      $ln1,
      'L'
    );
  } else {
    die("File QR tidak ditemukan<hr>src: $src");
  }
}


# ============================================================
# PAGE AT
# ============================================================
if (in_array('kesimpulan', $arr_page_at)) {
  // echo "<br>kesimpulan ----------------";
  $pdf->SetY(273);
  $pdf->SetFont(FF, '', FS);
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
