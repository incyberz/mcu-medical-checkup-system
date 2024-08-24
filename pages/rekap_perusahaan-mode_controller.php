<?php
if ($mode == 'monitoring_pasien') {
  include 'monitoring_pasien.php';
  exit;
}

$arr_mode = [
  'detail' => ['Preview untuk Perusahaan', ''],
  'approv' => ['Approv MCU Corporate', ''],
  'monitoring_pasien' => ['Monitoring Pasien', '_blank'],
  'kirim_link' => ['Kirim Link ke HRD', '_blank'],
  'invoice' => ['Cetak Invoice', ''],
];

$nav_mode = '';
foreach ($arr_mode as $k => $v) {
  $slash = $nav_mode ? ' | ' : '';
  if ($k == $mode) {
    $nav_mode .= "$slash<span class='abu'>$v[0]</span>";
  } else {
    $nav_mode .= "$slash<a target='$v[1]' href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=$k&tanggal_periksa=$get_tanggal_periksa'>$v[0]</a>";
  }
}

// $link_approv = "<a href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=approv&tanggal_periksa=$get_tanggal_periksa'>Mode Approv Kesimpulan</a>";
// $link_preview = "<a href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&tanggal_periksa=$get_tanggal_periksa'>Preview untuk Perusahaan</a>";
// $link_hrd = "<div class='mt2'>
//   <a class='btn btn-sm btn-success' href='?rekap_perusahaan&id_perusahaan=1&mode=kirim_link'>Kirim Link Pasien ke HRD</a>
// </div>
// <a class='' href='https://youtu.be/AAQdRTHI4PE' target=_blank >Lihat Tutorial Cara Verifikasi</a>";
// if ($mode == 'approv') {
//   $judul = 'Approv Corporate';
//   $link = $link_preview;
//   $sub_h = 'Mode Approv Kesimpulan';
// } elseif ($mode == 'monitoring_pasien') {
//   $link = $link_approv;
//   $sub_h = 'Monitoring Pasien';
//   $link_hrd = '';
// } else {
//   $link = $link_approv;
//   $sub_h = 'Preview untuk Perusahaan';
// }
// $sub_judul = "
//   $sub_h | $link 
//   $link_hrd
// ";


$sub_judul = "$nav_mode<br><a class='' href='https://youtu.be/AAQdRTHI4PE' target=_blank >Lihat Tutorial Cara Verifikasi</a>";
