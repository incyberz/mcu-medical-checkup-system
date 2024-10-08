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
  } elseif ($order_no) {
    $s = "SELECT 
    2 as status_bayar, -- status bayar 2 = corporate
    $fields
    FROM tb_pasien a 
    JOIN tb_order b ON a.order_no=b.order_no 
    JOIN tb_paket c ON b.id_paket=c.id 
    JOIN tb_paket_detail d ON d.id_paket=c.id 
    $joins
    ";
  } else {
    die(div_alert('danger', "JENIS [COR] tidak mempunyai [id_harga_perusahaan] ataupun [order_no]"));
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
$q_detail = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q_detail)) die('Belum ada data pemeriksaan untuk pasien ini');
$jumlah_row = mysqli_num_rows($q_detail);
while ($d_detail = mysqli_fetch_assoc($q_detail)) {
  // $id_pemeriksaan = $d_detail['id_pemeriksaan']; // untuk apa tujuan extract???
  // $jenis_pemeriksaan = $d_detail['jenis_pemeriksaan']; // untuk apa tujuan extract???
  // $nama_pemeriksaan = $d_detail['nama_pemeriksaan']; // untuk apa tujuan extract???
  // exit;
  if (strtolower($d_detail['jenis']) != 'mcu') {
    array_push($arr_id_pemeriksaan_penunjang, $d_detail['id_pemeriksaan']);

    // jika sesuai yang diminta | apap yg diminta ???
    if (strtolower($d_detail['jenis']) == $get_jenis) {
      // $is_mcu = 0; // is_mcu sudah dihandle di page lain
      // break;
    }
  } else {
    // $is_mcu = 1;
  }
}
