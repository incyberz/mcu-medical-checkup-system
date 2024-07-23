<?php

# ============================================================
# AWAL PEMERIKSAAN
# ============================================================
blok_hasil('AWAL PEMERIKSAAN', $hasil['awal_periksa'], 1);

# ============================================================
# SAMPLING
# ============================================================
$li = '';
foreach ($arr_sampel_by as $key => $value) {
  if (strlen($key) < 3) continue;
  $by = $arr_user[$value];
  $at = $arr_sampel_tanggal[$key];
  $li .= "<li>$key at $at </li>";
}
$str_hasil = $li ? "<ul class='hasil m0'>$li</ul>" : $tidak_ada;
blok_hasil('SAMPLING', $str_hasil);

# ============================================================
# PEMERIKSAAN
# ============================================================
// $li = '';
// foreach ($arr_pemeriksaan_by as $key => $value) {
//   $by = $arr_user[$value];
//   $at = $arr_pemeriksaan_tanggal[$key];
//   $li .= "<li>$arr_pemeriksaan[$key] by $by at $at </li>";
// }
// $str_hasil = $li ? "<ul class='hasil m0'>$li</ul>" : $tidak_ada;
// blok_hasil('PEMERIKSAAN', $str_hasil);
