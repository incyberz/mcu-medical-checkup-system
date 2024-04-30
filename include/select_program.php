<?php
$s = "SELECT * FROM tb_program where id_klinik=$id_klinik";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $select_program = div_alert('danger', 'Belum ada program pada klinik ini.');
} else {
  $select_program = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $select_program .= "<option value='$d[id]'>$d[nama]</option>";
  }
  $select_program = "<select class='form-control' name=id_program id=id_program>$select_program</select>";
}
