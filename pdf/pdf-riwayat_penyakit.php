<?php
# ============================================================
# RIWAYAT PENYAKIT
# ============================================================
if ($pasien['riwayat_penyakit']) {
  $dt = explode(',', $pasien['riwayat_penyakit']);
  $arr = [
    'RIWAYAT PENYAKIT' => 'riwayat',
    'RIWAYAT PENGOBATAN' => 'pengobatan',
    'RIWAYAT PENYAKIT AYAH' => 'ayah',
    'RIWAYAT PENYAKIT IBU' => 'ibu'
  ];
  $riwayat = [];
  foreach ($arr as $v1) if ($v1) $riwayat[$v1] = '';
  foreach ($dt as $v2) {
    $t = explode('--', $v2);
    if ($v2) {
      $riwayat[$t[0]] .= $riwayat[$t[0]] ?  ", $v2" : $v2;
    }
  }

  foreach ($arr as $k1 => $v1) {
    $riwayat[$v1] = $riwayat[$v1] ? $riwayat[$v1] : null;
  }

  $pasien['riwayat_penyakit_pasien'] = $riwayat['riwayat'];
  $pasien['riwayat_pengobatan'] = $riwayat['pengobatan'];
  $pasien['riwayat_penyakit_ayah'] = $riwayat['ayah'];
  $pasien['riwayat_penyakit_ibu'] = $riwayat['ibu'];
}
