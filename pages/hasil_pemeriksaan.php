<?php


$id_pasien_session = $_SESSION['mcu_id_pasien'] ?? null;
if ($id_pasien_session) {
  $id_pasien = $id_pasien_session;
  echo "
    <style>
      header,
      .admin_only,
      #topbar
      {
        display:none !important;
      }
    </style>
    <div class='mb4 tengah ' style='margin-top:-30px'>
      <div class='biru tebal'>Jika menggunakan handphone, pastikan Seting Browser: Mode Desktop</div>
      <div class='mt4 mb2'>Lihat Tutorial:</div>
      <a class='btn btn-sm btn-success mb2' href='https://youtu.be/XJ771E_a9E8' target=_blank>Save as PDF via Handphone</a>
      <br>
      <a class='btn btn-sm btn-success mb2' href='https://youtu.be/mnKlZ_kUtIg' target=_blank>Save as PDF via Laptop</a>
    </div>
  ";
} else {
  set_title("Hasil Pemeriksaan");
  only('users');
  $id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));
}
$get_jenis = $_GET['jenis'] ?? die(div_alert('danger', "Page ini membutuhkan index [jenis]"));
$get_jenis = strtolower($get_jenis);
$kesimpulan = [];
$kesimpulan_penunjang = [];
$arr_id_pemeriksaan_penunjang = [];
$dokter_pj = '<span class="tebal merah">UNKNOWN</span>';
$tidak_ada = '<i class=hasil>--tidak ada--</i>';
$abnormal_count = 0;
$unfit_count = 0;

if ($get_jenis == 'mcu') {
  $id_pemeriksaan = 8;
}
// } else {
//   $s = "SELECT id as id_pemeriksaan, nama FROM tb_pemeriksaan WHERE jenis='$get_jenis' ";
//   $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
//   while ($d = mysqli_fetch_assoc($q)) {
//     echo "<br>$d[id_pemeriksaan] $d[nama]";
//     // $id=$d['id'];
//     $id_pemeriksaan = $d['id_pemeriksaan'];
//   }
// }
// exit;


# ============================================================
# PROCESSORS 
# ============================================================
if (isset($_POST['btn_approve'])) {
  $s = "UPDATE tb_hasil_pemeriksaan SET 
  hasil=$_POST[hasil],
  approv_date = CURRENT_TIMESTAMP,
  approv_by = $id_user
  WHERE id_pasien=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', 'Update hasil sukses.');
  jsurl("?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$get_jenis");
}

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
  $id_harga_perusahaan = $pasien['id_harga_perusahaan'];
  $id_paket_custom = $pasien['id_paket_custom'];
  $jenis = $pasien['jenis'];
  $gender = strtolower($pasien['gender']);
  $JENIS = strtoupper($jenis);
}


// if (!$gender) {
//   echo '<pre>';
//   var_dump($gender);
//   echo '</pre>';
//   die(div_alert('danger', "Membutuhkan gender untuk kalkulasi"));
// }








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
$is_mcu = 0;
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
    <div class='admin_only'>
      <a href='?tampil_pasien&id_pasien=$id_pasien'>$img_prev</a>
      <a href='?pemeriksaan&id_pemeriksaan=$id_pemeriksaan&id_pasien=$id_pasien'>$img_edit</a>
    </div>
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
} elseif ($id_pemeriksaan == 9) {
  include 'hasil_pemeriksaan-rontgen.php';
} else {
  include 'hasil_pemeriksaan-lab.php';
}


