<?php
include 'ajax_session.php';
ajax_only(['admin', 'marketing']);

$id_import = $_GET['id_import'] ?? die(erid('[id_import]'));
$tb = $_GET['tb'] ?? die(erid('[tb]'));

$s = "DELETE FROM tb_import_$tb WHERE Sample_ID = $id_import";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo 'sukses';
