<?php
$pesan_save = '';
if (isset($_POST['btn_save_settings'])) {
  echo "<section><div class=container>";
  $section = $_POST['btn_save_settings'];
  unset($_POST['btn_save_settings']);

  // auto save new section
  $s = "SELECT 1 FROM tb_section WHERE section='$section'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    $s = "INSERT INTO tb_section (section) VALUES ('$section')";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echolog('add new section success');
  }


  if ($section) {

    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    foreach ($_POST as $key => $value) {
      $value = clean_sql($value);
      $value = strip_tags($value); // hilangkan tag html

      echolog('looping POST data and check previous settings');
      $s = "SELECT 1 FROM tb_klinik_data WHERE id_klinik=$id_klinik AND section='$section' AND field='$key'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (mysqli_num_rows($q)) {
        echolog("settings exists... updating $key");
        // setting sudah ada
        $s = "UPDATE tb_klinik_data SET value='$value' WHERE id_klinik=$id_klinik AND field='$key'";
      } else {
        echolog("settings not exists... inserting $key");
        $s = "INSERT INTO tb_klinik_data (id_klinik,field,value,modified_by,section) VALUES ($id_klinik,'$key','$value',$id_user,'$section')";
      }
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }

    $pesan_save .= div_alert('success', "<a href='?'>Home</a> | Saving data section $section sukses.");
    echo $pesan_save;
    jsurl('', 5000);
    exit;
  } else {
    echo div_alert('danger', "Section $section belum didefinisikan di klinik ini.");
  }
  echo "</div></section>";
}
