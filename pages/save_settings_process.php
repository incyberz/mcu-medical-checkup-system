<?php
$pesan_save = '';
if (isset($_POST['btn_save_settings'])) {

  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  echo "<section><div class=container>";
  $section = $_POST['btn_save_settings'];
  unset($_POST['btn_save_settings']);



  if ($section) {

    // auto save new section
    $s = "SELECT 1 FROM tb_section WHERE section='$section'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) {
      $s = "INSERT INTO tb_section (section) VALUES ('$section')";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      echolog('add new section success');
    }


    // handle file data
    include 'save_settings_file_handler.php';

    $data_array = []; // penampung data array, dipisahkan dg ~~~ 

    // save post data
    foreach ($_POST as $key => $value) {
      // if null then skip
      if (!$value) continue;

      // if array then loop
      if (is_array($value)) {
        // array value handler
        echo div_alert('info', "Manage array value: <span class='darkblue miring'>$key</span>");
        $arr2 = $value;
        // echo '<pre>';
        // var_dump($arr2);
        // echo '</pre>';

        foreach ($arr2 as $key2 => $value2) {
          if (!$value2) continue; // jika sub-value null maka skip
          echolog("handling $key #" . ($key2 + 1));
          if (isset($data_array[$key2])) {
            $data_array[$key2] .= "$value2~~~";
          } else {
            $data_array[$key2] = "$value2~~~";
          }
        }

        // echo '<pre>';
        // var_dump($data_array);
        // echo '</pre>';
      } else {
        // value normal || value non array
        $value = clean_sql($value);
        $value = strip_tags($value); // hilangkan tag html

        $s = "SELECT 1 FROM tb_klinik_data WHERE id_klinik=$id_klinik AND section='$section' AND field='$key'";
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
        if (mysqli_num_rows($q)) {
          echolog("settings exists... updating $key");
          // setting sudah ada
          $s = "UPDATE tb_klinik_data SET value='$value' WHERE id_klinik=$id_klinik AND field='$key'";
        } else {
          echolog("settings not exists... inserting $key");
          $s = "INSERT INTO tb_klinik_data (
            id_klinik,
            field,
            value,
            modified_by,
            section
          ) VALUES (
            $id_klinik,
            '$key',
            '$value',
            $id_user,
            '$section'
          )";
        }
        $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      }
    }

    $count = count($data_array);
    echo div_alert('success', "Persiapan inserting $count array data...");
    foreach ($data_array as $key => $value_as_array) {

      // cek kelengkapan konten
      $arr_tmp = explode('~~~', $value_as_array);
      if (!$arr_tmp[0] || !$arr_tmp[1] || !$arr_tmp[2]) {
        echolog('Kelengkapan konten keunggulan belum lengkap... skipped');
        continue;
      }

      $value_as_array = clean_sql($value_as_array);
      $no = $key + 1;
      $field = "$section-$no";

      // cek jika field sudah ada
      $s = "SELECT 1 FROM tb_klinik_data WHERE id_klinik=$id_klinik AND field='$field'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

      if (mysqli_num_rows($q)) {
        $s = "UPDATE tb_klinik_data SET value='$value_as_array' WHERE id_klinik=$id_klinik AND field='$field'";
        echolog("Updating $field");
      } else {
        echolog("Inserting $field");
        $s = "INSERT INTO tb_klinik_data (
            id_klinik,
            field,
            value,
            modified_by,
            section
          ) VALUES (
            $id_klinik,
            '$field',
            '$value_as_array',
            $id_user,
            '$section'
          )";
      }
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }

    $pesan_save .= div_alert('success', "<a href='?'>Home</a> | Saving data section $section sukses.");
    echo $pesan_save;
    $url = $section == 'hero' ? '' : $section;
    jsurl("?$url", 5000);
    exit;
  } else {
    echo div_alert('danger', "Section belum didefinisikan pada tombol simpan/upload.");
  }
  echo "</div></section>";
}
