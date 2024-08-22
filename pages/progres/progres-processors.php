<?php
if (isset($_POST['btn_delete_fitur'])) {
  $s = "DELETE FROM tb_progres_h1 WHERE id=$_POST[btn_delete_fitur]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_delete_subfitur'])) {
  $s = "DELETE FROM tb_progres_sub WHERE id=$_POST[btn_delete_subfitur]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_add_fitur'])) {
  $nama_fitur = $_POST['new_fitur'] ?? die(erid('new_fitur'));
  $nama_fitur = strtoupper($nama_fitur);
  $s = "INSERT INTO tb_progres_h1 (h1, request_by) VALUES ('$nama_fitur',$id_user)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}
if (isset($_POST['btn_add_subfitur']) || isset($_POST['btn_add_subfitur_daily'])) {
  $id_fitur =  $_POST['id_fitur'] ?? $_POST['btn_add_subfitur']; // from daily-form or from btn-val
  if (!$id_fitur) die(erid('id_fitur'));
  $nama_subfitur = $_POST['new_subfitur'] ?? die(erid('new_subfitur'));
  $keterangan = $_POST['keterangan'] ?? die(erid('keterangan'));
  $href = $_POST['href'] ?? null;
  $href = $href ? "'$href'" : 'NULL';

  $nama_subfitur = strtoupper($nama_subfitur);

  $s = "INSERT INTO tb_progres_sub 
  (id_fitur,nama,request_by,keterangan,href) VALUES 
  ($id_fitur,'$nama_subfitur',$id_user,'$keterangan',$href)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}


if (isset($_POST['btn_update_fitur'])) {
  $id = $_POST['btn_update_fitur'];
  unset($_POST['btn_update_fitur']);

  $pairs = '__';
  foreach ($_POST as $key => $value) {
    $value = $value ? "'$value'" : 'NULL';
    $value = $key == 'h1' ? strtoupper($value) : $value;
    $pairs .= ",$key = $value";
  }
  $pairs = str_replace('__,', '', $pairs);

  $s = "UPDATE tb_progres_h1 SET $pairs WHERE id=$id";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_set_status'])) {
  // echo div_alert('danger','Maaf, saat ini hanya DEVELOPER yang bisa mengubah status development.');
  $arr = explode('__', $_POST['btn_set_status']);
  $status = $arr[0];
  $id_subfitur = $arr[1];

  $s = "SELECT id_fitur FROM tb_progres_sub WHERE id=$id_subfitur";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $id_fitur = $d['id_fitur'];

  $s = "UPDATE tb_progres_sub SET status=$status,last_update=CURRENT_TIMESTAMP WHERE id=$id_subfitur";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl("?progres&id_fitur=$id_fitur&mode=$mode");
}

if (isset($_POST['btn_sedang_dikerjakan'])) {
  echo div_alert('danger', 'Maaf, hanya DEVELOPER yang bisa mengubah fitur mana yang sedang dikerjakan saat ini.');
}
