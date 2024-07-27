<?php
$s = "SELECT a.id,a.nama 
FROM tb_paket a 
JOIN tb_program b ON a.id_program=b.id 
WHERE b.id_klinik=$id_klinik 
AND a.status=1 -- paket aktif
AND a.id_program=1 -- program MCU
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_paket_corporate = [];
while ($d = mysqli_fetch_assoc($q)) {
  $arr_paket_corporate[$d['id']] = $d['nama'];
}
