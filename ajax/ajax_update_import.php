<?php
# =============================================================
# UPDATE | DELETE PEMERIKSAAN
# =============================================================
include 'ajax_session.php';
ajax_only(['admin', 'marketing']);

$id_pasien = $_GET['id_pasien'] ?? die(erid('id_pasien'));
$arr_hasil = $_GET['arr_hasil'] ?? die(erid('arr_hasil'));
$arr_tanggal_by = $_GET['arr_tanggal_by'] ?? die(erid('arr_tanggal_by'));

$s = "UPDATE tb_$tb SET $field=$value_or_null WHERE id=$id";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo 'sukses';
