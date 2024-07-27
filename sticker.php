<style>
  * {
    margin: 0;
  }
</style>
<?php
// require_once 'include/qrcode.php';
// $qr = QRCode::getMinimumQRCode($nomor_mcu, QR_ERROR_CORRECT_LEVEL_L);
// $qr->printHTML('3px');
// exit;

include 'pages/cetak_sticker-styles.php';
$print = $_GET['print'] ?? '';
if ($print)  $arr = explode('|||', $_POST['data']);



# ============================================================
# LOOP JUMLAH STICKER PAKET INI
# ============================================================

$i = 0;
foreach ($arr as $key => $value) {
  if (!$value) continue;

  $arr2 = explode(',', $value);
  $nama_sticker = $arr2[0]  ?? die('Array index 0 belum terdefinisi');
  $nomor_mcu = $arr2[1] ?? die('Array index 1 belum terdefinisi');
  $nama_pasien = $arr2[2] ?? die('Array index 2 belum terdefinisi');
  $info_perusahaan = $arr2[3] ?? die('Array index 3 belum terdefinisi');

  $i++;
  $red = $i % 2 == 0 ? 'red' : 'yellow';
  $red = '';
  echo  "
  <div style='display:flex; height: 2.54cm; align-items:center; padding-left: 1mm'>
    <div style='display:grid; grid-template-columns: 2cm 3cm; gap: 1mm'>
      <div style='background:$red'>";

  require_once 'include/qrcode.php';
  $qr = QRCode::getMinimumQRCode($nomor_mcu, QR_ERROR_CORRECT_LEVEL_L);
  $qr->printHTML('3px');

  echo "
    </div>
      <div>
        <div class='nama_sticker' style='margin:0'>$nama_sticker</div>
        <div class='nomor_mcu'>$nomor_mcu</div>
        <div class='nama_pasien'>$nama_pasien</div>
        <div class='nik_pasien'>$info_perusahaan</div>
      </div>
    </div>
  </div>
  ";
}


if ($print) echo "<script>window.print()</script>";
