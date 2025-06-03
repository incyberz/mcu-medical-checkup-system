<?php
$arr_id_detail_show = [
  'Tinggi Badan' => 2,
  'Berat Badan' => 1,
  'IMT' => 'fx',
  'Status Gizi' => 'fx',
  'Lingkar Perut' => 6,
  'Status Lingkar Perut' => 6,
  'Tes Buta Warna' => 11,
  'Tekanan Darah' => [7, 8],
  'Nadi' => 140,
  'Pernafasan' => 9,
  'Suhu' => 148,
  'Saturasi Oksigen' => 10,
];
$li = '';
$li2 = '';
$imt = 0;
$i = 0;
foreach ($arr_id_detail_show as $key => $id_detail) {
  $hasil = '';
  $i++;
  if ($key == 'Tekanan Darah') {
    $satuan = $arr_pemeriksaan_detail[$id_detail[0]]['satuan'];
    $sistol = $arr_id_detail[$id_detail[0]] ?? 0;
    $diastol = $arr_id_detail[$id_detail[1]] ?? 0;
    $hasil = ($sistol and $diastol) ? "$sistol/$diastol $satuan" : 'no-data';

    $arr = [
      'Hipotensi' => ['sistol' => 100, 'distol' => 60],
      'Normal' => ['sistol' => 129, 'distol' => 84],
      'Pre-Hipertensi' => ['sistol' => 148, 'distol' => 89],
      'Hipertensi derajat 1' => ['sistol' => 159, 'distol' => 99],
      'Hipertensi derajat 2' => ['sistol' => 179, 'distol' => 109],
      'Hipertensi derajat 3' => ['sistol' => 299, 'distol' => 199],
    ];

    foreach ($arr as $ket => $arr_value) {
      if ($sistol < $arr_value['sistol'] && $diastol < $arr_value['distol']) {
        $hasil .= ", $ket";
        break;
      }
    }

    if ($ket != 'Normal') {
      $kesimpulan['Tekanan Darah'] = $ket;
    }
  } elseif ($key == 'IMT') {
    $berat_badan = $arr_id_detail[1] ?? 0;
    $tinggi_badan = $arr_id_detail[2] ?? 0;
    $imt = ($berat_badan and $tinggi_badan) ? round($berat_badan * 10000 / ($tinggi_badan * $tinggi_badan), 2) : 0;
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
    $kesimpulan['Status Gizi'] = $hasil;
    $hasil = '';
  } elseif ($key == 'Status Lingkar Perut') {
    $lingkar_perut = $arr_id_detail[6] ?? 0;
    if ($lingkar_perut) {
      if (strtolower($pasien['gender']) == 'l') {
        $hasil = $lingkar_perut > 90 ? 'Beresiko' : 'Dalam batas aman';
      } else {
        $hasil = $lingkar_perut > 80 ? 'Beresiko' : 'Dalam batas aman';
      }
      $kesimpulan['Status Lingkar Perut'] = $hasil;
    } else {
      $hasil = 'no-data';
      $kesimpulan['Status Lingkar Perut'] = 'no-data';
    }
    $hasil = '';
  } elseif ($key == 'Tes Buta Warna') {
    $poin = $arr_id_detail[$id_detail] ?? 0;
    if ($poin) {
      if ($poin < 8) {
        if ($poin < 3) {
          $hasil = 'Buta warna total';
        } else {
          $hasil = 'Buta warna parsial';
        }
        $kesimpulan['Tes Buta Warna'] = $hasil;
      } else {
        $hasil = 'Tidak buta warna';
      }
    } else {
      $hasil = 'no-data';
      $kesimpulan['Tes Buta Warna'] = 'no-data';
    }
  } else {
    $satuan = $arr_pemeriksaan_detail[$id_detail]['satuan'];
    $hasil = isset($arr_id_detail[$id_detail]) ? "$arr_id_detail[$id_detail] $satuan" : 'no-data';
  }
  $c_li = !$hasil ? '' : "<li><span class=column>$key:</span> <span class=hasil>$hasil</span></li>";

  // kiri atau kanan
  if ($i < 8) {
    $li .= $c_li;
  } else {
    $li2 .= $c_li;
  }
}
$str_hasil = !$li ? $tidak_ada : "
  <div class=row>
    <div class=col>
    <ul class='m0'>$li</ul>
    </div>
    
    <div class=col>
    <ul class='m0'>$li2</ul>
    </div>
  </div>

";
blok_hasil('PEMERIKSAAN FISIK', $str_hasil);
