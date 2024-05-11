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
  if (!$tanggal_mengisi_riwayat_penyakit) {
    die(div_alert('danger', 'Status pasien tidak sesuai dengan data riwayat pada database. Mohon segera laporkan ke Petugas!'));
  }


  $arr = explode(',', $riwayat_penyakit);
  $li = '';
  $array_riwayat = [];
  foreach ($arr as $key => $r) {
    if ($r) $li .= "<li>$r</li>";
  }

  $riwayat_penyakit_show = "<ul class='pl3 f14'>$li</ul>";
  $tanggal_mengisi_riwayat_penyakit_show = date('d-M-Y H:i', strtotime($tanggal_mengisi_riwayat_penyakit));
  $tanggal_mengisi_riwayat_penyakit_show .= '<div class="f12 abu miring">' . eta2($tanggal_mengisi_riwayat_penyakit) . '</div>';



  // buat table untu info kuesioner
  $info_kuesioner = "
    <table class=table>
      <tr>
        <td class='kolom'>Riwayat Penyakit</td>
        <td>
          $riwayat_penyakit_show
          <div>
            <a href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_user'>
              Kuesioner Ulang $img_edit
            </a>
          </div>
        </td>
      </tr>
      <tr>
        <td class='kolom'>Tanggal Mengisi</td>
        <td>$tanggal_mengisi_riwayat_penyakit_show</td>
      </tr>
    </table>
  ";

  // belum mengisi gejala penyakit
  if ($status >= 4) { // 4 - Sudah Update Gejala Penyakit

    $arr = explode(',', $gejala_penyakit);
    $li = '';
    $array_gejala = [];
    foreach ($arr as $key => $r) {
      if ($r) $li .= "<li>$r</li>";
    }

    $gejala_penyakit_show = "<ul class='pl3 f14'>$li</ul>";
    $tanggal_mengisi_gejala_penyakit_show = date('d-M-Y H:i', strtotime($tanggal_mengisi_gejala_penyakit));
    $tanggal_mengisi_gejala_penyakit_show .= '<div class="f12 abu miring">' . eta2($tanggal_mengisi_gejala_penyakit) . '</div>';


    $info_kuesioner .= "
      <table class=table>
        <tr>
          <td class='kolom'>Gejala Penyakit</td>
          <td>
            $gejala_penyakit_show
            <div>
              <a href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_user&kolom=gejala'>
                Kuesioner Ulang $img_edit
              </a>
            </div>            
          </td>
        </tr>
        <tr>
          <td class='kolom'>Tanggal Mengisi</td>
          <td>$tanggal_mengisi_gejala_penyakit_show</td>
        </tr>
      </table>
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
