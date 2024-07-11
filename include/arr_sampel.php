<?php
$s = "SELECT * FROM tb_sampel WHERE id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_sampel = [];
while ($d = mysqli_fetch_assoc($q)) {
  foreach ($d as $key => $value) {
    if ($key == 'sampel' || $key == 'id_klinik') continue;
    $arr_sampel[$d['sampel']][$key] = $value;
  }
}
