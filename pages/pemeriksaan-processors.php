<?php
if (isset($_POST['btn_submit_data_pasien'])) {
  $id_pasien = $_POST['btn_submit_data_pasien'] ?? die('index [id_pasien] undefined');
  // $id_paket = $_POST['id_paket'] ?? die('index [id_paket] undefined');
  // $id_pemeriksaan = $_POST['id_pemeriksaan'] ?? die('index [id_pemeriksaan] undefined');
  $last_pemeriksaan = $_POST['last_pemeriksaan'] ?? die('index [last_pemeriksaan] undefined');
  $id_pemeriksaan = $_POST['id_pemeriksaan'] ?? die('index [id_pemeriksaan] undefined');
  unset($_POST['btn_submit_data_pasien']);
  unset($_POST['last_pemeriksaan']);
  unset($_POST['id_pemeriksaan']);

  // echolog('data POST');
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';




  # ============================================================
  # UPDATE ARRAY ID DETAIL WITH DATA POSTS
  # ============================================================
  echolog('updating array');
  foreach ($_POST as $key => $value) {
    $arr_id_detail[$key] = $value;
  }
  // echo '<pre>';
  // var_dump($arr_id_detail);
  // echo '</pre>';

  # ============================================================
  # ARRAY SORT BY KEY
  # ============================================================
  ksort($arr_id_detail);

  # ============================================================
  # CONVERT TO STRING
  # ============================================================
  $pairs = [];
  echolog('converting to string');
  $str = '';
  foreach ($arr_id_detail as $key => $value) {
    if ($value) $str .= "$key=$value||";
  }
  $pairs['arr_hasil'] = "arr_hasil='$str'";

  // echo '<pre>';
  // var_dump($str);
  // echo '</pre>';

  echolog('timestamp and by-role ZZZ');

  $str_pairs = join(',', $pairs);

  $s = "UPDATE tb_hasil_pemeriksaan SET 
    $str_pairs,
    last_pemeriksaan = '$last_pemeriksaan',
    last_update = CURRENT_TIMESTAMP,
    status = 2 
  WHERE id_pasien=$id_pasien";
  echolog($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echolog('sukses');
  // jsurl("?tampil_pasien&id_pasien=$id_pasien", 3000);

  # ============================================================
  # UPDATE status pasien 
  # ============================================================
  $s = "UPDATE tb_pasien SET status=9 WHERE id=$id_pasien";
  echolog('updating status pasien');
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl();
  exit;
}
