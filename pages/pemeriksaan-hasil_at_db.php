<?php
$arr_id_detail = [];

$arr_id_pemeriksaan_tanggal = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];

$arr_sampel_tanggal_by = [];
$arr_sampel_tanggal = [];
$arr_sampel_by = [];
# ============================================================
# GET HASIL DB
# ============================================================
$s = "SELECT * FROM tb_hasil_pemeriksaan WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$hasil_at_db = [];
if (mysqli_num_rows($q)) {
  $hasil_at_db = mysqli_fetch_assoc($q);
  $arr_hasil = explode('||', $hasil_at_db['arr_hasil']);
  $arr_tanggal_by = explode('||', $hasil_at_db['arr_tanggal_by']);
  $arr_sampel_tanggal_by = explode('||', $hasil_at_db['arr_sampel']);

  foreach ($arr_hasil as $pair) {
    if (!$pair) continue;
    $arr_pair = explode('=', $pair, 2);
    $arr_id_detail[$arr_pair[0]] = $arr_pair[1];
  }

  foreach ($arr_tanggal_by as $pair) {
    if (!$pair) continue;
    $arr_pair = explode('=', $pair, 2);
    $arr_id_pemeriksaan_tanggal[$arr_pair[0]] = $arr_pair[1];


    $arr_tmp = explode(',', $arr_pair[1]);
    $arr_pemeriksaan_tanggal[$arr_pair[0]] = $arr_tmp[0];
    $arr_pemeriksaan_by[$arr_pair[0]] = $arr_tmp[1];
  }

  foreach ($arr_sampel_tanggal_by as $pair) {
    if (!$pair) continue;
    $arr_pair = explode('=', $pair, 2);

    $arr_tmp = explode(',', $arr_pair[1]);
    if ($arr_tmp[0]) {
      $arr_sampel_tanggal[$arr_pair[0]] = $arr_tmp[0];
      $arr_sampel_by[$arr_pair[0]] = $arr_tmp[1];
    }
  }
}
