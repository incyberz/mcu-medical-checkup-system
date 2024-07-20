<?php
$s9 = "SELECT 
id,
label,
satuan,
option_default 
FROM tb_pemeriksaan_detail ";
$q9 = mysqli_query($cn, $s9) or die(mysqli_error($cn));
$arr_pemeriksaan_detail = [];
while ($d9 = mysqli_fetch_assoc($q9)) {
  $arr_pemeriksaan_detail[$d9['id']] = [
    'label' => $d9['label'],
    'satuan' => $d9['satuan'],
    'option_default' => $d9['option_default'],
  ];
}
