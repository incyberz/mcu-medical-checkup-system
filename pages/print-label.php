<?php
$print = $_GET['print'] ?? '';
$nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
$id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
if ($print) {
  $id_klinik = 1; ///zzz debug
  include '../conn.php';
  $lokasi_img = "../assets/img";
}

$judul = 'Print Label';
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', 'Index id_pasien belum terdefinisi.'));

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

// echo $s;
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (!mysqli_num_rows($q)) {
  if ($id_pasien == 'random') {
    $pesan = "
      Belum ada pasien untuk paket <u>$nama_paket</u>
      <hr>
      <a href='?print-label&id_pasien=random-klinik&id_paket=$id_paket&nama_paket=$nama_paket'>
        Test Print Label untuk Pasien Klinik (Random)
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
  $nomor_mcu = $d['nomor'];
  $perusahaan = $d['perusahaan'];
}


if (!$print) {
  $sub_judul = "
    $mode
    <a href='?manage-sticker&id_paket=$id_paket&nama_paket=$nama_paket'>Back</a> 
    | 
    Print Label untuk 
    <b class='biru'>
      $nama_pasien | 
      MCU-$nomor_mcu | 
      NIK. $nik_pasien | 
      $perusahaan
    </b>
    <hr>
    <a target=_blank href='pages/print-label.php?id_pasien=$id_pasien&id_paket=$id_paket&nama_paket=$nama_paket&print=1' class='btn btn-primary'><i class='bx bx-printer'></i> Cetak ke Printer</a>
    <div class='f12 abu miring mt2'>
      Lihat pada preview dibawah ini, jika <u>tidak ada kesalahan</u>, silahkan Anda dapat langsung Cetak ke Printer, kemudian seting Printer dengan tidak menyertakan header/footer web dan semua margin harus nol.
    </div>
    <hr>
  ";
  set_title($judul);
  set_h2($judul, $sub_judul);
  only(['admin', 'marketing']);
}
// $img_sticker = "<img src='$lokasi_icon/sticker.png' height=25px class='zoom pointer' />";












# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
  echo div_alert('success', "Delete Paket sukses.");
  jsurl('', 3000);
}













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
echo "
  <div class='flexy' style='justify-content:center'>
    <div>
      <table id=tb_label>
        ";

$s = "SELECT kode FROM tb_paket_sticker WHERE kode  like '$id_paket-%'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', "Paket ini belum mempunyai sticker. | Silahkan <a href='?manage_sticker&id_paket=$id_paket'>Manage Sticker</a>!"));
} else {
  while ($d = mysqli_fetch_assoc($q)) {
    $arr = explode('-', $d['kode']);
    $id_paket = $arr[0];
    $id_sticker = $arr[1];

    $nama_sticker = strtoupper($stickers[$id_sticker]);

    echo  "
      <tr>
        <td>
          <img src='$lokasi_img/qr.png' class='img-qr'/>
        </td>
        <td>
          <div class='nama_sticker'>$nama_sticker</div>
          <div class='nomor_mcu'>MCU-$nomor_mcu</div>
          <div class='nama_pasien'>$nama_pasien</div>
          <div class='nik_pasien'>NIK. $nik_pasien</div>
        </td>
      </tr>
    ";
  }
}



echo "
      </table>
    </div>
  </div>
";
if ($print) echo "<script>window.print()</script>";
