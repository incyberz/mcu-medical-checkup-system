<?php
# ============================================================
# DELETE ACTION
# ============================================================
if (isset($_POST['btn_delete_modul'])) {
  $s = "DELETE FROM tb_progres_modul WHERE id=$_POST[btn_delete_modul]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}
if (isset($_POST['btn_delete_fitur'])) {
  $s = "DELETE FROM tb_progres_fitur WHERE id=$_POST[btn_delete_fitur]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}
if (isset($_POST['btn_delete_task'])) {
  $s = "DELETE FROM tb_progres_task WHERE id=$_POST[btn_delete_task]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}



# ============================================================
# TAKE TASK
# ============================================================
if (isset($_POST['btn_take_task'])) {
  $s = "UPDATE tb_progres_task SET assign_by=$id_user WHERE id=$_POST[btn_take_task]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

# ============================================================
# DROP TASK
# ============================================================
if (isset($_POST['btn_drop_task'])) {
  $s = "UPDATE tb_progres_task SET assign_by=NULL WHERE id=$_POST[btn_drop_task] AND assign_by=$id_user";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}



# ============================================================
# ADD TASK
# ============================================================
if (isset($_POST['btn_add_task'])) {
  $post_task = $_POST['task'] ?? die(erid('task'));
  $post_keterangan = $_POST['keterangan'] ?? die(erid('keterangan'));
  $post_id_fitur = $_POST['id_fitur'] ?? die(erid('id_fitur'));
  $assign_by = $_POST['assign_by'] ?? die(erid('assign_by'));
  $status = $_POST['status'] ?? die(erid('status'));

  $assign_by = $assign_by ? $assign_by : 'NULL';

  if ($post_id_fitur) {
    $s = "INSERT INTO tb_progres_task (
      id_fitur,
      task,
      request_by,
      keterangan,
      date_created,
      assign_by,
      status
    ) VALUES (
      $post_id_fitur,
      '$post_task',
      $id_user,
      '$post_keterangan',
      '$post_today',
      $assign_by,
      $status
    )";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    jsurl();
  } else {
    echo div_alert('danger', "Anda belum memilih Fitur pada saat Add Task.");
  }
}

# ============================================================
# ADD MODUL
# ============================================================
if (isset($_POST['btn_add_modul'])) {
  $nama_modul = $_POST['new_modul'] ?? die(erid('new_modul'));
  $nama_modul = strtoupper($nama_modul);
  $s = "INSERT INTO tb_progres_modul (modul, request_by) VALUES ('$nama_modul',$id_user)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

if (isset($_POST['btn_add_fitur']) || isset($_POST['btn_add_fitur_daily'])) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  $id_modul =  $_POST['id_modul'] ?? $_POST['btn_add_fitur']; // from daily-form or from btn-val
  if (!$id_modul) die(erid('id_modul'));
  $fitur = $_POST['new_fitur'] ?? die(erid('new_fitur'));
  $keterangan = $_POST['keterangan'] ?? die(erid('keterangan'));
  $href = $_POST['href'] ?? null;
  $href = $href ? "'$href'" : 'NULL';

  $fitur = strtoupper($fitur);
  $keterangan = $keterangan ? "'$keterangan'" : 'NULL';

  $s = "INSERT INTO tb_progres_fitur 
  (id_modul,fitur,request_by,keterangan,href) VALUES 
  ($id_modul,'$fitur',$id_user,$keterangan,$href)";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}


# ============================================================
# UPDATE MODUL
# ============================================================
if (isset($_POST['btn_update_modul'])) {
  $id = $_POST['btn_update_modul'];
  unset($_POST['btn_update_modul']);

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


# ============================================================
# SET STATUS TASK 
# ============================================================
if (isset($_POST['btn_set_status_task'])) {
  if (!in_array($username, $dev_usernames)) {
    echo div_alert('danger', "Hanya Developer yang berhak mengubah status Task.");
  } else {
    $arr = explode('__', $_POST['btn_set_status_task']);
    $status = $arr[0];
    $id_task = $arr[1];


    $s = "UPDATE tb_progres_task SET status=$status,last_update=CURRENT_TIMESTAMP WHERE id=$id_task";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    jsurl();
  }
}
