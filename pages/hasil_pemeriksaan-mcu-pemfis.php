<?php
$arr_id_detail_show = [
  'Tekanan Darah' => [7, 8],
  'Nadi' => 140,
  'Pernafasan' => 9,
  'Suhu' => 139,
  'Saturasi Oksigen' => 10,
  'Tinggi Badan' => 2,
  'Berat Badan' => 1,
  'IMT' => 'fx',
  'Status Gizi' => 'fx',
  'Lingkar Perut' => 6,
  'Status Lingkar Perut' => 6,
];
$li = '';
$imt = 0;
foreach ($arr_id_detail_show as $key => $id_detail) {
  $hasil = '';
  if ($key == 'Tekanan Darah') {
    $satuan = $arr_pemeriksaan_detail[$id_detail[0]]['satuan'];
    $sistol = $arr_id_detail[$id_detail[0]];
    $diastol = $arr_id_detail[$id_detail[1]];
    $hasil =  "$sistol/$diastol $satuan";

    $s = "SELECT * FROM tb_tekanan_darah";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $arr_tekanan_darah = [];
    while ($d = mysqli_fetch_assoc($q)) {
      // $id=$d['id'];
      $arr_tekanan_darah = $d;
      if ($d['usia_max'] > $pasien['usia']) break;
    }

    // echo '<pre>';
    // var_dump($arr_tekanan_darah);
    // echo '</pre>';
    //  ZZZ SKIPPED

  } elseif ($key == 'IMT') {
    $berat_badan = $arr_id_detail[1];
    $tinggi_badan = $arr_id_detail[2];
    $imt = round($berat_badan * 10000 / ($tinggi_badan * $tinggi_badan), 2);
    $hasil = $imt;
  } elseif ($key == 'Status Gizi') {
    $batas_imt = [
      18.5 => 'Underweight',
      25 => 'Normal range',
      30 => 'Overweight',
      35 => 'Obese class 1',
      40 => 'Obese class 2',
      999 => 'Obese class 3',
    ];
    foreach ($batas_imt as $batas => $status_gizi) {
      $hasil = $status_gizi;
      if ($batas > $imt) break;
    }
  } elseif ($key == 'Status Lingkar Perut') {
    $lingkar_perut = $arr_id_detail[6];
    if (strtolower($pasien['gender']) == 'l') {
      $hasil = $lingkar_perut > 90 ? 'Beresiko' : 'Dalam batas aman';
    } else {
      $hasil = $lingkar_perut > 80 ? 'Beresiko' : 'Dalam batas aman';
    }
  } else {
    $satuan = $arr_pemeriksaan_detail[$id_detail]['satuan'];
    $hasil = "$arr_id_detail[$id_detail] $satuan";
  }
  $li .= "<li><span class=column>$key:</span> <span class=hasil>$hasil</span></li>";
}
$str_hasil = $li ? "<ul class='m0'>$li</ul>" : $tidak_ada;
blok_hasil('PEMERIKSAAN FISIK', $str_hasil);
