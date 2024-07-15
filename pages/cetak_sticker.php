<?php
$thn = date('y');
$print = $_GET['print'] ?? '';
if ($print) {
  $id_klinik = 1; ///zzz debug
  include '../conn.php';
  $lokasi_img = "../assets/img";
}
include 'include/arr_sampel_detail.php';

$nama_paket = $_POST['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
$id_paket = $_POST['id_paket'] ?? null;
$id_paket_custom = $_POST['id_paket_custom'] ?? '';
if (!$id_paket and !$id_paket_custom) die(div_alert('danger', 'Index [id_paket | id_paket_custom] belum terdefinisi.'));
$is_custom = $id_paket_custom ? 1 : 0;
// $id_paket = $id_paket ?? $id_paket_custom;

$judul = 'Cetak Sticker';
$id_pasien = $_POST['id_pasien'] ?? die(div_alert('danger', 'Index id_pasien belum terdefinisi.'));

$where_id = "WHERE id='$id_pasien'";
$mode = '';
if ($id_pasien == 'random' || $id_pasien == 'random-klinik') {

  if ($id_pasien == 'random') {
    $mode = "Random Pasien pada Paket $nama_paket.";
    $where_id = "
      WHERE a.id_klinik=$id_klinik 
      AND b.id_paket=$id_paket
      ORDER BY RAND() 
      LIMIT 1
    ";
  } elseif ($id_pasien == 'random-klinik') {
    $mode = 'Random Pasien pada Klinik ini.';
    $where_id = "
      WHERE a.id_klinik=$id_klinik 
      ORDER BY RAND() 
      LIMIT 1
    ";
  }
}
$mode = $mode ? "<div class='red bold mb2'>Print mode: $mode (testing only)</div>" : '';


$s = "SELECT a.*, 
a.id as id_pasien,
b.perusahaan 
FROM tb_pasien a 
JOIN tb_order b ON a.order_no=b.order_no 
$where_id
";

if ($is_custom) {
  $s = "SELECT a.*, 
  a.id as id_pasien 
  FROM tb_pasien a 
  $where_id
  ";
}

// echo $s;
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (!mysqli_num_rows($q)) {
  if ($id_pasien == 'random') {
    $pesan = "
      Belum ada pasien untuk paket <u>$nama_paket</u>
      <hr>
      <a href='?print-label&id_pasien=random-klinik&id_paket=$id_paket&nama_paket=$nama_paket'>
        Test Cetak Sticker untuk Pasien Klinik (Random)
      </a>
    ";
  } elseif ($id_pasien == 'random-klinik') {
    $pesan = "Belum ada pasien di klinik ini. ";
  } else {
    $pesan = "Data pasien tidak ditemukan. ";
  }
  die(div_alert('danger', $pesan));
} else {
  $d = mysqli_fetch_assoc($q);
  $id_pasien = $d['id_pasien']; // replace id_pasien jika random pasien
  $nama_pasien = $d['nama'];
  $nik_pasien = $d['nikepeg'];
  $nomor_mcu = "MCU$thn-$id_pasien";
  $perusahaan = !$is_custom ? $d['perusahaan'] : '';
  $status = $d['status'];
  $tanggal_lahir = $d['tanggal_lahir'];
  if ($status == 7 and $print) {
    $s2 = "UPDATE tb_pasien SET status=8 WHERE id=$id_pasien";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  }
}


if (!$print) {
  $caption_upd_status = $status == 7 ? ' dan Update Status Pasien' : '';
  // $status_pasien_info = $status != 7 ? '' : "
  //     Status pasien sudah Siap Pemeriksaan (7) maka otomatis akan diubah status ke Sudah Cetak Label (8)
  // "; ZZZ

  $tanggal_lahir = $d['tanggal_lahir'] ? hari_tanggal($d['tanggal_lahir'], 1, 0, 0) : '-';
  $info_perusahaan = $is_custom ? $tanggal_lahir : "NIK. $nik_pasien | $perusahaan";

  $sub_judul = "
    $mode
    Cetak Sticker untuk 
    <b class='darkblue'>
      $nama_pasien | 
      $nomor_mcu | 
      $info_perusahaan
    </b>
  ";
  set_title($judul);
  set_h2($judul, $sub_judul);
  only(['admin', 'marketing', 'cs']);
}
// $img_sticker = "<img src='$lokasi_icon/sticker.png' height=25px class='zoom pointer' />";

























$stickers = [];

$s = "SELECT id,nama FROM tb_sticker WHERE id_klinik= '$id_klinik'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $id = $d['id'];
  $nama = $d['nama'];
  $stickers[$id] = $nama;
}



