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

$COR_STATUS = 'Jantung Tidak Membesar ( CTR < 50% )';
$AORTA_STATUS = 'Normal';
$PULMO_STATUS = 'Tidak Tampak Infiltrat / Lesi Pada Kedua Paru. Corakan Bronchovasculer Normal. Kedua Hemidiafragma Licin. -- Sinus Kostoferenikus Kanan-Kiri Lancip. Tulang-Tulang Dan Soft Tissue Normal';
$PULMO_STATUS = 'Normal'; // ambil cepat
$PULMO_STATUS = 'Tidak Tampak Infiltrat / Lesi Pada Kedua Paru. Corakan Bronchovasculer Normal. Kedua Hemidiafragma Licin.';
$KESAN_STATUS = 'Dalam batas normal';

# ============================================================
# HASIL RONTGEN PROCESSOR
# ============================================================
$ID_DETAIL_RONTGEN = 134;
$str_hasil = $arr_id_detail[$ID_DETAIL_RONTGEN];
// $str_hasil = 'normal'; // ZZZ DEBUG

$arr_default_rontgen['COR'] = 'Jantung Tidak Membesar ( CTR < 50% )';
$arr_default_rontgen['AORTA'] = 'Normal';
$arr_default_rontgen['PULMO'] = 'Tidak Tampak Infiltrat / Lesi Pada Kedua Paru. Corakan Bronchovasculer Normal. Kedua Hemidiafragma Licin. -- Sinus Kostoferenikus Kanan-Kiri Lancip. Tulang-Tulang Dan Soft Tissue Normal';
$arr_default_rontgen['PULMO'] = 'Normal'; // ambil cepat
$arr_default_rontgen['PULMO'] = 'Tidak Tampak Infiltrat / Lesi Pada Kedua Paru. Corakan Bronchovasculer Normal. Kedua Hemidiafragma Licin.';
$arr_default_rontgen['KESAN'] = 'Dalam batas normal';

$str = strtolower(trim($str_hasil));
if ($str == 'dalam batas normal' || $str == 'normal') {
  $red = 'green';
} else {
  $red = 'red';
  $tmp = explode('kesan_tambahan: ', $str_hasil);
  $kesan_tambahan = $tmp[1] ?? null;
  $str_hasil2 = $tmp[0];

  $arr = explode(', ', $str_hasil2);

  $abnor['COR'] = '';
  $abnor['AORTA'] = '';
  $abnor['PULMO'] = [];
  foreach ($arr as $key => $value) {
    $awalan = strtolower(substr($value, 0, 5));
    if ($awalan == 'jantu') {
      $abnor['COR'] .= $value;
    } elseif ($awalan == 'aorta') {
      $abnor['AORTA'] .= $value;
    } elseif ($awalan == 'pulmo') {
      $tmp = explode(' > ', $value);
      if ($tmp[1]) array_push($abnor['PULMO'], $tmp[1]);
    }
  }


  $KESAN_STATUS = $kesan_tambahan ?? 'Terdapat Kelainan Paru';

  $COR_STATUS = $abnor['COR'] ? $abnor['COR'] : $arr_default_rontgen['COR'];
  $AORTA_STATUS = $abnor['AORTA'] ? $abnor['AORTA'] : $arr_default_rontgen['AORTA'];
  $PULMO_STATUS = $abnor['PULMO'] ? $abnor['PULMO'] : $arr_default_rontgen['PULMO'];
}



# ============================================================
# CREATE UI PDF
# ============================================================
$koloms = [
  'COR' => $COR_STATUS,
  'AORTA' => $AORTA_STATUS,
  'PULMO' => $PULMO_STATUS,
  'KESAN' => $KESAN_STATUS,
];
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
$pdf->Cell(0, LH, "Dokter Radiologi: $dokter_radiologi ", '-', $ln1, 'L');
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
