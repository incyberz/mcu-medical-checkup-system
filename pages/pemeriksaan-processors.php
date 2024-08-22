<?php
if (isset($_POST['btn_submit_data_pasien'])) {
  $id_pasien = $_POST['btn_submit_data_pasien'] ?? die('index [id_pasien] undefined');
  $sampel = $_POST['sampel'] ?? '';
  if ($sampel) {
    # ============================================================
    # SAMPEL PROCESSOR
    # ============================================================
    // $arr_sampel_tanggal_by[$sampel] = "$sampel=" . date('Y-m-d H:i:s') . ",$id_user";
    array_push($arr_sampel_tanggal_by, "$sampel=" . date('Y-m-d H:i:s') . ",$id_user");

    # ============================================================
    # ARRAY SORT BY KEY
    # ============================================================
    // ksort($arr_sampel_tanggal_by);
    // "1,1,1,1,1,2,1,1,1,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,"

    # ============================================================
    # CONVERT TO STRING
    # ============================================================
    $pairs = [];
    echolog('converting to string');
    $str_sampel = '';
    foreach ($arr_sampel_tanggal_by as $key => $value) {
      if ($value) $str_sampel .= "$value||";
    }
    $pairs['arr_sampel'] = "arr_sampel='$str_sampel'";

    $str_pairs = join(',', $pairs);

    $s = "UPDATE tb_hasil_pemeriksaan SET 
      $str_pairs,
      last_update = CURRENT_TIMESTAMP,
      status = 2 -- status hasil sedang diinput
    WHERE id_pasien=$id_pasien";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echolog('sukses');
  } else {
    # ============================================================
    # PEMERIKSAAN PROCESSORS
    # ============================================================
    $last_pemeriksaan = $_POST['last_pemeriksaan'] ?? die('index [last_pemeriksaan] undefined');
    $id_pemeriksaan = $_POST['id_pemeriksaan'] ?? die('index [id_pemeriksaan] undefined');
    if ($id_pemeriksaan == 9) { // KHUSUS RONTGEN
      $id_detail = 134;
      $value = trim($_POST['catatan__by_system']);
      if ($_POST['kesan__tambahan']) {
        $kesan_tambahan = trim($_POST['kesan__tambahan']);
        if (strpos($kesan_tambahan, 'normal')) {
          die(div_alert('danger', "Mohon tidak ada kata-kata `normal` atau `abnormal` pada catatan tambahan karena telah dijadikan keyword dalam penentuan kesimpulan pemeriksaan. Anda dapat mengganti kata `abnormal` dengan kata `terdapat kelainan`"));
        }
        $value .= ", kesan_tambahan: $kesan_tambahan";
      }

      $value = $value ? $value : 'normal';
      $arr_id_detail[$id_detail] = $value;
      // exit;
    } else { // bukan rontgen
      unset($_POST['btn_submit_data_pasien']);
      unset($_POST['last_pemeriksaan']);
      unset($_POST['id_pemeriksaan']);

      # ============================================================
      # UPDATE ARRAY ID DETAIL WITH DATA POSTS
      # ============================================================
      echolog('updating array hasil');
      foreach ($_POST as $key => $value) {
        $arr_id_detail[$key] = $value;
      }
    }

    # ============================================================
    # UPDATE ARRAY ID PEMERIKSAAN
    # ============================================================
    $arr_id_pemeriksaan_tanggal[$id_pemeriksaan] = date('Y-m-d H:i:s') . ",$id_user";

    # ============================================================
    # ARRAY SORT BY KEY
    # ============================================================
    ksort($arr_id_detail);
    // ksort($arr_id_pemeriksaan_tanggal);


    # ============================================================
    # CONVERT TO STRING
    # ============================================================
    $pairs = [];
    echolog('converting to string');
    $str_hasil = '';
    $str_tanggal_by = '';
    foreach ($arr_id_detail as $key => $value) {
      if ($value === '' || $value === null) continue;
      $str_hasil .= "$key=$value||";
    }
    foreach ($arr_id_pemeriksaan_tanggal as $key => $value) {
      if ($value) $str_tanggal_by .= "$key=$value||";
    }

    $pairs['arr_hasil'] = "arr_hasil='$str_hasil'";
    $pairs['arr_tanggal'] = "arr_tanggal_by='$str_tanggal_by'";



    $str_pairs = join(',', $pairs);

    $s = "UPDATE tb_hasil_pemeriksaan SET 
      $str_pairs,
      last_pemeriksaan = '$last_pemeriksaan',
      last_update = CURRENT_TIMESTAMP,
      status = 2 
    WHERE id_pasien=$id_pasien";
    echolog($s);
    // exit;
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    echolog('sukses');
  }











  # ============================================================
  # UPDATE status pasien 
  # ============================================================
  $s = "UPDATE tb_pasien SET status=9 -- sedang pemeriksaan
  WHERE id=$id_pasien  AND status <= 9
  ";
  echolog('updating status pasien');
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  jsurl();
  exit;
}
