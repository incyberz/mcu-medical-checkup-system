<?php
if ($id_perusahaan) {
  $src = "qr/$id_perusahaan.jpg";
  $src2 = "qr/qr_mmc.jpg";
  if (file_exists($src)) {
  } elseif (file_exists($src2)) {
    $src = $src2;
  } else {
    die("File QR tidak ditemukan<hr>src: $src");
  }

  $pdf->Cell(
    0,
    LH,
    // $pdf->Image("qr/$id_perusahaan", $x, $y, $w, $h, '', 'link'),
    $pdf->Image($src, null, null, 20, null, '', 'link'),
    '-',
    $ln1,
    'L'
  );
}
