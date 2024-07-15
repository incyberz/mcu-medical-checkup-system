<?php
$s = "SELECT jenis,nama FROM tb_jenis_pemeriksaan where id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_jenis_pemeriksaan = [];
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', 'Belum ada jenis pemeriksaan pada klinik ini.');
} else {

  $opt = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $arr_jenis_pemeriksaan[$d['jenis']] = $d['nama'];
  }
}
