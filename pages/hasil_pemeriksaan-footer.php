<?php
# ============================================================
# FOOTER
# ============================================================
$tanggal_cetak = hari_tanggal($now, 1, 0, 1, 1);
$dokter_pj = $arr_user[$hasil_at_db['approv_by']];

$http = $online_version ? 'https' : 'http';
$rlink = explode('?', $http . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

$qr_konten = $rlink[0];
$qr_konten = str_replace('index.php', '', $qr_konten);

$id1 = "000000000$id_pasien";
$id1 = substr($id1, strlen($id1) - 6, 100);

// jika mcu maka pemeriksaan pemfis dokter showAtQR
if ($get_jenis == 'mcu') $id_pemeriksaan = 8;
$id2 = "000000000$id_pemeriksaan";
$id2 =  substr($id2, strlen($id2) - 3, 100);

$mc = date('is');
$id2 = $mc . $id2;

$id2 =  substr($id2, strlen($id2) - 6, 100);

$id3 = '';
for ($i = 0; $i < 6; $i++) {
  $id3 .= substr($id2, $i, 1);
  $id3 .= substr($id1, $i, 1);
}

$qr_konten .= "qr/?$id3";

$Dokter_pemeriksa = $id_pemeriksaan == 9 ? 'Dokter Radiologi' : 'Dokter Pemeriksa';
$dokter_pj = $id_pemeriksaan == 9 ? 'dr. Yuliawati H, Sp.Rad' : $dokter_pj;

echo "
  <div class='mt2 kiri f11' style='margin-left:11cm; margin-bottom: 1cm'>
    <div>
      <span class='abu miring'>Printed at:</span> 
      Bekasi, $tanggal_cetak
    </div>
    <div class=hideit>
      <span class='abu miring'>From:</span> 
      Mutiara Medical System, https://mmc-clinic.com
    </div>
    <div class=mb1>
      <span class='abu miring'>$Dokter_pemeriksa:</span> 
      $dokter_pj
    </div>
";


require_once 'include/qrcode.php';
$qr = QRCode::getMinimumQRCode($qr_konten, QR_ERROR_CORRECT_LEVEL_L);
$qr->printHTML('4px');

echo "</div>";
