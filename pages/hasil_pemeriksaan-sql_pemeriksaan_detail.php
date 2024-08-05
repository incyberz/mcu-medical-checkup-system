<?php
# ============================================================
# SQL PEMERIKSAAN DETAIL
# ============================================================
$fields = "
  e.id as id_pemeriksaan,
  e.nama as nama_pemeriksaan,
  e.singkatan,
  e.sampel,
  e.jenis,
  f.nama as jenis_pemeriksaan,
  (SELECT COUNT(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=e.id) count_pemeriksaan_detail
";

$joins = "
  JOIN tb_pemeriksaan e ON d.id_pemeriksaan=e.id 
  JOIN tb_jenis_pemeriksaan f ON e.jenis=f.jenis 
  WHERE a.id=$id_pasien 
  -- AND e.jenis='$get_jenis'
";

if ($JENIS == 'COR') {
  if ($id_harga_perusahaan) {
    $s = "SELECT 
    2 as status_bayar, -- status bayar 2 = corporate
    $fields
    FROM tb_pasien a 
    JOIN tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id 
    JOIN tb_paket c ON b.id_paket=c.id 
    JOIN tb_paket_detail d ON d.id_paket=c.id 
    $joins
    ";
  } elseif ($id_paket_custom) {
    $s = "SELECT 
    2 as status_bayar, -- status bayar 2 = corporate
    $fields
    FROM tb_pasien a 
    JOIN tb_order b ON a.order_no=b.order_no 
    JOIN tb_paket c ON b.id_paket=c.id 
    JOIN tb_paket_detail d ON d.id_paket=c.id 
    $joins
    ";
  }
} else { // pasien non COR
  $s = "SELECT 
  c.status_bayar,
  $fields
  FROM tb_pasien a 
  JOIN tb_paket_custom c ON a.id_paket_custom=c.id  
  JOIN tb_paket_custom_detail d ON d.id_paket_custom=c.id 
  $joins
  ";
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die('Belum ada data pemeriksaan untuk pasien ini');
$jumlah_row = mysqli_num_rows($q);
while ($d = mysqli_fetch_assoc($q)) {
  $id_pemeriksaan = $d['id_pemeriksaan'];
  $jenis_pemeriksaan = $d['jenis_pemeriksaan'];
  $nama_pemeriksaan = $d['nama_pemeriksaan'];
  // exit;
  if (strtolower($d['jenis']) != 'mcu') {
    array_push($arr_id_pemeriksaan_penunjang, $d['id_pemeriksaan']);

    // jika sesuai yang diminta
    if (strtolower($d['jenis']) == $get_jenis) {
      $is_mcu = 0;
      break;
    }
  } else {
    $is_mcu = 1;
  }
}
