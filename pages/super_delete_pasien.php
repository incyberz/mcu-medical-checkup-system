<?php
$id_pasien = $_GET['id_pasien'] ?? '';
$all = $_GET['all'] ?? '';

$s = "SELECT id as id_pasien FROM tb_pasien WHERE id='$id_pasien'";
if ($all) {
  $s = "SELECT id as id_pasien FROM tb_pasien ";
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  while ($d = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];
    $s2 = "DELETE FROM tb_pembayaran WHERE id_pasien=$d[id_pasien]";
    echolog("-- $s2");
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $s2 = "DELETE FROM tb_pasien WHERE id=$d[id_pasien]";
    echolog("-- $s2");
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  }
} else {
  echo div_alert('danger', 'Pasien tidak ditemukan');
}
