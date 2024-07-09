<?php
# =============================================================
# UPDATE | DELETE PEMERIKSAAN
# =============================================================
include 'ajax_session.php';
ajax_only(['admin', 'marketing', 'nakes']);

$aksi = $_GET['aksi'] ?? die(erid('aksi'));
$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? die(erid('id_pemeriksaan'));
if ($aksi == 'update') {
  $field = $_GET['field'] ?? die(erid('field'));
  $value = $_GET['value'] ?? die(erid('value'));

  $s = "UPDATE tb_pemeriksaan SET $field='$value' WHERE id=$id_pemeriksaan";
} elseif ($aksi == 'delete') {
  $s = "DELETE FROM tb_pemeriksaan WHERE id=$id_pemeriksaan";
}

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo 'sukses';
