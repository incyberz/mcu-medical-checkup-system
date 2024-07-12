<?php
$s9 = "SELECT id as id_pemeriksaan,nama FROM tb_pemeriksaan WHERE id_klinik=$id_klinik";
$q9 = mysqli_query($cn, $s9) or die(mysqli_error($cn));
$arr_pemeriksaan = [];
while ($d9 = mysqli_fetch_assoc($q9)) {
  $arr_pemeriksaan[$d9['id_pemeriksaan']] = $d9['nama'];
}
