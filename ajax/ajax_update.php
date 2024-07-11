<?php
# =============================================================
# UPDATE | DELETE PEMERIKSAAN
# =============================================================
include 'ajax_session.php';
ajax_only(['admin', 'marketing', 'nakes']);

$aksi = $_GET['aksi'] ?? die(erid('aksi'));
$id = $_GET['id'] ?? die(erid('id'));
$tb = $_GET['tb'] ?? die(erid('tb'));
if ($aksi == 'update') {
  $field = $_GET['field'] ?? die(erid('field'));
  $value = $_GET['value'] ?? die(erid('value'));

  $s = "UPDATE tb_$tb SET $field='$value' WHERE id=$id";
} elseif ($aksi == 'delete') {
  $s = "DELETE FROM tb_$tb WHERE id=$id";
}

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo 'sukses';
