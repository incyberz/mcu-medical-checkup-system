<?php
$id_pemeriksaan = 5;
$nama_pemeriksaan = 'PEMERIKSAAN EKG (JANTUNG)';
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

$id_detail_pemeriksaan = 135;
if (isset($arr_id_detail[$id_detail_pemeriksaan])) {
  $EKG_STATUS = 'Normal Sinus';
  $SARAN_STATUS = 'Check-up 1 tahun sekali';

  # ============================================================
  # HASIL RONTGEN PROCESSOR
  # ============================================================
  $str_hasil = $arr_id_detail[$id_detail_pemeriksaan];

  $arr_default_rontgen['HASIL EKG'] = $EKG_STATUS;
  $arr_default_rontgen['SARAN'] = $SARAN_STATUS;

  $str = strtolower(trim($str_hasil));
  if ($str == 'dalam batas normal' || $str == 'normal') {
    $red = 'green';
  } else {
    $red = 'red';
    $tmp = explode('kesan_tambahan: ', $str_hasil);
    $kesan_tambahan = $tmp[1] ?? null;
    $str_hasil2 = $tmp[0];

    $arr = explode(', ', $str_hasil2);

    $abnor['HASIL EKG'] = '';
    foreach ($arr as $key => $value) {
      $awalan = strtolower(substr($value, 0, 5));
      if ($awalan == 'jantu') {
        $abnor['HASIL EKG'] .= $value;
      } elseif ($awalan == 'aorta') {
      } elseif ($awalan == 'pulmo') {
        $tmp = explode(' > ', $value);
      }
    }


    $SARAN_STATUS = $kesan_tambahan ?? 'Terdapat Kelainan Jantung';

    $EKG_STATUS = $abnor['HASIL EKG'] ? $abnor['HASIL EKG'] : $arr_default_rontgen['HASIL EKG'];
  }



  # ============================================================
  # CREATE UI PDF
  # ============================================================
  $koloms = [
    'HASIL EKG' => $EKG_STATUS,
    'SARAN' => $SARAN_STATUS,
  ];
} else {
  $koloms = [
    'HASIL EKG' => ['HASIL EKG' => '(no-test)'],
    'SARAN' => ['SARAN' => '(no-test)'],
  ];
}
$h = LH * 1.9;
foreach ($koloms as $k => $v) {
  if (!is_array($v) >= 3) {
    $pdf->Cell($widths[0], $h, $k, 'T', $ln0, 'L');
    $pdf->Cell($widths[1], $h, ':', 'T', $ln0, 'L');

    if (strtolower(trim($v)) != strtolower(trim($arr_default_rontgen[$k]))) {
      $pdf->SetTextColor(255, 0, 0); // red | abnormal
      // echo " $v != $arr_default_rontgen[$k] ";
    }
    $pdf->Cell($widths[2], $h, $v, 'T', $ln1, 'L');
    $pdf->SetTextColor(0, 0, 0);
  } else {
    $i = 0;
    foreach ($v as $k2 => $v2) {
      $i++;
      $titik_koma =  ' ';
      $kolom =  ' ';
      if ($i == 1) {
        $titik_koma =  ':';
        $kolom =  $k;
        $pdf->Cell(0, 2, ' ', 'T', $ln1, 'L'); // border top + spacer
      }
      $pdf->Cell($widths[0], LH, $kolom, '-', $ln0, 'L');
      $pdf->Cell($widths[1], LH, $titik_koma, '-', $ln0, 'L');
      $pdf->SetTextColor(255, 0, 0); // bentuk array pasti abnormal
      $pdf->Cell($widths[2], LH, " - $v2", '-', $ln1, 'L');
      $pdf->SetTextColor(0, 0, 0); // reset color
    }
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
$pdf->Cell(0, LH, "Spesialis Jantung: dr. Adolf Amahorseya, Sp. JP ", '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');



include 'pdf-qr_show.php';



// footer 
$k = 'ekg';
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
