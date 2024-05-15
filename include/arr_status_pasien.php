<?php
# ============================================================
# SELECT STATUS PASIEN
# ============================================================
$s = "SELECT status, nama FROM tb_status_pasien ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  die('Data status pasien tidak ditemukan @arr_status_pasien');
} else {
  $arr_status_pasien = [];
  while ($d = mysqli_fetch_assoc($q)) {
    $arr_status_pasien[$d['status']] = $d['nama'];
  }
}
