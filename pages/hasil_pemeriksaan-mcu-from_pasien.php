<?php
# ============================================================
# HASIL RIWAYAT PENYAKIT
# ============================================================
$dt = explode(',', $pasien['riwayat_penyakit']);
$arr = [
  'RIWAYAT PENYAKIT' => 'riwayat',
  'RIWAYAT PENGOBATAN' => 'pengobatan',
  'RIWAYAT PENYAKIT AYAH' => 'ayah',
  'RIWAYAT PENYAKIT IBU' => 'ibu'
];
$riw = [];
foreach ($arr as $k1 => $v1) if ($v1) $riw[$v1] = '';

foreach ($dt as $k2 => $v2) {
  $t = explode('--', $v2);
  if ($v2) $riw[$t[0]] .= "<li>$v2</li>";
}

foreach ($arr as $k1 => $v1) {

  // if (!$riw[$v1]) continue;
  $riw[$v1] = $riw[$v1] ? "<ul class='hasil m0'>$riw[$v1]</ul>" : $tidak_ada;
  blok_hasil($k1, $riw[$v1]);
}


# ============================================================
# HASIL GEJALA PENYAKIT
# ============================================================
$dt = explode(',', $pasien['gejala_penyakit']);
$str_hasil = '';
foreach ($dt as $k => $v)  if ($v) $str_hasil .= "<li>$v</li>";

$str_hasil = $str_hasil ? "<ul class='hasil m0'>$str_hasil</ul>" : $tidak_ada;
blok_hasil('GEJALA PENYAKIT', $str_hasil);



# ============================================================
# HASIL GAYA HIDUP
# ============================================================
$dt = explode(',', $pasien['gaya_hidup']);
$str_hasil = '';
foreach ($dt as $k => $v)  if ($v) $str_hasil .= "<li>$v</li>";

$str_hasil = $str_hasil ? "<ul class='hasil m0'>$str_hasil</ul>" : $tidak_ada;
blok_hasil('GAYA HIDUP', $str_hasil);



# ============================================================
# HASIL KELUHAN
# ============================================================
$str_hasil = strlen($pasien['keluhan']) > 3 ? "<span class='hasil m0'>$pasien[keluhan]</span>" : $tidak_ada;
blok_hasil('KELUHAN', $str_hasil);
