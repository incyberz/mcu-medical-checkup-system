<?php
$wg = 7; // width kolom gigi
// $pdf->Cell(0, LHB, "PEMERIKSAAN GIGI:", 1, 1);
// $pdf->Cell(0, LH, ' ', 'LR', 1);
# ============================================================
# PROCESSING PEMERIKSAAN GIGI
# ============================================================
// $array_gigi_default = '1,2,3,4,5,6,7,8,8,7,6,5,4,3,2,1,1,2,3,4,5,6,7,8,8,7,6,5,4,3,2,1,'; //zzz debug
$g = explode(',', $arr_id_detail[94] ?? $array_gigi_default);

$grup = 1;
$NG = 9; // nomor gigi
$wg_left = $wg; // width gigi left
for ($baris = 1; $baris <= 7; $baris++) {
  foreach ($g as $k => $kode_gigi) {
    if (!$kode_gigi) continue;

    if ($baris < 3 || $baris > 5) {
      // $pdf->SetFillColor(200, 200, 200);
    }

    if ($baris == 1) {
      if ($k < 16) {
        $h = LH;
        if ($k == 0) {
          // $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C', false); // spacer left
        } elseif ($k == 8) {
          $grup++;
          // $pdf->Cell($wg, $h, '---', 'TLR', 0, 'C', true);
        }
        // $pdf->Cell($wg, $h, $grup, 'TLR', 0, 'C', true);
        if ($k == 15) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(60, $h, 'Keterangan:', 'R', 1, 'L', false);
        }
      }
    } elseif ($baris == 2) { // baris nomor gigi
      if ($k < 16) {
        $h = LH;
        // if ($k == 0) $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C'); // spacer left
        // if ($k == 8) $pdf->Cell($wg, $h, '---', 'LRB', 0, 'C', true);
        if ($k < 8) {
          $increment = -1;
        } elseif ($k == 8) {
          $increment = 0;
        } else {
          $increment = 1;
        }
        $NG += $increment;
        // $pdf->Cell($wg, $h, "$NG", 'LRB', 0, 'C', true);
        if ($k == 15) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(5, $h, 'O', '', 0, 'L', false);
          // $pdf->Cell(55, $h, 'Caries', 'R', 1, 'L', false);
        }
      }
    } elseif ($baris == 3) { // baris gigi atas
      if ($k < 16) {
        $h = 6;
        // if ($k == 0) $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C'); // spacer left
        // if ($k == 8) $pdf->Cell($wg, $h, '---', 'LRB', 0, 'C', true);
        $simbol = $kode_gigi == 1 ? ' ' : simbol_gigi($kode_gigi);
        // $pdf->Cell($wg, $h, $simbol, 'LRB', 0, 'C');
        if ($k == 15) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(5, $h, '@', '', 0, 'L', false);
          // $pdf->Cell(55, $h, 'Tambalan', 'R', 1, 'L', false);
        }
      }
    } elseif ($baris == 4) { // pembatas baris gigi atas/bawah
      if ($k < 16) {
        $h = LH;
        // if ($k == 0) $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C'); // spacer left
        // if ($k == 8) $pdf->Cell($wg, $h, '---', 'LRB', 0, 'C', true);
        // $pdf->Cell($wg, $h, '---', 'LRB', 0, 'C', true);
        if ($k == 15) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(5, $h, 'X', '', 0, 'L', false);
          // $pdf->Cell(55, $h, 'Gigi Sudah tidak ada', 'R', 1, 'L', false);
        }
      }
    } elseif ($baris == 5) { // baris gigi bawah
      if ($k > 15) {
        $h = 6;
        // if ($k == 16) $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C'); // spacer left
        // if ($k == 24) $pdf->Cell($wg, $h, '---', 'LRB', 0, 'C', true);
        $simbol = $kode_gigi == 1 ? ' ' : simbol_gigi($kode_gigi);
        // $pdf->Cell($wg, $h, $simbol, 'LRB', 0, 'C');
        if ($k == 31) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(5, $h, 'H', '', 0, 'L', false);
          // $pdf->Cell(55, $h, 'Gigi belum tumbuh', 'R', 1, 'L', false);
        }
      }
    } elseif ($baris == 6) { // baris nomor gigi
      if ($k > 15) {
        $h = LH;
        if ($k == 16) {
          $grup += 2;
          // $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C', false); // spacer left
        } elseif ($k == 24) {
          $grup--;
          // $pdf->Cell($wg, $h, '---', 'TLR', 0, 'C', true);
        }
        // $pdf->Cell($wg, $h, $grup, 'TLR', 0, 'C', true);
        if ($k == 31) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(5, $h, 'E', '', 0, 'L', false);
          // $pdf->Cell(55, $h, 'Gigi goyang', 'R', 1, 'L', false);
        }
      }
    } elseif ($baris == 7) {
      if ($k > 15) {
        $h = LH;
        if ($k == 16) {
          $NG++;
          // $pdf->Cell($wg_left, $h, ' ', 'L', 0, 'C'); // spacer left
        }
        // if ($k == 24) $pdf->Cell($wg, $h, '---', 'LRB', 0, 'C', true);
        if ($k < 24) {
          $increment = -1;
        } elseif ($k == 24) {
          $increment = 0;
        } else {
          $increment = 1;
        }
        $NG += $increment;
        // $pdf->Cell($wg, $h, "$NG", 'LRB', 0, 'C', true);
        if ($k == 31) {
          // $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
          // $pdf->Cell(5, $h, '^', '', 0, 'L', false);
          // $pdf->Cell(55, $h, 'Calculus', 'R', 1, 'L', false);
        }
      }
    }
  } // end foreach array gigi
} // end foreach baris

