<?php
set_title("Hasil Pemeriksaan");
only('users');
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));
$get_jenis = $_GET['jenis'] ?? die(div_alert('danger', "Page ini membutuhkan index [jenis]"));
$get_jenis = strtolower($get_jenis);
$kesimpulan = [];
$kesimpulan_penunjang = [];
$arr_id_pemeriksaan_penunjang = [];
$dokter_pj = '<span class="tebal merah">UNKNOWN</span>';
$tidak_ada = '<i class=hasil>--tidak ada--</i>';

# ============================================================
# INCLUDES
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/arr_user.php';
include 'include/arr_pemeriksaan.php';
include 'include/arr_pemeriksaan_detail.php';
include 'hasil_pemeriksaan-functions.php';
include 'hasil_pemeriksaan-styles.php';


# ============================================================
# HASIL MEDICAL AT DB
# ============================================================
$hasil = [];
$arr_id_detail = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];
$arr_sampel_tanggal = [];
$arr_sampel_by = [];
include 'pemeriksaan-hasil_at_db.php';
$dokter_pj = $arr_user[$arr_pemeriksaan_by[8]];















# ============================================================
# DATA PASIEN
# ============================================================
$s = "SELECT a.*, 
(SELECT perusahaan FROM tb_order  WHERE order_no=a.order_no) perusahaan  
FROM tb_pasien a 
WHERE a.id=$id_pasien";
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
  -- AND e.jenis='$get_jenis'
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
  $nama_pemeriksaan = $d['nama_pemeriksaan'];
  $jenis = strtolower($d['jenis']);
  // exit;
  if ($jenis != 'mcu') {
    array_push($arr_id_pemeriksaan_penunjang, $id_pemeriksaan);

    // jika sesuai yang diminta
    if ($jenis == $get_jenis) {
      $is_mcu = 0;
      break;
    }
  } else {
    $is_mcu = 1;
  }
}



# ============================================================
# DIV HEADER
# ============================================================
$div_header = '';
include 'hasil_pemeriksaan-header.php';

# ============================================================
# DETAIL PEMERIKSAAN
# ============================================================
$MC = $is_mcu ? 'MEDICAL CHECKUP' : 'PEMERIKSAAN ' . strtoupper($nama_pemeriksaan);
echo "
  <div class='wadah gradasi-hijau tengah'>
    <div class='f30 abu mb2 mt4'>Preview Hasil Laboratorium</div>
    <a href='?tampil_pasien&id_pasien=$id_pasien'>$img_prev</a>
    <div class='flexy flex-center f12 mt2'>
      <div class='kertas bg-white p4 mt2' id=kertas__mcu>
        <div>$img_header_logo</div>
        <div class='border-bottom mb2 pb2 f12 mt1'>Tambun Business Park Blok C12 Tambun - Bekasi<br>Telp.(021) 29487893</div>
        
        <h3 class='p1 f16 bold'>HASIL $MC</h3>

        $div_header
        ";


# ============================================================
# PEMERIKSAAN INTI
# ============================================================
if ($is_mcu) {
  include 'hasil_pemeriksaan-mcu.php';
} else {
  include 'hasil_pemeriksaan-lab.php';
}


# ============================================================
# FOOTER
# ============================================================
include 'hasil_pemeriksaan-footer.php';


echo "
      </div>
    </div>
    <button class='btn btn-primary  mt3' onclick=window.print()>Print</button>
  </div>
";
