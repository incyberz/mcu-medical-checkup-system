<?php
if (isset($_FILES)) {
  $field = '';
  $size = 0;
  foreach ($_FILES as $key => $value) {
    $field = $key;
    $size = $_FILES[$key]['size'];
  }

  if ($size) {

    $source = $_FILES[$key]['tmp_name'];
    $file_awal = '<b class="red">UNKNOWN</b>';
    $ekstensi = 'jpg'; // default
    $lokasi_target = $lokasi_img; // default

    // exception custom handlers
    if ($field == 'header_logo') {
      $file_awal = $header_logo;
      $ekstensi = 'png';
    } else if ($field == 'bg_hero') {
      $file_awal = $bg_hero;
    } else {
      echo div_alert('warning', "Perhatian! Default handler untuk file <b>$field</b>. Eksetensi: JPG. Lokasi default at $lokasi_img");
    }

    $new_file = $field . '_' . date('ymdhis') . ".$ekstensi";
    $target = "$lokasi_target/$new_file";
    $target_lama = "$lokasi_target/$file_awal";

    echolog('handling file');
    echolog("File awal: $file_awal");
    echolog("File baru: $new_file");
    echolog("Target lokasi: $target");

    if (move_uploaded_file($source, $target)) {
      echolog('move_uploaded_file berhasil', false);
      $s = "SELECT 1 FROM tb_klinik_data WHERE field='$field' AND id_klinik=$id_klinik";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

      if (mysqli_num_rows($q)) {
        $s = "UPDATE tb_klinik_data SET value='$new_file' WHERE field='$field' AND id_klinik=$id_klinik";
        echolog('updating');
      } else {
        echolog("inserting new $field");
        $s = "INSERT INTO tb_klinik_data (
              id_klinik,
              field,
              value,
              modified_by,
              section
            ) VALUES (
              $id_klinik,
              '$field',
              '$new_file',
              $id_user,
              '$section'
            )";
      }
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }

    echo div_alert('success', "Replace file $field.$ekstensi sukses.");
    jsurl('', 3000);

    exit;
  } else {
    echolog('Zero file-size detected. File handler skipped');
  }
}
