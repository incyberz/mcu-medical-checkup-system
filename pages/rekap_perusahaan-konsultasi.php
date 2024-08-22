<?php
# ============================================================
# rekomendasi
# ============================================================
if ($hasil_at_db['rekomendasi']) {
  $rekomendasi = $hasil_at_db['rekomendasi'];
} elseif (!$hasil_at_db['rekomendasi']) {
  if ($hasil_at_db['hasil'] == 1 || $hasil_at_db['hasil'] == 2) {
    $rekomendasi = 'Dapat bekerja sesuai bidangnya';
  } else {
    $rekomendasi = ($hasil_at_db['hasil'] === '0' || $hasil_at_db['hasil'] === 0) ? 'lakukan pemeriksaan kesehatan lanjutan' :
      $hasil_at_db['rekomendasi'];
  }
}


# ============================================================
# konsultasi from db
# ============================================================
$konsultasi = $hasil_at_db['konsultasi'] ?? '-';


$arr_konsultasi = [];
# ============================================================
# konsultasi ahli gizi
# ============================================================
if (strpos("salt$kesimpulan_fisik", 'obese') || strpos("salt$kesimpulan_fisik", 'underweight')) array_push($arr_konsultasi, 'dokter ahli gizi');

# ============================================================
# konsultasi gigi
# ============================================================
if (strpos("salt$kesimpulan_fisik", 'gigi')) array_push($arr_konsultasi, 'dokter gigi');

# ============================================================
# konsultasi lab | dokter umum
# ============================================================
if ($hasil_lab['HEMA'] != 'normal' || $hasil_lab['URINE'] != 'normal') array_push($arr_konsultasi, 'dokter umum');

# ============================================================
# konsultasi paru atau jantung
# ============================================================
$hasil_lab['RONTGEN'] = strip_tags(strtolower($hasil_lab['RONTGEN']));
if (strpos("salt$hasil_lab[RONTGEN]", 'cardiomega') || strpos("salt$hasil_lab[RONTGEN]", 'elongasi')) {
  array_push($arr_konsultasi, 'dokter jantung');
} else {
  if (!strpos("salt$hasil_lab[RONTGEN]", 'normal')) {
    array_push($arr_konsultasi, 'dokter paru');
  }
}

# ============================================================
# konsultasi mata
# ============================================================
if ($arr_id_detail[14] > 20 || $arr_id_detail[142] > 20) array_push($arr_konsultasi, 'dokter mata');


if (!$arr_konsultasi) {
  $konsultasi = '-';
} elseif (count($arr_konsultasi) == 1) {
  $konsultasi = 'konsultasi ke ' . $arr_konsultasi[0];
} elseif (count($arr_konsultasi) == 2) {
  $konsultasi = 'konsultasi ke ' . $arr_konsultasi[0] . ' dan ' . $arr_konsultasi[1];
} else {
  $konsultasi = 'konsultasi ke ' . implode(', ', $arr_konsultasi);
}
