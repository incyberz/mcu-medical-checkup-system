<?php
# =============================================================
# UPDATE | DELETE PEMERIKSAAN
# =============================================================
include 'ajax_session.php';
ajax_only(['admin', 'marketing', 'nakes', 'dokter', 'dokter-pj']);

$id_pasien = $_GET['id_pasien'] ?? die('undefined index id_pasien');
$no_urut_awal = $_GET['no_urut_awal'] ?? die('undefined index no_urut_awal');
$no_urut_baru = $_GET['no_urut_baru'] ?? die('undefined index no_urut_baru');
$id_perusahaan = $_GET['id_perusahaan'] ?? die('undefined index id_perusahaan');



if (!$no_urut_awal) die('no_urut_awal is null');
if (!$no_urut_baru) die('no_urut_baru is null');
if (!$id_perusahaan) die('id_perusahaan is null');
if ($no_urut_awal == $no_urut_baru) die('nomor urut sama.');

$id_perusahaan *= 1000;
$no_urut_baru += $id_perusahaan;
$no_urut_awal += $id_perusahaan;

if ($no_urut_baru > $no_urut_awal) {
  $s = "UPDATE tb_pasien SET urutan = urutan - 1 WHERE urutan > $no_urut_awal AND urutan <= $no_urut_baru";
} else {
  $s = "UPDATE tb_pasien SET urutan = urutan + 1 WHERE urutan >= $no_urut_baru AND urutan < $no_urut_awal";
}
// die("
// id_perusahaan: $id_perusahaan, 
// no_urut_awal: $no_urut_awal, 
// no_urut_baru: $no_urut_baru, 
// id_pasien: $id_pasien, 
// <hr> 
// $s
// ");

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));


// Update urutan pasien yang dipindah
$stmt = $cn->prepare("UPDATE tb_pasien SET urutan = ? WHERE id = ?");
$stmt->bind_param("ii", $no_urut_baru, $id_pasien);
$stmt->execute();


echo 'sukses';
