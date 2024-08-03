<?php
$kolom = $_GET['kolom'] ?? 'riwayat';

$info_kuesioner = '<div class="abu miring tengah mt2 mb2">Anda belum mengisi kuesioner</div>';
$button_link = "
  <a class='btn btn-$type_btn_jadwal w-100' href='?isi-kuesioner&id_program=$id_program'>
    Isi Kuesioner Riwayat Penyakit
  </a>
  <div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>
    Silahkan isi dahulu Kuesioner Riwayat Penyakit agar Anda mendapatkan Sticker-Medis untuk prasyarat pemeriksaan
  </div>
";

if ($status >= 3) {
  if (!$is_login_as) {
    if (!$tanggal_mengisi_riwayat_penyakit) {
      die(div_alert('danger', 'Status pasien tidak sesuai dengan data riwayat pada database. Mohon segera laporkan ke Petugas!'));
    }
  }


  $arr = explode(',', $riwayat_penyakit);
  $li = '';
  foreach ($arr as $key => $r) {
    if ($r) $li .= "<li>$r</li>";
  }

  $riwayat_penyakit_show = "<ul class='pl3 f14s '>$li</ul>";
  $tanggal_mengisi_riwayat_penyakit_show = date('d-M-Y H:i', strtotime($tanggal_mengisi_riwayat_penyakit));
  $tanggal_mengisi_riwayat_penyakit_show .= '<span class="f12 abu miring"> ~ ' . eta2($tanggal_mengisi_riwayat_penyakit) . '</span>';



  // buat table untu info kuesioner
  $info_kuesioner = "
    <h3 class='kolom tengah'>Riwayat Penyakit</h3>
    $riwayat_penyakit_show
    <div class='mb2 tengah mt1'>
      <a href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_user'>
        Kuesioner Ulang $img_edit
      </a>
    </div>
    <div class='f12 tengah mb4'>$tanggal_mengisi_riwayat_penyakit_show</div>
    <hr>
  ";

  // belum mengisi gejala penyakit
  if ($status >= 4) { // 4 - Sudah Update Gejala Penyakit

    $arr = explode(',', $gejala_penyakit);
    $li = '';
    foreach ($arr as $key => $r) {
      if ($r) $li .= "<li>$r</li>";
    }

    $gejala_penyakit_show = "<ul class='pl3 '>$li</ul>";
    $tanggal_mengisi_gejala_penyakit_show = date('d-M-Y H:i', strtotime($tanggal_mengisi_gejala_penyakit));
    $tanggal_mengisi_gejala_penyakit_show .= '<span class="f12 abu miring"> ~ ' . eta2($tanggal_mengisi_gejala_penyakit) . '</span>';


    $info_kuesioner .= "
      <h3 class='kolom tengah'>Gejala Penyakit</h3>
      $gejala_penyakit_show
      <div class='tengah mb2'>
        <a href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_user&kolom=gejala'>
          Kuesioner Ulang $img_edit
        </a>
      </div>            
      <div class='tengah f12'>$tanggal_mengisi_gejala_penyakit_show</div>
    ";

    $button_link = "
      <a class='btn btn-$type_btn_jadwal w-100' href='?isi-kuesioner&id_program=$id_program'>
        Isi Kuesioner Riwayat Penyakit
      </a>
      <div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>
        Silahkan isi dahulu Kuesioner Riwayat Penyakit agar Anda mendapatkan Sticker-Medis untuk prasyarat pemeriksaan
      </div>
    ";
    $button_link = '';
  } else { // masih status 3 Baru Ngisi Kusionaer Riwayat Penyakit
    // link ke gejala penyakit 
    $info_kuesioner .= '<div class="abu miring tengah mt2 mb2">Anda belum mengisi kuesioner untuk gejala penyakit Anda
    </div>';

    $button_link = "
      <a class='btn btn-$type_btn_jadwal w-100' href='?isi-kuesioner&id_program=$id_program&kolom=gejala'>
        Isi Gejala Penyakit
      </a>
      <div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>
        Silahkan Anda lanjutkan ke pengisian Kuesioner Gejala Penyakit agar Dokter dapat dengan mudah menganalisis kesehatan Anda
      </div>
    ";
  }
} // end status >= 3 Update Riwayat Penyakit


$type_btn_jadwal = 'primary';
$blok_kuesioner = $status < 2 ? '' : "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body'>
      <h3>Kuesioner Online</h3>
      $info_kuesioner
      $button_link
    </div>
  </div>
";
