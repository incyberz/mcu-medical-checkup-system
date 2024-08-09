<?php
# ============================================================
# PROCESSING PEMERIKSAAN FISIK DOKTER
# ============================================================
$width = [27, 15, 148];
$pdf->SetFont(FF, 'B', 10);
$pdf->Cell(0, LH * 2, "PEMERIKSAAN FISIK DOKTER", 0, 1, 'L');
$abnormal_count = 0;
// $pdf->Cell(0, LH, ' ', 'LR', 1);

# ============================================================
# HEADER
# ============================================================
$pdf->SetFont(FF, 'B', FS);
$pdf->SetFillColor(200, 255, 255);
$pdf->Cell($width[0], LHB, 'BAGIAN', 'TLRB', 0, 'L', true);
$pdf->Cell($width[1], LHB, 'NORMAL', 'TLRB', 0, 'L', true);
$pdf->Cell($width[2], LHB, 'DESKRIPSI DAN KELAINAN', 'TLRB', 1, 'L', true);
$pdf->SetFont(FF, '', FS);
// $pdf->SetFillColor(200, 255, 255);

$s = "SELECT * FROM tb_bagian_tubuh ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
while ($d = mysqli_fetch_assoc($q)) {
  $s2 = "SELECT * FROM tb_pemeriksaan_detail WHERE bagian='$d[bagian]' AND id_pemeriksaan=8";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  $str_hasil = '';
  $str_kelainan = '';
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $id_detail = $d2['id'];
    $nama_pemeriksaan = trim($d2['label']);
    $option_default = strtolower($arr_pemeriksaan_detail[$id_detail]['option_default']);

    if ($option_default) {
      if ($option_default == $arr_id_detail[$id_detail]) {
        $koma = $str_hasil ? '; ' : ' ';
        $str_hasil .= "$koma$nama_pemeriksaan: $arr_id_detail[$id_detail]";
      } else { // opsi default berbeda dg hasil
        $koma = $str_kelainan ? '; ' : ' ';
        $str_kelainan .= "$koma$nama_pemeriksaan: $arr_id_detail[$id_detail]";
      }
    } else { // tanpa opsi default
      $koma = $str_hasil ? '; ' : ' ';
      $str_hasil .= "$koma$nama_pemeriksaan: $arr_id_detail[$id_detail]";
    }
  }

  $str_hasil2 = '';
  $len = strlen($str_hasil);
  if ($len > 110) {
    $pos = strpos($str_hasil, ';', 90); // cari titik koma di posisi 90
    $str_hasil_trim = substr($str_hasil, 0, $pos);
    $str_hasil2 = substr($str_hasil, $pos + 2, $len); // titik koma dan spasi dibuang
    // echo "<br>pos: $pos | str_hasil2:$str_hasil2 | str_hasil_trim:$str_hasil_trim ";
  }


  $pdf->SetTextColor(0, 0, 0);
  $NORMAL = $str_kelainan ? '' : 'NORMAL';
  $h = LH * 1.2; // line height table
  $str_hasil = $str_hasil ? $str_hasil : '-';

  if (!$str_hasil2) {
    $pdf->SetFont(FF, 'B', FS);
    $pdf->Cell($width[0], $h, $d['bagian'], 'TLR', 0, 'L', false);

    $pdf->SetFont(FF, '', FS);
    $pdf->SetTextColor(0, 200, 0);
    $pdf->Cell($width[1], $h, "$NORMAL", 'TLR', 0, 'L', false);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($width[2], $h, trim($str_hasil), 'TLR', 1, 'L', false);
  } else { // multi string
    $len = strlen($str_hasil2);
    $str_hasil3 = '';
    if ($len > 110) {
      // echo "<br>str_hasil2:$str_hasil2 ";
      $pos = strpos($str_hasil2, ';', 90); // cari titik koma di posisi 90
      $str_hasil_trim2 = substr($str_hasil2, 0, $pos);
      $str_hasil3 = substr($str_hasil2, $pos + 2, $len); // titik koma dan spasi dibuang
      // echo "<br>pos: $pos | str_hasil3: $str_hasil3 | str_hasil_trim2:$str_hasil_trim2 ";
    }

    if (!$str_hasil3) {
      $pdf->SetFont(FF, 'B', FS);
      $pdf->Cell($width[0], $h, $d['bagian'], 'TLR', 0, 'L', false);

      $pdf->SetFont(FF, '', FS);
      $pdf->SetTextColor(0, 200, 0);
      $pdf->Cell($width[1], $h, "$NORMAL", 'TLR', 0, 'L', false);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Cell($width[2], $h, trim($str_hasil_trim), 'TLR', 1, 'L', false);

      $pdf->Cell($width[0], $h,  ' ', 'LR', 0, 'L', false);
      $pdf->Cell($width[1], $h, ' ', 'LR', 0, 'L', false);
      $pdf->Cell($width[2], $h,  trim($str_hasil2), 'LR', 1, 'L', false);
    } else { // terdapat 3 baris konten normal 
      $pdf->SetFont(FF, 'B', FS);
      $pdf->Cell($width[0], $h, $d['bagian'], 'TLR', 0, 'L', false);

      $pdf->SetFont(FF, '', FS);
      $pdf->SetTextColor(0, 200, 0);
      $pdf->Cell($width[1], $h, "$NORMAL", 'TLR', 0, 'L', false);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Cell($width[2], $h, trim($str_hasil_trim), 'TLR', 1, 'L', false);

      $pdf->Cell($width[0], $h,  ' ', 'LR', 0, 'L', false);
      $pdf->Cell($width[1], $h, ' ', 'LR', 0, 'L', false);
      $pdf->Cell($width[2], $h,  trim($str_hasil_trim2), 'LR', 1, 'L', false);

      $pdf->Cell($width[0], $h,  ' ', 'LR', 0, 'L', false);
      $pdf->Cell($width[1], $h, ' ', 'LR', 0, 'L', false);
      $pdf->Cell($width[2], $h,  trim($str_hasil3), 'LR', 1, 'L', false);
    }
  }

  if ($str_kelainan) {
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetFont(FF, 'B', FS);
    $pdf->Cell($width[0], $h,  ' ', 'LR', 0, 'L', false);
    $pdf->Cell($width[1], $h, ' ', 'LR', 0, 'L', false);
    $pdf->Cell($width[2], $h, 'Kelainan: ' . trim($str_kelainan), 'LR', 1, 'L', false);
  }
}








$pdf->Cell($width[0], 2,  ' ', 'LRB', 0, 'L', false);
$pdf->Cell($width[1], 2, ' ', 'LRB', 0, 'L', false);
$pdf->Cell($width[2], 2,  ' ', 'LRB', 1, 'L', false);
