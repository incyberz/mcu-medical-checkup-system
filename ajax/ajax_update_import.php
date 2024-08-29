<?php
# =============================================================
# UPDATE | DELETE PEMERIKSAAN
# =============================================================
include 'ajax_session.php';
ajax_only(['admin', 'marketing']);

$id_pasien = $_GET['id_pasien'] ?? die(erid('id_pasien'));
$arr_hasil = $_GET['arr_hasil'] ?? die(erid('arr_hasil'));
$arr_tanggal_by = $_GET['arr_tanggal_by'] ?? die(erid('arr_tanggal_by'));
$id_import = $_GET['id_import'] ?? die(erid('[id_import]'));
$tb = $_GET['tb'] ?? die(erid('[tb]'));


$s = "SELECT arr_hasil, arr_tanggal_by FROM tb_hasil_pemeriksaan WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data hasil tidak ditemukan'));
$d = mysqli_fetch_assoc($q);

# ============================================================
# MODE REPLACE :: NEW STRING HASIL
# ============================================================
$arr = explode('||', strip_tags($d['arr_hasil']));
$arr_tmp_hasil = [];
foreach ($arr as $serpihan) {
  if ($serpihan) {
    $arr2 = explode('=', $serpihan, 2);
    $arr_tmp_hasil[$arr2[0]] = $arr2[1];
  }
}

$arr = explode('||', strip_tags($arr_hasil));
$arr_tmp_new_hasil = [];
foreach ($arr as $serpihan) {
  if ($serpihan) {
    $arr2 = explode('=', $serpihan, 2);
    $arr_tmp_new_hasil[$arr2[0]] = $arr2[1];
  }
}


foreach ($arr_tmp_new_hasil as $key => $value) {
  if (key_exists($key, $arr_tmp_hasil)) {
    # ============================================================
    # REPLACE DENGAN NILAI BARU
    # ============================================================
    // echo "\nZZZ $key : $arr_tmp_hasil[$key] >> $arr_tmp_new_hasil[$key]";
    $arr_tmp_hasil[$key] = $arr_tmp_new_hasil[$key];
  } else {
    // echo "\nZZZ key: $key | INSERTING NEW value: $value";
    $arr_tmp_hasil[$key] = $value;
  }
}

ksort($arr_tmp_hasil);
$new_str_hasil = '';
foreach ($arr_tmp_hasil as $key => $value) {
  $new_str_hasil .= "$key=$value||";
}

# ============================================================
# MODE REPLACE :: NEW TANGGAL BY
# ============================================================
$arr = explode('||', strip_tags($d['arr_tanggal_by']));
$arr_tmp_tanggal_by = [];
foreach ($arr as $serpihan) {
  if ($serpihan) {
    $arr2 = explode('=', $serpihan, 2);
    $arr_tmp_tanggal_by[$arr2[0]] = $arr2[1];
  }
}

$arr = explode('||', strip_tags($arr_tanggal_by));
$arr_tmp_new_tanggal_by = [];
foreach ($arr as $serpihan) {
  if ($serpihan) {
    $arr2 = explode('=', $serpihan, 2);
    $arr_tmp_new_tanggal_by[$arr2[0]] = $arr2[1];
  }
}
// echo '<pre>';
// var_dump($arr);
// echo '</pre>';
// exit;

// foreach ($arr_tmp_tanggal_by as $key => $value) {
//   if (key_exists($key, $arr_tmp_new_tanggal_by)) {
//     # ============================================================
//     # REPLACE DENGAN NILAI BARU
//     # ============================================================
//     // echo "\nZZZ $key : $arr_tmp_tanggal_by[$key] >> $arr_tmp_new_tanggal_by[$key]";
//     $arr_tmp_tanggal_by[$key] = $arr_tmp_new_tanggal_by[$key];
//   }
// }

foreach ($arr_tmp_new_tanggal_by as $key => $value) {
  if (key_exists($key, $arr_tmp_tanggal_by)) {
    # ============================================================
    # REPLACE DENGAN NILAI BARU
    # ============================================================
    // echo "\nZZZ $key : $arr_tmp_tanggal_by[$key] >> $arr_tmp_new_tanggal_by[$key]";
    $arr_tmp_tanggal_by[$key] = $arr_tmp_new_tanggal_by[$key];
  } else {
    // echo "\nZZZ key: $key | INSERTING NEW value: $value";
    $arr_tmp_tanggal_by[$key] = $value;
  }
}


ksort($arr_tmp_tanggal_by);
$new_str_tanggal_by = '';
foreach ($arr_tmp_tanggal_by as $key => $value) {
  $new_str_tanggal_by .= "$key=$value||";
}



# ============================================================
# UPDATING
# ============================================================
$s = "UPDATE tb_hasil_pemeriksaan SET 
arr_hasil='$new_str_hasil',
arr_tanggal_by='$new_str_tanggal_by'
 
WHERE id_pasien=$id_pasien";
// echo '<pre>';
// var_dump($s);
// echo '</pre>';
// exit;
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



# ============================================================
# LANJUTKAN KE DELETE
# ============================================================
$s = "DELETE FROM tb_import_$tb WHERE Sample_ID = $id_import";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo 'sukses';
