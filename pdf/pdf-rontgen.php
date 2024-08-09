<?php
$id_pemeriksaan = 9;
$nama_pemeriksaan = 'RONTGEN THORAX';
# ===========================================
# HEADER
# ===========================================
$pdf->AddPage(); // header always in new page
$pdf->SetFont(FF, 'B', 12);
$pdf->Cell(0, 10, "HASIL $nama_pemeriksaan", 0, 1, 'C');
$pdf->Cell(0, 2, ' ', 'B', 1, 'C'); //border bottom

include 'pdf-header.php';

$pdf->SetFont(FF, '', FS);

$widths = [15, 5, 170];

$koloms = [
  'COR' => [':', 'Jantung Tidak Membesar ( CTR < 50% )'],
  'AORTA' => [':', 'Normal'],
  'PULMO' => [':', 'Tidak Tampak Infiltrat / Lesi Pada Kedua Paru. Corakan Bronchovasculer Normal. Kedua Hemidiafragma Licin.'],
  ' ' => [' ', 'Sinus Kostoferenikus Kanan-Kiri Lancip. Tulang-Tulang Dan Soft Tissue Normal'],
  'KESAN' => [':', 'dalam batas normal'],
];
$h = LH * 1.9;
foreach ($koloms as $k => $v) {
  if (strlen($k) >= 3) {
    $pdf->Cell($widths[0], $h, $k, 'T', $ln0, 'L');
    $pdf->Cell($widths[1], $h, $v[0], 'T', $ln0, 'L');
    if ($k == 'KESAN') {
      $pdf->SetFont(FF, 'B', 10);
      $pdf->SetTextColor(0, 200, 0);
    }
    $pdf->Cell($widths[2], $h, $v[1], 'T', $ln1, 'L');
  } else {
    $pdf->Cell($widths[0], LH, $k, '-', $ln0, 'L');
    $pdf->Cell($widths[1], LH, $v[0], '-', $ln0, 'L');
    $pdf->Cell($widths[2], LH, $v[1], '-', $ln1, 'L');
    $pdf->Cell(0, 2, ' ', '-', $ln1, 'L'); // spacer
  }
}

$pdf->Cell(0, 9, ' ', '-', $ln1, 'L'); // spacer



# ============================================================
# FOOTER
# ============================================================
$pdf->SetFont(FF, '', FS);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 2, ' ', 'B', $ln1, 'L');
$pdf->Cell(0, 2, ' ', '-', $ln1, 'L');
$pdf->Cell(0, LH, 'Catatan: ', '-', $ln1, 'L');
$pdf->Cell(0, LH, 'Hasil Ini harus di interpretasikan oleh dokter yang menangani untuk disesuaikan dengan klinisnya. ', '-', $ln1, 'L');
$pdf->Cell(0, 2, ' ', 'B', $ln1, 'L'); // border bottom

$pdf->Cell(0, 2, ' ', '-', $ln1, 'L'); // spacer
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');
$pdf->Cell(0, LH, 'Printed at: Bekasi, ' . hari_tanggal('', 1, 0), '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');
$pdf->Cell(0, LH, 'Dokter Pemeriksa: dr. Mutiara Putri Camelia ', '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');



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


// footer after keluhan
$k = 'rontgen';
if (in_array($k, $arr_page_at)) {
  // echo "<br>$k ----------------";
  $pdf->SetY(273);
  $pdf->SetFont(FF, '', FS);
  foreach ($arr_page_at as $k3 => $v3) {
    if ($v3 == $k) {
      $page = $k3 + 1;
      break;
    }
  }
  $pdf->Cell(0, 3, "MCU-$id_pasien --- Page $page of $total_page", $border_debug, 0, 'C');
}
