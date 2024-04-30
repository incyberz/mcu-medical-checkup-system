<?php
$s = "SELECT * FROM tb_status_order";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_status_order = [];
while ($d = mysqli_fetch_assoc($q)) {
  $arr_status_order[$d['status']] = $d['nama'];
}
