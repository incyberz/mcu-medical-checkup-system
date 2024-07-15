<?php
$s = "SELECT sampel,zat,volume,satuan FROM tb_sampel WHERE id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_sampel = [];
while ($d = mysqli_fetch_assoc($q)) {
  $arr_sampel[$d['sampel']] = "$d[sampel] - $d[zat] - $d[volume] $d[satuan]";
}
