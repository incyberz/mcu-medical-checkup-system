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
  if (!$is_login_as) {
    if (!$tanggal_mengisi_gaya_hidup) {
      // die(div_alert('danger', "Tanggal mengisi gaya hidup tidak sama dengan status pasien. Mohon segera laporkan ke Petugas!<hr>status: $status"));
      // status 9 dan boleh acak
    }
    if (!$gaya_hidup) {
      // die(div_alert('danger', "Data gaya hidup pasien tidak terbaca. Mohon segera laporkan ke Petugas!"));
      // zzz debug skipped
    }
  }

  $arr = explode(',', $gaya_hidup);
  $li = '';
  foreach ($arr as $key => $r) {
    if ($r) $li .= "<li>$r</li>";
  }

  $btn_link = '';
  $info_gaya_hidup = "<ul class='pl3 '>$li</ul>";
  if ($tanggal_mengisi_gaya_hidup) {
    $tanggal_mengisi_gaya_hidup_show = date('d-M-Y H:i', strtotime($tanggal_mengisi_gaya_hidup));
    $tanggal_mengisi_gaya_hidup_show .= '<span class="f12 abu miring"> ~ ' . eta2($tanggal_mengisi_gaya_hidup) . '</span>';
    $Isi_Kuesioner = 'Jelaskan Ulang';
  } else {
    $tanggal_mengisi_gaya_hidup_show = '<span class="f14 red bold">Belum mengisi Gaya Hidup</span>';
    $Isi_Kuesioner = 'Isi Gaya Hidup';
  }
  $info_gaya_hidup .= "
    <div class='mb2 tengah mt1'>
      <a href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_user&kolom=gaya_hidup'>
        $Isi_Kuesioner $img_edit
      </a>
    </div>
  ";

  $info_gaya_hidup .= "<div class='tengah f12'>$tanggal_mengisi_gaya_hidup_show</span></div>";
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
