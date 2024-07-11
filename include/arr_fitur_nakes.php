<?php
$s_roles = "SELECT * FROM tb_role_pemeriksaan a 
JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
WHERE a.role='nakes'
AND b.jenis='mcu'";
$q_roles = mysqli_query($cn, $s_roles) or die(mysqli_error($cn));
while ($data_roles = mysqli_fetch_assoc($q_roles)) {
  $arr_fitur_nakes[$data_roles['pemeriksaan']] = $data_roles['nama'];
}