?>
<style>
  #tb_label {
    max-width: 5cm;
  }

  #tb_label tr {
    max-width: 5cm;
  }

  #tb_label td {
    /* border: solid 1px red !important; */
    vertical-align: top;

  }

  .img-qr {
    margin-top: 0.2cm;
    height: 1.8cm;
  }

  .nama_sticker {
    margin-top: 0.35cm;
    font-family: consolas;
    font-size: 11px;
    overflow: hidden;
    white-space: nowrap;
    height: 16px;
  }

  .nomor_mcu {
    font-size: 12px;
    overflow: hidden;
    white-space: nowrap;
    height: 18px;
  }

  .nama_pasien {
    font-size: 9px;
    overflow: hidden;
    white-space: nowrap;
    height: 13px;
  }

  .nik_pasien {
    font-size: 11px;
    overflow: hidden;
    white-space: nowrap;
    height: 16px;
  }

  .nama_sticker,
  .nama_pasien,
  .nomor_mcu,
  .nik_pasien {
    /* border: solid 1px red; */
    border: none
  }
</style>
<?php


if ($is_custom) {
  $s = "SELECT 
  b.nama as nama_pemeriksaan,
  b.sampel   
  FROM tb_paket_custom_detail a 
  JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
  WHERE a.id_paket_custom=$id_paket_custom
  ";
} else {
  $s = "SELECT kode FROM tb_paket_sticker WHERE kode ZZZ like '$id_paket-%'";
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', "Paket ini belum mempunyai sticker ZZZ. | Silahkan <a href='?manage_paket'>Manage Paket</a>"));
} else {
  $arr_sampel_paket = [];
  $info_nik = $is_custom ? date('d-M-Y', strtotime($tanggal_lahir)) : "NIK. $nik_pasien";
  $list_pemeriksaan = '';

  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    if ($d['sampel']) {
      if (!array_key_exists($d['sampel'], $arr_sampel_paket)) {
        $arr_sampel_paket[$d['sampel']] = $arr_sampel[$d['sampel']];
      }
      $zat = $arr_sampel[$d['sampel']]['zat'];
      $volume = $arr_sampel[$d['sampel']]['volume'];
      $satuan = $arr_sampel[$d['sampel']]['satuan'];
      $list_pemeriksaan .=  "
        <tr>
          <td>$i</td>
          <td>$d[nama_pemeriksaan]</td>
          <td>$d[sampel]</td>
          <td>$zat</td>
          <td>$volume $satuan</td>
        </tr>
      ";
    } else {
      $list_pemeriksaan .=  "
        <tr>
          <td>$i</td>
          <td>$d[nama_pemeriksaan]</td>
          <td class='abu f12 miring'>tanpa sampel</td>
          <td class='abu f12 miring'>-</td>
          <td class='abu f12 miring'>-</td>
        </tr>
      ";
    }
  }

  echo "
    <div class='wadah gradasi-hijau'>
      <table class='table'>
        <thead>
          <th>No</th>
          <th>Pemeriksaan</th>
          <th>Sampel</th>
          <th>Zat</th>
          <th>Volume</th>
        </thead>
        $list_pemeriksaan
      </table>
    </div>
  ";
}



# ============================================================
# UPDATE status pasien 
# ============================================================
$s = "UPDATE tb_pasien SET status=8 WHERE id=$id_pasien";
echo div_alert('info tengah', 'Updating status pasien sukses. | Status pasien: Sudah Cetak Sticker (8)');
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));



# ============================================================
# LOOP JUMLAH STICKER PAKET INI
# ============================================================

$tr_preview = '';

$data = '';
foreach ($arr_sampel_paket as $key => $value) {

  $nama_sticker = "$key - " . $value['zat'];
  $data .= "$nama_sticker,$nomor_mcu,$nama_pasien,$info_perusahaan|||";

  $tr_preview .=  "
    <tr>
      <td>
        <img src='$lokasi_img/qr.png' class='img-qr'/>
      </td>
      <td>
        <div class='nama_sticker'>$nama_sticker</div>
        <div class='nomor_mcu'>$nomor_mcu</div>
        <div class='nama_pasien'>$nama_pasien</div>
        <div class='nik_pasien'>$info_perusahaan</div>
      </td>
    </tr>
  ";
}


echo "
  <h4 class='tengah mt4'>Preview Bentuk Sticker</h4>
  <div class='flexy flex-center '>
    <div class=''>
      <table id=tb_label>
        $tr_preview
      </table>
    </div>
  </div>

  <form method=post action='sticker.php?print=1' target=_blank class=tengah>
    <input type=hidden name=data value='$data'>
    <button class='btn btn-primary mt2'>
      <i class='bx bx-printer'></i> 
      Print Preview Sticker
    </button>
    <div class='f12 abu miring mt2'>
      Setinglah Printer dengan tidak menyertakan header/footer dan semua margin harus nol.
    </div>
    <div class='mt2 hideit'>
      $ status_pasien_info ZZZ
    </div>
  </form>


";
