<?php
$info_gaya_hidup = '<div class="abu miring tengah mt2 mb2">Anda belum menjelaskan gaya hidup Anda</div>';
$btn_link = "
  <a class='btn btn-$type_btn_jadwal w-100' href='?isi-kuesioner&id_program=$id_program&kolom=gaya_hidup'>
    Jelaskan Gaya Hidup
  </a>
  <div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>
    Silahkan Anda jelaskan tentang Gaya Hidup Anda
  </div>
";

if ($status >= 5) { // sudah mengisi gaya hidup
  if (!$tanggal_mengisi_gaya_hidup) {
    die(div_alert('danger', "Tanggal mengisi gaya hidup tidak sama dengan status pasien. Mohon segera laporkan ke Petugas!"));
  }

  $info_gaya_hidup = 'INFO GAYA HIDUP';
  $btn_link = '';
}

$type_btn_jadwal = 'primary';
$blok_gaya_hidup = $status < 4 ? '' : "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body'>
      <h3>Gaya Hidup</h3>
      $info_gaya_hidup
      $btn_link
    </div>
  </div>
";
