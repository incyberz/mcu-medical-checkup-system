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
$dokter_pj = '<span class="tebal merah">UNKNOWN</span>';
$tidak_ada = '<i class=hasil>--tidak ada--</i>';
$no_data = '<i class=hasil>--no data--</i>';
$abnormal_count = 0;
$unfit_count = 0;

if ($get_jenis == 'mcu') {
  $id_pemeriksaan = 8;
} else {
  $id_pemeriksaan = $_GET['id_pemeriksaan'] ?? die(erid('[id_pemeriksaan]'));
}

// fixed get jenis diambil dari DB berdasarkan id pemeriksaan
$s = "SELECT jenis FROM tb_pemeriksaan WHERE id = $id_pemeriksaan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$get_jenis = $d['jenis'];




$s = "SELECT * FROM tb_hasil_pemeriksaan WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$hasil = mysqli_fetch_assoc($q);
$approv_labs = $hasil['approv_labs'];

# ============================================================
# LAB VERIFIED STATUS
# ============================================================
$verified_lab = strpos("salt||$approv_labs", "||$id_pemeriksaan=") ? 1 : 0;
































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
  jsurl("?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$get_jenis&id_pemeriksaan=$id_pemeriksaan");
}

if (isset($_POST['btn_approve_lab'])) {
  $approv_labs = "$id_pemeriksaan=1,$now,$id_user||$approv_labs";
  $s = "UPDATE tb_hasil_pemeriksaan SET approv_labs = '$approv_labs' WHERE id_pasien=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', 'Update hasil lab sukses.');
  jsurl("?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$get_jenis&id_pemeriksaan=$id_pemeriksaan", 1000);
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
$dokter_pj = 'dr. Mutiara Putri Camelia';
echo 'zzz3';















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










# ============================================================
# DATA PAKET PEMERIKSAAN
# ============================================================
$arr_id_pemeriksaan_penunjang = [];
$is_mcu = 0;
// $s = 'SELECT string (will be replaced)';
include 'hasil_pemeriksaan-sql_pemeriksaan_detail.php';
echo 'zzz4';


$arr_id_pemeriksaan_mcu = [];
$s = "SELECT id FROM tb_pemeriksaan WHERE jenis='mcu'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  if ($id_pemeriksaan == $d['id']) {
    $is_mcu = 1;
    $nama_pemeriksaan = "Medical Checkup";
    $jenis_pemeriksaan = "MCU";
    break;
  }
}
if (!$is_mcu) {
  $s = "SELECT a.*,b.nama as jenis_pemeriksaan FROM tb_pemeriksaan a JOIN tb_jenis_pemeriksaan b ON a.jenis=b.jenis WHERE a.id=$id_pemeriksaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data [pemeriksaan] tidak ditemukan'));
  $pemeriksaan = mysqli_fetch_assoc($q);
  $nama_pemeriksaan = $pemeriksaan['nama'];
  $jenis_pemeriksaan = $pemeriksaan['jenis_pemeriksaan'];
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
  echo 'zzz7';
}


# ============================================================
# FOOTER
# ============================================================
if ($hasil_at_db['approv_date'] || $verified_lab) {
  include 'hasil_pemeriksaan-footer.php';
} else {
  echo div_alert('danger tengah', 'BELUM DIVERIFIKASI');
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

    // if ($get_jenis == 'mcu ZZZ Fitur Approv per pasien aborted pada MCU Corporate') {
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
          <button class='btn btn-primary mt4' name=btn_approve value=1>Approve</button>
        </div>
      ";
    } elseif ($get_jenis != 'mcu') {
      if ($role == 'dokter-pj') {
        $blok_approve = "
          <div class=kiri>
            <label style='display:block' class='m2'>
              <input type=checkbox required >
              Saya menyatakan bahwa nilai pemeriksaan lab diatas sudah benar 
            </label>
          </div>
          <div class=wadah>
            <div>Approve by <b>$nama_user</b> at <b>$hari</b> </div>
            <button class='btn btn-primary mt4' name=btn_approve_lab value=1>Approve Hasil Lab</button>
          </div>
        ";
      } else {
        $blok_approve = "
          Mohon tunggu Approve dari Dokter Penanggung Jawab
        ";
      }
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
if ($hasil_at_db['approv_date'] || $verified_lab) {
  $nama_file = "hasil-$get_jenis-$no_mcu-$nama_pasien.pdf";
  $nama_file = str_replace(' ', '_', $nama_file);
  $nama_file = strtolower($nama_file);
  $btn = $verified_lab
    ? "        
        <div class='tengah mt2'>
          <button class='btn btn-primary' onclick=window.print()>Print</button>
        </div>"
    : "
      <div class='flexy flex-center mt3'>
        <div class='admin_only hideit'>Filename:</div>
        <div class='admin_only hideit'>
          <input class='form-control js-copytextarea' value='$nama_file' />
        </div>
        <div class='admin_only hideit'>
          <button class='btn btn-success js-textareacopybtn' id=btn_copy>Copy</button>
        </div>
        <div class='hideit'>
          <button class='btn btn-primary' onclick=window.print()>Print</button>
        </div>
        <div class='hideit'>
          <a target=_blank href='pdf/?id_pasien=$id_pasien' class='btn btn-success'>PDF</a>
        </div>
        <div class=''>
          Kesimpulan Pemeriksaan Fisik telah tersimpan. Anda boleh kembali ke Rekap Pemeriksaan.
          <hr> 
          <span onclick='window.close()' class='btn btn-secondary'>Close Window</span>
        </div>
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