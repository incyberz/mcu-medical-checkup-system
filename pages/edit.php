<?php
set_title("Edit ???");
only('users');
$id = $_GET['id'] ?? die(div_alert('danger', "Page editing membutuhkan index [id]"));
$tb = $_GET['tb'] ?? die(div_alert('danger', "Page editing membutuhkan index [tb]"));
$acuan = $_GET['acuan'] ?? 'id';
$s = "SELECT 1 FROM tb_$tb WHERE $acuan = '$id'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(div_alert('danger', "Data [$tb] tidak ditemukan<hr>$s"));
$Tb = ucwords($tb);

set_title("Edit $Tb");
?>
ZZZ