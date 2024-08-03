<?php
$info_keluhan = '<div class="abu miring tengah mt2 mb2">Anda belum menjelaskan keluhan Anda</div>';
$btn_link = "
  <a class='btn btn-$type_btn_jadwal w-100' href='?isi-kuesioner&id_program=$id_program&kolom=keluhan'>
    Jelaskan Keluhan
  </a>
  <div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>
    Silahkan Anda jelaskan tentang Keluhan Anda
  </div>
";

if ($status >= 6) { // sudah mengisi keluhan
  if (!$is_login_as) {
    if (!$tanggal_mengisi_keluhan) {
      die(div_alert('danger', "Tanggal mengisi keluhan tidak sama dengan status pasien. Mohon segera laporkan ke Petugas!"));
    }
    if (!$keluhan) {
      die(div_alert('danger', "Data keluhan pasien tidak terbaca. Mohon segera laporkan ke Petugas!"));
    }
  }

  $arr = explode(',', $keluhan);
  $li = '';
  foreach ($arr as $key => $r) {
    if ($r) $li .= "<li>$r</li>";
  }

  $btn_link = '';
  $info_keluhan = "<ul class='pl3 '>$li</ul>";
  $info_keluhan .= "
    <div class='mb2 tengah mt1'>
      <a href='?isi-kuesioner&id_program=$id_program&id_pasien=$id_user&kolom=keluhan'>
        Jelaskan Ulang $img_edit
      </a>
    </div>
  ";

  $info_keluhan .= '<div class="tengah f12">'
    . date('d-M-Y H:i', strtotime($tanggal_mengisi_keluhan))
    . '<span class="f12 abu miring"> ~ '
    . eta2($tanggal_mengisi_keluhan)
    . '</span></div>';
}

$keluhan_show = '';
$hide_form_keluhan = '';
$btn_caption = 'Submit Keluhan';
$primary = 'primary';
if ($keluhan) {
  $primary = 'secondary';
  $btn_caption = 'Update Keluhan';
  $hide_form_keluhan = 'hideit';
  $keluhan_show = "
  <div class='darkblue tebal wadah miring gradasi-toska mt4 mb4'>

    $keluhan
  </div>
  <div class='mb4'>
    <span class='btn_aksi darkblue f14' id=form_keluhan__toggle>Ubah Keluhan $img_edit</span>
  </div>
  ";
}

$type_btn_jadwal = 'primary';
$blok_keluhan = $status < 4 ? '' : "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body tengah'>
      <h3>Keluhan Sekarang</h3>
      $keluhan_show

      <form id=form_keluhan class='$hide_form_keluhan' method='POST' action='?isi-kuesioner&id_program=1&id_pasien=2&kolom=keluhan'>
        <img src='assets/img/ilustrasi/bertanya.jpg' class='img-thumbnail img-fluid' />
        <input type='hidden' name='kolom' value='keluhan'>
        <div class='mt2 mb2'>Silahkan Anda jelaskan keluhan Anda saat ini:</div>
        <textarea required name='jawaban' id=jawaban class='hideita form-control' rows=5 minlength=1 maxlength=2000 />$keluhan</textarea>
        <div class='mt1 mb2 f12 abu miring'>Jika tidak ada keluhan apapun, silahkan isi dengan tanda strip.</div>

        <button class=' btn btn-$primary w-100 mt2' name=btn_submit_jawaban id=btn_submit_jawaban >$btn_caption</button>

      </form>

    </div>
  </div>
";
