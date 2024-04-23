<?php
$pesan_save = '';
if (isset($_POST['btn_save_settings'])) {
  $page = $_POST['btn_save_settings'];
  if ($page) {

    $pesan_save .= div_alert('danger', '<a href="?">Home</a> | Proses editing section masih dalam tahap pengembangan. Terimakasih sudah mencoba!');
    echo "<section><div class=container>$pesan_save</div></section>";
    exit;
  }
}
