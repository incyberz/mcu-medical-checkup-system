<?php
only(['admin', 'marketing', 'pendaftar']);
$order_no = $_GET['order_no'] ?? '';
if ($role == 'pendaftar') {
  // paksa ke nomor_order sendiri
  $judul = 'My List Pasien';
  if (!$order_no) {
    $s = "SELECT * FROM tb_order WHERE username_pendaftar='$username' ORDER BY tanggal_order DESC LIMIT 1";
  } else {
    // cari $order_no from GET[order_no] pada tb_order
    $s = "SELECT 1 FROM tb_order WHERE username_pendaftar='$username' AND order_no='$order_no'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) {
      div_alert('danger', "Order No. $order_no tidak ditemukan pada List Order Anda.");
    } else {
      // $order_no ada pada List Order
      $s = "SELECT * FROM tb_order WHERE order_no='$order_no'";
    }
  }
} else {
  $judul = 'List Pasien';
  if (!$order_no) die('index [order_no] belum terdefinisi.');
  $s = "SELECT * FROM tb_order WHERE order_no='$order_no'";
}

$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pesan_err = $role == 'pendaftar' ? 'pada List Order Anda' : '';
if (!mysqli_num_rows($q)) die(div_alert('danger', "Data Order [$order_no] tidak ditemukan $pesan_err | <a href='?'>Home</a>"));
$order = mysqli_fetch_assoc($q);

$mode = $_GET['mode'] ?? '';
if ($mode == 'jadwal') $judul = 'Penjadwalan Pasien';
if ($mode == 'jadwal' and $role == 'pendaftar') die(div_alert('danger', 'Pendaftar tidak berhak melakukan penjadwalan pasien'));

// include 'include/arr_status_order.php';

$order_no = $order['order_no'];
$link_back = "<div class=mt2><a href='?manage_order&order_no=$order_no'>$img_prev</a></div>";
$sub_judul = "Untuk Order No. <b class=darkblue>$order_no | $order[perusahaan]</b> $link_back";
set_h2($judul, $sub_judul);

include 'include/arr_status_pasien.php';













# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_pasien'])) {

  $order_no = $_POST['btn_add_pasien'];

  $s = "INSERT INTO tb_pasien (
    id_klinik,
    order_no,
    nama
  ) VALUES (
    $id_klinik,
    '$order_no',
    '$_POST[new_pasien]'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Add Pasien sukses. Silahkan pilih data pasien tersebut untuk editing selanjutnya.");
  jsurl('', 1000);
} elseif (isset($_POST['btn_delete_pasien'])) {
  $s = "DELETE FROM tb_pasien WHERE id = $_POST[btn_delete_pasien]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Delete Pasien sukses.");
  jsurl('', 1000);
}













# ============================================================
# NORMAL FLOW
# ============================================================
$widths = [
  'nama' => 20,
  'gender' => 10,
  'usia' => 10,
  'no_mcu' => 15,
  'jadwal' => 15,
  'status' => 20,
];
if ($mode == 'jadwal') $widths = [
  'nama' => 20,
  'no_mcu' => 15,
  'status' => 20,
  'jadwal' => 35,
];
$max_width = array_sum($widths);

$arr_field = ['gender', 'usia', 'jadwal', 'status'];
if ($mode == 'jadwal') $arr_field = ['status', 'jadwal'];

$str_fields = '';
foreach ($arr_field as $field) {
  $str_fields .= "a.$field, ";
}

$s = "SELECT 
a.nama,
concat('MCU-',a.id) as no_mcu,  
$str_fields
a.id as id_pasien,
(SELECT awal_periksa FROM tb_hasil_pemeriksaan WHERE id_pasien=a.id) awal_periksa

FROM tb_pasien a WHERE order_no='$order_no'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pasien = mysqli_num_rows($q);
$jumlah_pasien_show = number_format($jumlah_pasien, 0);
$jumlah_sudah_periksa = 0;
$jumlah_belum_terjadwal = 0;

