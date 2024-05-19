<?php
if (isset($_POST['btn_submit_data_pasien'])) {
  $section = $_POST['btn_submit_data_pasien'];
  unset($_POST['btn_submit_data_pasien']);

  if ($section == 'gigi') {
    echolog('EXCEPTION FOR GIGI');
    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    $array_gigi = '';
    foreach ($_POST as $status_gigi) {
      $array_gigi .= "$status_gigi,";
    }
    $sets = "array_gigi = '$array_gigi'";
  } else {
    $sets = '__';
    foreach ($_POST as $key => $value) {
      // echo "<br>$section | $key | $value";
      $sets .= ", $key = '$value'";
    }
  }

  $sets .= ", tanggal_simpan_$section = CURRENT_TIMESTAMP";
  $sets .= ", pemeriksa_$section = $id_user";
  $sets = str_replace('__,', '', $sets);

  $s = "UPDATE tb_mcu SET $sets WHERE id_pasien=$id_pasien";
  echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  echo div_alert('success', "Update Data Pasien sukses. $s");
  jsurl('', 1000);
  exit;
}
