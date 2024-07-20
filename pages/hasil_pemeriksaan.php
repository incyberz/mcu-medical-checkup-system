<?php
set_title("Hasil Pemeriksaan");
only('users');
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));
$get_jenis = $_GET['jenis'] ?? die(div_alert('danger', "Page ini membutuhkan index [jenis]"));

# ============================================================
# INCLUDES
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/arr_user.php';


# ===========================================================
# HASIL (IF EXISTS)
# ===========================================================
$arr_id_detail = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];
$arr_sampel_tanggal = [];
$arr_sampel_by = [];
include 'pemeriksaan-hasil_at_db.php';















# ============================================================
# DATA PASIEN
# ============================================================
$s = "SELECT * FROM tb_pasien WHERE id=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$order_no = '';
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', 'Data pasien tidak ditemukan'));
} else {
  $pasien = mysqli_fetch_assoc($q);
  $order_no = $pasien['order_no'];
  $jenis = $pasien['jenis'];
  $JENIS = strtoupper($jenis);
}







# ============================================================
# DATA PAKET PEMERIKSAAN
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
  AND e.jenis='$get_jenis'
";

if ($JENIS == 'COR') {
  $s = "SELECT 
  2 as status_bayar, -- status bayar 2 = corporate
  $fields
  FROM tb_pasien a 
  JOIN tb_order b ON a.order_no=b.order_no 
  JOIN tb_paket c ON b.id_paket=c.id 
  JOIN tb_paket_detail d ON d.id_paket=c.id 
  $joins
  ";
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
$is_mcu = 0;
$jumlah_row = mysqli_num_rows($q);

while ($d = mysqli_fetch_assoc($q)) {
  $id_pemeriksaan = $d['id_pemeriksaan'];
  $jenis_pemeriksaan = $d['jenis_pemeriksaan'];
  $jenis = strtolower($d['jenis']);
  echo "<br>$jenis ROW: $jumlah_row";
  // exit;
  $file = "$lokasi_pages/hasil_pemeriksaan-$jenis.php";
  if ($jenis != 'mcu') {
    if (file_exists($file)) {
      include $file;
    } else {

      echo div_alert('danger', "Belum ada Format Hasil Pemeriksaan untuk jenis: $jenis_pemeriksaan");
    }
  } else {
    $is_mcu = 1;
  }
}

if ($is_mcu) {
  include 'hasil_pemeriksaan-mcu.php';
}
