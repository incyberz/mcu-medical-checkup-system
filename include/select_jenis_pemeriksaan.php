<?php
$s = "SELECT * FROM tb_jenis_pemeriksaan where id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $select_jenis_pemeriksaan = div_alert('danger', 'Belum ada program pada klinik ini.');
} else {
  $select_jenis_pemeriksaan = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $select_jenis_pemeriksaan .= "<option value='$d[jenis]'>$d[nama]</option>";
  }
  $select_jenis_pemeriksaan = "<select class='form-control' name=jenis id=jenis>$select_jenis_pemeriksaan</select>";
}
