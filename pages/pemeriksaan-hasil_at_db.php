<?php
$arr_id_detail = [];
# ============================================================
# GET HASIL DB
# ============================================================
$s = "SELECT * FROM tb_hasil_pemeriksaan WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $d = mysqli_fetch_assoc($q);
  $arr_hasil = explode('||', $d['arr_hasil']);
  $arr_tanggal = explode('||', $d['arr_tanggal']);
  $arr_by = explode('||', $d['arr_by']);

  // $arr_hasil = 1=167
  // echolog('string arr_hasil from DB');
  // echo '<pre>';
  // var_dump($arr_hasil);
  // echo '</pre>';
  foreach ($arr_hasil as $pair) {
    if (!$pair) continue;
    $arr_pair = explode('=', $pair, 2);
    $arr_id_detail[$arr_pair[0]] = $arr_pair[1];
  }

  // echolog('converted to arr hasil at DB || $arr_id_detail');
  // echo '<pre>';
  // var_dump($arr_id_detail);
  // echo '</pre>';
}
