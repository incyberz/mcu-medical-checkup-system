<?php
if (isset($_POST['btn_delete_fitur'])) {
  $s = "DELETE FROM tb_progres_modul WHERE id=$_POST[btn_delete_fitur]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_add_rev'])) {
  $nama = $_POST['nama'] ?? die(erid('nama'));
  $keterangan = $_POST['keterangan'] ?? die(erid('keterangan'));

  $s = "INSERT INTO tb_progres_task (
    id_fitur,
    nama,
    request_by,
    keterangan
  ) VALUES (
    $_POST[btn_add_rev],
    '$nama',
    $id_user,
    '$keterangan'
  )";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_delete_fitur'])) {
  $s = "DELETE FROM tb_progres_fitur WHERE id=$_POST[btn_delete_fitur]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_add_fitur'])) {
  $nama_fitur = $_POST['new_fitur'] ?? die(erid('new_fitur'));
  $nama_fitur = strtoupper($nama_fitur);
  $s = "INSERT INTO tb_progres_modul (modul, request_by) VALUES ('$nama_fitur',$id_user)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}
if (isset($_POST['btn_add_fitur']) || isset($_POST['btn_add_fitur_daily'])) {
  $id_modul =  $_POST['id_modul'] ?? $_POST['btn_add_fitur']; // from daily-form or from btn-val
  if (!$id_modul) die(erid('id_modul'));
  $nama_fitur = $_POST['new_fitur'] ?? die(erid('new_fitur'));
  $keterangan = $_POST['keterangan'] ?? die(erid('keterangan'));
  $href = $_POST['href'] ?? null;
  $href = $href ? "'$href'" : 'NULL';

  $nama_fitur = strtoupper($nama_fitur);

  $s = "INSERT INTO tb_progres_fitur 
  (id_modul,nama,request_by,keterangan,href) VALUES 
  ($id_modul,'$nama_fitur',$id_user,'$keterangan',$href)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}


if (isset($_POST['btn_update_fitur'])) {
  $id = $_POST['btn_update_fitur'];
  unset($_POST['btn_update_fitur']);

  $pairs = '__';
  foreach ($_POST as $key => $value) {
    $value = $value ? "'$value'" : 'NULL';
    $value = $key == 'modul' ? strtoupper($value) : $value;
    $pairs .= ",$key = $value";
  }
  $pairs = str_replace('__,', '', $pairs);

  $s = "UPDATE tb_progres_modul SET $pairs WHERE id=$id";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_set_status_progres_sub'])) {
  // echo div_alert('danger','Maaf, saat ini hanya DEVELOPER yang bisa mengubah status development.');
  $arr = explode('__', $_POST['btn_set_status_progres_sub']);
  $status = $arr[0];
  $id_fitur = $arr[1];

  $s = "SELECT id_modul FROM tb_progres_fitur WHERE id=$id_fitur";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $id_modul = $d['id_modul'];

  $s = "UPDATE tb_progres_fitur SET status=$status,last_update=CURRENT_TIMESTAMP WHERE id=$id_fitur";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl("?progres&id_modul=$id_modul&mode=$mode");
}

if (isset($_POST['btn_sedang_dikerjakan'])) {
  echo div_alert('danger', 'Maaf, hanya DEVELOPER yang bisa mengubah fitur mana yang sedang dikerjakan saat ini.');
}
