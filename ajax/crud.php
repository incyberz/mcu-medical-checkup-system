<?php
# =============================================================
# AJAX CRUD by InSho
# =============================================================
# Revised: 
# 1.0.3 - ajax_session.php revised + NULL update value handler
# 1.0.2 - aksi assign dengan double acuan
# =============================================================
include 'ajax_session.php';
ajax_only(['admin', 'marketing', 'perawat']);

$tb = $_GET['tb'] ?? die(erid('tb'));
if (!$tb) die(erid('tb::empty'));
$aksi = $_GET['aksi'] ?? die(erid('aksi'));
if (!$aksi) die(erid('aksi::empty'));
$id = $_GET['id'] ?? die(erid('id'));
if (!$id) die(erid('id::empty'));

if ($tb == 'tb_paket_sticker') {
  $value = $_GET['value'];
  if (!$value) die(erid('id::empty'));

  if ($aksi == 'insert') {
    $arr = explode('-', $value);
    $s = "INSERT INTO tb_paket_sticker (kode,id_paket,id_sticker) VALUES ('$value',$arr[0],$arr[1]) ON DUPLICATE KEY UPDATE id_sticker=$arr[1]";
  } elseif ($aksi == 'delete') {
    $s = "DELETE FROM tb_paket_sticker WHERE kode='$value'";
  } else {
    die("Belum ada handler untuk aksi: $aksi");
  }
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  die('sukses');
}

$tb = str_replace('edit_', '', $tb);

if ($aksi == 'update') {
  $kolom = $_GET['kolom'] ?? die(erid('kolom'));
  if (!$kolom) die(erid('kolom::empty'));
  $value = $_GET['value'] ?? die(erid('value'));
  if (!$value) die(erid('value::empty'));

  $value = strip_tags(clean_sql($value));
  $value = ($value == '' || $value == 'NULL') ? 'NULL' : "'$value'";
  $s = "UPDATE tb_$tb SET $kolom = $value WHERE id = $id";
} elseif ($aksi == 'insert') {
  $koloms = 'kode,nama';
  $values = "'AAA-NEW','NEW $tb'";
  $s = "INSERT INTO tb_$tb ($koloms) VALUES ($values) ";
} elseif ($aksi == 'insert_item') {
  $ids = $_GET['ids'] ?? die(erid('ids'));
  $qtys = $_GET['qtys'] ?? die(erid('qtys'));
  $hargas = $_GET['hargas'] ?? die(erid('hargas'));

  $rid = explode(';', $ids);
  $rqty = explode(';', $qtys);
  $rharga = explode(';', $hargas);

  foreach ($rid as $key => $id) {
    if (strlen($id) > 0) {
      $harga_manual = $rharga[$key] ?? 'NULL';
      $s = "UPDATE tb_$tb SET qty=$rqty[$key], harga_manual=$harga_manual WHERE id=$rid[$key] ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
  die('sukses');
} elseif ($aksi == 'delete') {
  $s = "DELETE FROM tb_$tb WHERE id = $id";
} elseif ($aksi == 'assign') {

  $acuan1 = $_GET['acuan1'] ?? die(erid('acuan1'));
  if (!$acuan1) die(erid('acuan1::empty'));
  $acuan2 = $_GET['acuan2'] ?? die(erid('acuan2'));
  if (!$acuan2) die(erid('acuan2::empty'));
  $id1 = $_GET['id1'] ?? die(erid('id1'));
  if (!$id1) die(erid('id1::empty'));
  $id2 = $_GET['id2'] ?? die(erid('id2'));
  if (!$id2) die(erid('id2::empty'));
  $s = "SELECT 1 FROM tb_$tb WHERE $acuan1 = '$id1' AND $acuan2 = '$id2'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $kolom = $_GET['kolom'] ?? die(erid('kolom'));
  if (!$kolom) die(erid('kolom::empty'));
  $value = $_GET['value'] ?? die(erid('value'));
  if (!$value) die(erid('value::empty'));

  $value = strtolower($value) == 'null' ? 'NULL' : "'$value'";

  $kolom2 = $_GET['kolom2'] ?? null;
  $value2 = $_GET['value2'] ?? null;
  if ($kolom2 and !$value2) die(erid('value2::empty'));
  if (mysqli_num_rows($q)) {
    //update
    $str = $kolom2 ? ",$kolom2='$value2'" : '';
    $s = "UPDATE tb_$tb SET $kolom=$value $str WHERE $acuan1 = '$id1' AND $acuan2 = '$id2'";
  } else {
    //insert new
    $str_kolom = $kolom2 ? ",$kolom2" : '';
    $str_value = $value2 ? ",'$value2'" : '';
    $s = "INSERT INTO tb_$tb ($kolom,$acuan1,$acuan2 $str_kolom) VALUES ($value,'$id1','$id2' $str_value)";
  }
} else {
  die("Handler untuk aksi: $aksi, belum ditentukan. ");
}
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

?>
sukses