# ============================================================
# FOOTER
# ============================================================
$re_approv = $_GET['re_approv'] ?? '';
$Re_ = $re_approv ? 'Re -' : '';
if ($hasil_at_db['approv_date'] and !$re_approv) {
  include 'hasil_pemeriksaan-footer.php';
} else {
  if (!$re_approv) echo div_alert('danger tengah', 'BELUM DIVERIFIKASI');
  if ($role == 'dokter-pj') {

    require_once 'include/radio_toolbar_functions.php';
    $arr_radio = [
      '0' => [
        'id_detail' => 'hasil',
        'id' => 'hasil__0',
        'option_class' => 'hasil',
        'option_value' => '0',
        'checked' => '',
        'caption' => 'Unfit',
      ],
      '2' => [
        'id_detail' => 'hasil',
        'id' => 'hasil__2',
        'option_class' => 'hasil',
        'option_value' => '2',
        'checked' => 'checked',
        'caption' => 'Fit with Medical Note',
      ],
      '1' => [
        'id_detail' => 'hasil',
        'id' => 'hasil__1',
        'option_class' => 'hasil',
        'option_value' => '1',
        'checked' => '',
        'caption' => 'Fit On Job',
      ],
    ];
    $radios = radio_toolbar($arr_radio);

    // $hasil_at_db['hasil'] = 1; // test

    $arr_hasil_at_db = [
      0 => 'Unfit',
      1 => 'Fit On Job',
      2 => 'Fit with Medical Note',
    ];

    $by = $arr_user[$hasil_at_db['approv_by']] ?? '';
    $hasil_at_db_show = $arr_hasil_at_db[$hasil_at_db['hasil']] ?? '';
    $hasil_at_db_show = $hasil_at_db['hasil'] ? "<div class=wadah>Kesimpulan: <b class='darkblue f24'>$hasil_at_db_show</b> <br>at $hasil_at_db[approv_date]  by $by </div>" : '';

    $hari = hari_tanggal();

    if ($get_jenis == 'mcu') {
      $blok_approve = "
        $radios
        <div class=kiri>
          <label style='display:block' class='m2'>
            <input type=checkbox required >
            Saya telah membaca seluruh hasil pemeriksaan dengan seksama 
          </label>
          <label style='display:block' class='m2'>
            <input type=checkbox required >
            Saya menyatakan bahwa kesimpulan pemeriksaan sudah benar 
          </label>
        </div>
        <div class=wadah>
          <div>Approve by <b>$nama_user</b> at <b>$hari</b> </div>
          <button class='btn btn-primary mt4' name=btn_approve value=$re_approv>$Re_ Approve</button>
        </div>
      ";
    } else {
      $blok_approve = "
        Silahkan Approve pada <a href='?hasil_pemeriksaan&id_pasien=1&jenis=mcu'>Hasil Pemeriksaan MCU</a>
      ";
    }

    echo "
      <form method=post class='wadah gradasi-kuning'>
        $hasil_at_db_show
        $blok_approve
      </form>
    ";
  }
}
$btn = "<div class='mt3 red f12'>Belum bisa Print Hasil karena belum diverifikasi oleh Dokter Penanggung jawab.</div>";
if ($hasil_at_db['approv_date']) {
  $nama_file = "hasil-$get_jenis-$no_mcu-$nama_pasien.pdf";
  $nama_file = str_replace(' ', '_', $nama_file);
  $nama_file = strtolower($nama_file);
  $btn_re_approv = $role != 'dokter-pj' ? '' : "<div><a class='btn btn-danger' href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$get_jenis&re_approv=1'>Re-Approve</a></div>";
  $btn_re_approv = ''; // aborted
  $btn = "
      <div class='flexy flex-center mt3'>
        <div class=admin_only>Filename:</div>
        <div class=admin_only>
          <input class='form-control js-copytextarea' value='$nama_file' />
        </div>
        <div class=admin_only>
          <button class='btn btn-success js-textareacopybtn' id=btn_copy>Copy</button>
        </div>
        <div>
          <button class='btn btn-primary' onclick=window.print()>Print</button>
        </div>
        $btn_re_approv
      </div>
  ";
}

echo "
      </div>
    </div>

    $btn
  </div>
";


?>


<script>
  var copyTextareaBtn = document.querySelector('.js-textareacopybtn');

  copyTextareaBtn.addEventListener('click', function(event) {
    var copyTextarea = document.querySelector('.js-copytextarea');
    copyTextarea.focus();
    copyTextarea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
    } catch (err) {
      console.log('Oops, unable to copy');
    }
  });
</script>