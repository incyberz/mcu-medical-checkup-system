<?php
# ============================================================
# PROCESSING PEMERIKSAAN FISIK AWAL
# ============================================================
# 1. Tinggi Badan => 2,
# 2. Berat Badan => 1,
# 3. Lingkar Perut => 6,
# 4. IMT => fx,
# 5. Status Lingkar Perut => fx,
# 6. Tes Buta warna => 11 fx,
# 1. Tekanan Darah => [7, 8],
# 2. Nadi => 140,
# 3. Pernafasan => 9,
# 4. Suhu => 148,
# 5. Saturasi Oksigen => 10,
# 6. Pakai Kacamata => 13,
# ============================================================
$abnormal = [];
$berat_badan = $arr_id_detail[1];
$tinggi_badan = $arr_id_detail[2];
$imt = round($berat_badan * 10000 / ($tinggi_badan * $tinggi_badan), 2);

$batas_imt = [
  18.5 => 'Underweight',
  25 => 'Normal range',
  30 => 'Overweight',
  35 => 'Obese class 1',
  40 => 'Obese class 2',
  999 => 'Obese class 3',
];
foreach ($batas_imt as $batas => $v) {
  $status_gizi = $v;
  if ($batas > $imt) break;
}



$lingkar_perut = $arr_id_detail[6];
if (strtolower($pasien['gender']) == 'l') {
  $resiko_lingkar_perut = $lingkar_perut > 90 ? 'Beresiko' : 'Dalam batas aman';
} else {
  $resiko_lingkar_perut = $lingkar_perut > 80 ? 'Beresiko' : 'Dalam batas aman';
}



$poin_tes_warna = $arr_id_detail[11];
if ($poin_tes_warna < 8) {
  if ($poin_tes_warna < 3) {
    $tes_buta_warna = 'Buta warna total';
  } else {
    $tes_buta_warna = 'Buta warna parsial';
  }
} else {
  $tes_buta_warna = 'Tidak buta warna';
}

$sistol = $arr_id_detail[7];
$diastol = $arr_id_detail[8];
$tensi =  "$sistol/$diastol mmHg";

$arr = [
  'Hipotensi' => ['sistol' => 100, 'distol' => 60],
  'Normal' => ['sistol' => 129, 'distol' => 84],
  'Pre-Hipertensi' => ['sistol' => 148, 'distol' => 89],
  'Hipertensi derajat 1' => ['sistol' => 159, 'distol' => 99],
  'Hipertensi derajat 2' => ['sistol' => 179, 'distol' => 109],
  'Hipertensi derajat 3' => ['sistol' => 299, 'distol' => 199],
];

foreach ($arr as $key_info_tensi => $arr_value) {
  if ($sistol < $arr_value['sistol'] && $diastol < $arr_value['distol']) {
    $tensi .= " ($key_info_tensi)";
    $info_tensi = $key_info_tensi;
    break;
  }
}


$abnormal['IMT'] = ($imt < 18.5 || $imt > 30) ? 1 : 0;
$abnormal['Lingkar Perut'] = $resiko_lingkar_perut == 'Beresiko' ? 1 : 0;
$abnormal['Tes Buta Warna'] = $poin_tes_warna < 8 ? 1 : 0;
$abnormal['Tekanan Darah'] = ($diastol < 60 || $diastol > 89 || $sistol < 100 || $sistol > 148) ? 1 : 0;

$pasien['pemeriksaan_fisik_awal'] = [
  'ki' => [
    'Tinggi Badan' => "$arr_id_detail[2] " . $arr_pemeriksaan_detail[2]['satuan'],
    'Berat Badan' => "$arr_id_detail[1] " . $arr_pemeriksaan_detail[1]['satuan'],
    'IMT' => "$imt (status gizi: $status_gizi)",
    'Lingkar Perut' => "$arr_id_detail[6] " . $arr_pemeriksaan_detail[6]['satuan'],
    'Resiko Lingkar Perut' => $resiko_lingkar_perut,
    'Tes Buta Warna' => $tes_buta_warna,
  ],
  'ka' => [
    'Tekanan Darah' => $tensi,
    'Nadi' => "$arr_id_detail[140] " . $arr_pemeriksaan_detail[140]['satuan'],
    'Pernafasan' => "$arr_id_detail[9] " . $arr_pemeriksaan_detail[9]['satuan'],
    'Suhu' => "$arr_id_detail[148] " . $arr_pemeriksaan_detail[148]['satuan'],
    'Saturasi Oksigen' => "$arr_id_detail[10] " . $arr_pemeriksaan_detail[10]['satuan'],
    'Pakai Kacamata' => "$arr_id_detail[13] " . $arr_pemeriksaan_detail[13]['satuan'],
  ],
];

$pasien['kesimpulan_pemeriksaan_fisik']['STATUS GIZI'] = $status_gizi;
$pasien['kesimpulan_pemeriksaan_fisik']['RESIKO LINGKAR PERUT'] = $resiko_lingkar_perut;
$pasien['kesimpulan_pemeriksaan_fisik']['TES BUTA WARNA'] = $tes_buta_warna;
$pasien['kesimpulan_pemeriksaan_fisik']['TEKANAN DARAH'] = $info_tensi;