// $pdf->Cell($wg, LH, ' ', 'L', 0, 'C');
// $pdf->Cell(119, LH, " ", '', 0, 'L');

// $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
// $pdf->Cell(5, $h, 'v', '', 0, 'L', false);
// $pdf->Cell(55, $h, 'Radix', 'R', 1, 'L', false);



# ============================================================
# PENCARIAN GIGI ABNORMAL
# ============================================================
$rg = [];
for ($i = 0; $i < 32; $i++) {
  if ($i < 16) {
    $rg['atas'][$i] = $g[$i];
  } else {
    $rg['bawah'][$i] = $g[$i];
  }
}

$arr = ['atas', 'bawah'];
$i = 0;
$j = 1;
$gigi_rusak = [];
foreach ($arr as $posisi) {
  $td[$posisi] = '';
  $nn[$posisi] = '';
  $pos = 8;
  $increment = -1;
  foreach ($rg[$posisi] as $key => $value) {
    $value = $value < 0 ? abs($value) : $value;
    $i++;
    $NN = 1;
    if ($i > 8) $NN = 2;
    if ($i > 16) $NN = 4;
    if ($i > 24) $NN = 3;

    # ============================================================
    # PUSH GIGI RUSAK
    # ============================================================
    if ($value != 1) $gigi_rusak["$NN-$pos"] = arti_simbol_gigi($value);

    if ($i > 24) {
      $pos++;
    } elseif ($i > 16) {
      if ($pos > 1) $pos--;
    } elseif ($i > 8) {
      $pos++;
    } elseif ($i > 0) {
      if ($pos > 1) $pos--;
    }
  }
}

$normal = $gigi_rusak ? '' : 'dalam batas normal';

// $pdf->Cell($wg, LH, ' ', 'L', 0, 'C');
// $pdf->Cell(119, LH, "Kesimpulan: $normal", '', 0, 'L');

// $pdf->Cell(4, $h, ' ', '', 0, 'L', false);
// $pdf->Cell(5, $h, '.', '', 0, 'L', false);
// $pdf->Cell(55, $h, '(tidak bertanda artinya normal)', 'R', 1, 'L', false);

$info_gigi = 'dalam batas normal';
if ($gigi_rusak) {
  $info_gigi = '';
  foreach ($gigi_rusak as $k => $v) {
    // $pdf->Cell($wg, LH, ' ', 'L', 0, 'C');
    // $pdf->Cell(183, LH, "Gigi $k: $v ", 'R', 1, 'L');
    $info_gigi = $info_gigi ? ", Gigi $k: $v" : "Gigi $k: $v";
  }
}

// $pdf->Cell(0, 2, ' ', 'LRB', 1, 'L');

$pasien['kesimpulan_pemeriksaan_fisik']['GIGI'] = $info_gigi;
