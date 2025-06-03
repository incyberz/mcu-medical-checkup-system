<?php
set_title('Hasil MCU');
# ============================================================
# AWAL PERIKSA, SAMPLING, DAN LIST PEMERIKSAAN
# ============================================================
include 'hasil_pemeriksaan-mcu-awal_pemeriksaan.php';

echo "<h2 class='tengah f16 mt4 bold'>HASIL PEMERIKSAAN</h2>";

# ============================================================
# HASIL FROM PASIEN
# ============================================================
include 'hasil_pemeriksaan-mcu-from_pasien.php';

# ============================================================
# PEMFIS AWAL
# ============================================================
include 'hasil_pemeriksaan-mcu-pemfis.php';

# ============================================================
# MATA 
# ============================================================
include 'hasil_pemeriksaan-mcu-mata.php';

# ============================================================
# GIGI 
# ============================================================
// include 'hasil_pemeriksaan-mcu-gigi.php';

# ============================================================
# PEMFIS DOKTER
# ============================================================
include 'hasil_pemeriksaan-mcu-pemfis_dokter.php';



# ============================================================
# PEMFIS DOKTER
# ============================================================
include 'hasil_pemeriksaan-mcu-gigi.php';
include 'hasil_pemeriksaan-mcu-kesimpulan.php';

# ============================================================
# PEM.PENUNJANG
# ============================================================
include 'hasil_pemeriksaan-mcu-kesimpulan_penunjang.php';

include 'include/arr_kesimpulan.php';
$belum_ada = '<span class="red miring">belum diverifikasi oleh Dokter MCU</span>';
$hasil_at_db_show = $hasil_at_db['hasil'] ? $arr_kesimpulan[$hasil_at_db['hasil']] : $belum_ada;
blok_hasil('KESIMPULAN', $hasil_at_db_show);




# ============================================================
# KONSULTASI DAN REKOMENDASI
# ============================================================

# ============================================================
# KONSULTASI DAN REKOMENDASI
# ============================================================
$konsultasi = '';

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


$kesimpulan_fisik = $hasil_at_db['kesimpulan_fisik'] ?? "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=mcu'>$belum_ada</a>";

$arr_konsultasi = [];
if (strpos("salt$kesimpulan_fisik", 'obese') || strpos("salt$kesimpulan_fisik", 'underweight')) array_push($arr_konsultasi, 'dokter ahli gizi');
if (strpos("salt$kesimpulan_fisik", 'gigi')) array_push($arr_konsultasi, 'dokter gigi');
if ($hasil_lab['HEMA'] != 'normal' || $hasil_lab['URINE'] != 'normal') array_push($arr_konsultasi, 'dokter umum');


if (isset($hasil_lab['RONTGEN'])) {
  if ($hasil_lab['RONTGEN'] != 'normal') array_push($arr_konsultasi, 'dokter paru');
}

if (isset($arr_id_detail[14]) and isset($arr_id_detail[142])) {
  if ($arr_id_detail[14] > 20 || $arr_id_detail[142] > 20) array_push($arr_konsultasi, 'dokter mata');
}


if (!$arr_konsultasi) {
  $konsultasi = '-';
} elseif (count($arr_konsultasi) == 1) {
  $konsultasi = 'konsultasi ke ' . $arr_konsultasi[0];
} elseif (count($arr_konsultasi) == 2) {
  $konsultasi = 'konsultasi ke ' . $arr_konsultasi[0] . ' dan ' . $arr_konsultasi[1];
} else {
  $konsultasi = 'konsultasi ke ' . implode(', ', $arr_konsultasi);
}

if ($hasil_at_db['konsultasi'] != $konsultasi) { // auto-update konsultasi
  $s = "UPDATE tb_hasil_pemeriksaan SET konsultasi = '$konsultasi' WHERE id_pasien=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
}

// $hasil_at_db_show = $hasil_at_db['konsultasi'] ? $arr_kesimpulan[$hasil_at_db['konsultasi']] : '-';
blok_hasil('KONSULTASI', $konsultasi);

$hasil_at_db_show = $hasil_at_db['rekomendasi'] ? $arr_kesimpulan[$hasil_at_db['rekomendasi']] : 'Dapat bekerja sesuai bidangnya';
blok_hasil('REKOMENDASI', $hasil_at_db_show);
