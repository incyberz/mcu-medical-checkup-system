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
$arr_hasil = "$arr_hasil$d[arr_hasil]";
$arr_tanggal_by = "$arr_tanggal_by$d[arr_tanggal_by]";

$s = "UPDATE tb_hasil_pemeriksaan SET 
arr_hasil='$arr_hasil',
arr_tanggal_by='$arr_tanggal_by'
 
WHERE id_pasien=$id_pasien";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



# ============================================================
# LANJUTKAN KE DELETE
# ============================================================
$s = "DELETE FROM tb_import_$tb WHERE Sample_ID = $id_import";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo 'sukses';
