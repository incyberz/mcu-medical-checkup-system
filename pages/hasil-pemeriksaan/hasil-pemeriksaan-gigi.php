<?php
$hasil_form = '<div>ARRAY GIGI</div>';

$arr_gigi = explode(',', $mcu['array_gigi']);
$z = 0;
$gigi_kanan_atas = '';
$gigi_kanan_bawah = '';
$gigi_kiri_atas = '';
$gigi_kiri_bawah = '';
foreach ($arr_gigi as $gigi_ke => $status_gigi) {
  if (!$status_gigi) continue;

  // gradasi
  $gradasi = 'bg-white';
  if ($status_gigi == -1) {
    $gradasi = 'gradasi-kuning';
  } elseif ($status_gigi == -2) {
    $gradasi = 'gradasi-merah';
  }

  //border-notif
  $border = $status_gigi == 1 ? '' : "style='border: solid 2px red'";

  if ($gigi_ke >= 24) {
    $gigi_kanan_bawah .= "<div class='status-gigi br50 bordered p1 $gradasi' $border>$status_gigi</div>";
  } elseif ($gigi_ke >= 16) {
    $gigi_kiri_bawah .= "<div class='status-gigi br50 bordered p1 $gradasi' $border>$status_gigi</div>";
  } elseif ($gigi_ke >= 8) {
    $gigi_kanan_atas .= "<div class='status-gigi br50 bordered p1 $gradasi' $border>$status_gigi</div>";
  } else {
    $gigi_kiri_atas .= "<div class='status-gigi br50 bordered p1 $gradasi' $border>$status_gigi</div>";
  }
}

$hasil_form = "
  <div class=row>
    <div class='col-6'><div class='m1 bordered br5 p2 flexy flex-between'>$gigi_kiri_atas</div></div>
    <div class='col-6'><div class='m1 bordered br5 p2 flexy flex-between'>$gigi_kanan_atas</div></div>
    <div class='col-6'><div class='m1 bordered br5 p2 flexy flex-between'>$gigi_kiri_bawah</div></div>
    <div class='col-6'><div class='m1 bordered br5 p2 flexy flex-between'>$gigi_kanan_bawah</div></div>
  </div>
";

$hasil_form = "<tr><td>$hasil_form</td></tr>";