$tr = '';
$no_width = (20 + 5 * (strlen($jumlah_pasien) - 1)) . 'px';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_pasien = $d['id_pasien'];

    $tr_class = 'tr_all';
    if ($d['awal_periksa']) {
      $jumlah_sudah_periksa++;
      $tr_class .= ' tr_sudah_periksa';
    } else {
      $tr_class .= ' tr_belum_periksa';
      if ($d['jadwal']) {
        $tr_class .= ' tr_sudah_terjadwal';
      } else {
        $jumlah_belum_terjadwal++;
        $tr_class .= ' tr_belum_terjadwal';
      }
    }


    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'id_klinik'
        || $key == 'id_pasien'
        || $key == 'order_no'
        || $key == 'date_created'
        || $key == 'awal_periksa'
      ) continue;

      $w = $widths[$key] ?? '';
      $width = $w ? round($w * 100 / $max_width, 1) : '';

      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th width='$width%'>$kolom</th>";
      }

      if ($key == 'nama') {
        $value = "
          <div style='display: -webkit-box; -webkit-line-clamp:1; -webkit-box-orient: vertical; overflow: hidden'>
            $value
          </div>
        ";
      } elseif ($key == 'jadwal') {
        if ($d['awal_periksa'] and !$value) {
          $value  = "$d[awal_periksa] (auto)";
        }
      } elseif ($key == 'status') {

        $form_delete = "
          <form method=post class='m0' style='display: inline'>
            <button class='btn-transparan' name=btn_delete_pasien value=$id_pasien onclick='return confirm(\"Hapus pasien ini?\")'>$img_delete</button>
          </form>
        ";
        $value = $value ? "$value - <span class=f10>$arr_status_pasien[$value] $form_delete ZZZ</span>" : $null;
      }

      $value_show = $value ? $value : $null;
      $td .= "<td width='$width%'>$value_show</td>";
    }
    $tr .= "
      <tr class='$tr_class'>
        <td><div style='width:$no_width'><i class='f14 abu'>$i</i></div></td>
        $td
      </tr>
    ";
  }
}

$jumlah_belum_periksa = $jumlah_pasien - $jumlah_sudah_periksa;
$jumlah_sudah_terjadwal = $jumlah_belum_periksa - $jumlah_belum_terjadwal;

$jumlah_sudah_periksa_show = number_format($jumlah_sudah_periksa);
$jumlah_sudah_terjadwal_show = number_format($jumlah_sudah_terjadwal);
$jumlah_belum_terjadwal_show = number_format($jumlah_belum_terjadwal);
$jumlah_pasien_show = number_format($jumlah_pasien);


if (!$mode) {
  $tb = $tr ? "
    <style>
      .tr_nav:hover{background:linear-gradient(#fef,#fcf)}
      .tr_nav_active{border:solid 2px blue}
    </style>
    <div class='flexy flex-center'>
      <div class='flexy tengah'>
        <div class='wadah p2 gradasi-hijau tr_nav pointer' id=tr_nav__sudah_periksa>
          <div class='f24 green'>$jumlah_sudah_periksa_show</div>
          <div class='abu f10'>Sudah Pemeriksaan</div>
        </div>
        <div class='f30'>+</div>
        <div class='wadah p2 gradasi-toska tr_nav pointer' id=tr_nav__sudah_terjadwal>
          <div class='f24 biru'>$jumlah_sudah_terjadwal_show</div>
          <div class='abu f10'>Sudah Terjadwal</div>
        </div>
        <div class='f30'>+</div>
        <div class='wadah p2 gradasi-kuning tr_nav pointer' id=tr_nav__belum_terjadwal>
          <div class='f24 darkred'>$jumlah_belum_terjadwal_show</div>
          <div class='abu f10'>Belum Terjadwal</div>
        </div>
        <div class='f30'>=</div>
        <div class='wadah p2  tr_nav pointer' id=tr_nav__all>
          <div class='f24 '>$jumlah_pasien_show</div>
          <div class='abu f10'>Jumlah Pasien</div>
        </div>
      </div>
    </div>
  
    <table class='table th_toska'>
      <thead><th><div style='width:$no_width'>No</div></th>$th</thead>
    </table>
    <div class='border-bottom' style='max-height:300px; overflow-y:scroll; background: linear-gradient(white,#ccf) !important; position:relative'>
      <table class='table td_trans '>
        $tr
      </table>
    </div>
    <div class='biru mt1 mb4'>Tekan Ctrl + F untuk pencarian data, Ctrl + Home/End menuju paling atas/bawah (klik dahulu pada tabel pasien)</div>
  " : div_alert('danger', "Data pasien tidak ditemukan.");
  echo "$tb";

  if ($role != 'pendaftar') {
    include 'list_pasien-import_pasien.php';
    echo "
      <a class='btn btn-success btn-sm' href='?list_pasien&order_no=$order_no&mode=jadwal'>Penjadwalan</a>
      <span class='btn btn-success btn-sm btn_aksi' id=form_import_pasien__toggle>Import Pasien</span>
      <div class='hideit mt2' id=form_import_pasien>$import_pasien</div>
    ";
  }
} elseif ($mode == 'jadwal') {
  include 'list_pasien-rule_penjadwalan.php';
} else {
  echo div_alert('danger', "Belum ada FORM untuk mode aksi: $mode");
}



?>
<script>
  $(function() {
    $('.tr_nav').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);

      $('.tr_nav').removeClass('tr_nav_active');
      $(this).addClass('tr_nav_active');

      $('.tr_all').hide();
      $('.tr_' + id).show();

    })
  })
</script>