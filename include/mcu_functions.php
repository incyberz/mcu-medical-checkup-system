<?php


function radio($name, $id, $value, $caption, $checked = '', $classess = '')

{
  $id = $id ? $id : $name;
  return "
    <div class='radio-toolbar abu mb2 mt2'>
      <input type='radio' name='$name' id='$id' class='opsi_radio $classess' required value='$value' $checked >
      <label class='proper' for='$id'>$caption</label>
    </div>
  ";
}

function radio_tf($kalimat, $name, $nilai_default = '', $caption = '', $hr = 1, $Ya = 'Ya', $Tidak = 'Tidak')

{
  $hr = $hr ? '<hr>' : '';
  $mb2 = $hr ? '' : 'mb2';
  $id0 = $name . '0';
  $id1 = $name . '1';

  $checked_true = '';
  $checked_false = '';
  if ($nilai_default != '') {
    if ($nilai_default == 1) {
      $checked_true = 'checked';
    } else {
      $checked_false = 'checked';
    }
  }

  return "
    <label class='darkblue mb2'>$kalimat</label>
    <div class='radio-toolbar abu $mb2'>
      <div class='row'>
        <div class='col-6'>
          <input type='radio' name='$name' id='$id0' class='opsi_radio' required value='0' $checked_false >
          <label class='proper' for='$id0'>$Tidak $caption</label>
        </div>
        <div class='col-6'>
          <input type='radio' name='$name' id='$id1' class='opsi_radio' required value='1' $checked_true>
          <label class='proper' for='$id1'>$Ya. $caption</label>
        </div>
      </div>
    </div>
    $hr
  ";
}

function input($name, $value = '', $placeholder = '', $visible = 1, $type = '', $min = 0, $max = 9999, $minlength = 3, $maxlength = 200)
{
  $hideit = $visible ? '' : 'hideit';
  return "<input class='form-control $hideit' name='$name' id='$name' value='$value' placeholder='$placeholder' type=$type min=$min max=$max minlength=$minlength maxlength=$maxlength >";
}

function textarea($name, $value = '', $placeholder = '', $visible = 1,  $minlength = 10, $maxlength = 1000, $rows = 4)
{
  $hideit = $visible ? '' : 'hideit';
  return "<textarea class='form-control $hideit' name='$name' id='$name' placeholder='$placeholder' minlength=$minlength maxlength=$maxlength rows=$rows >$value</textarea>";
}


function radio_dan_input($kalimat, $name, $placeholder = '', $nilai_default = '', $visible_input = 1)
{
  $radio = radio_tf($kalimat, $name, $nilai_default, '', $nilai_default);
  $input = input("input_$name", '', $placeholder, $visible_input);
  return "$radio $input <hr>";
}

function radio_dan_textarea($kalimat, $name, $placeholder = '', $nilai_default = '', $visible_input = 1)
{
  $radio = radio_tf($kalimat, $name, $nilai_default, '', $nilai_default);
  $input = textarea("input_$name", '', $placeholder, $visible_input);
  return "$radio $input <hr>";
}

function tr_input($kolom, $satuan = '', $name, $placeholder, $type, $min, $max, $minlength, $maxlength)
{
  $field = strtolower(str_replace(' ', '_', $kolom));
  $isi = input($name, '', $placeholder, 1, $type, $min, $max, $minlength, $maxlength);
  $kolom = $satuan ? "$kolom ($satuan)" : $kolom;
  return "
    <tr>
      <td>$kolom</td>
      <td>:</td>
      <td>$isi</td>
    </tr>
  ";
}


function ilustrasi($nama, $w = 180, $h = 'auto', $ext = 'png')
{
  $me = "assets/img/ilustrasi/$nama.$ext";
  $na = "assets/img/img_na.jpg";
  $width =  $w . 'px';
  $height = $h == 'auto' ? '' : $h . 'px';
  if (file_exists($me)) {
    return "<img class='img-thumbnail' src='$me' width=$width height=$height />";
  } else {
    return "<img class='img-thumbnail' src='$na' width=$width height=$height />
    <div class='tengah f12 miring'>ilustrasi $nama n/a</div>
    ";
  }
}
// function ilustrasi($nama, $w = 180, $h = 'auto')
// {
//   $me = "../../assets/img/ilustrasi/$nama.jpeg";
//   $na = "../../assets/img/img_na.jpg";
//   $width =  $w . 'px';
//   $height = $h == 'auto' ? '' : $h . 'px';
//   if (file_exists($me)) {
//     return "<img class='img-thumbnail' src='$me' width=$width height=$height />";
//   } else {
//     return "<img class='img-thumbnail' src='$na' width=$width height=$height />
//     <div class='tengah f12 miring'>ilustrasi $nama n/a</div>
//     ";
//   }
// }

# ===========================================================
# FUNCTIONS
# ===========================================================
function pesan_wa($event = 'after_order', $nama_pendaftar, $perusahaan_pendaftar, $order_no, $username_pendaftar, $password_pendaftar)
{
  $jam = date('H');
  $long_date_show = date('F d, Y, H:i:s');
  $tanggal_show = date('d-F-Y');

  if ($jam >= 9) {
    $waktu = "Siang";
  } elseif ($jam >= 15) {
    $waktu = "Sore";
  } elseif ($jam >= 18) {
    $waktu = "Malam";
  } else {
    $waktu = "Pagi";
  }

  if ($event == 'after_order') {
    $link_login = urlencode("https://mmc-clinic.com/?login&as=pendaftar&username=$username_pendaftar");

    return
      "Selamat $waktu Saudara/i <b>$nama_pendaftar</b> dari <b>$perusahaan_pendaftar</b><br><br>Berdasarkan Request Order dari Anda dengan Order No. <i>$order_no</i> tanggal $tanggal_show, kami mengucapkan banyak terimakasih, dan kami telah memverifikasi request Anda, serta membuat username dan password untuk Anda:<br><br>~ <b>Username: $username_pendaftar</b><br>~ <b>Password: $password_pendaftar</b><br><br>Silahkan login ke Website MMC dengan username dan password tersebut untuk melengkapi data dan melanjutkan penawaran Anda.<br><br>$link_login<br><br><br>Untuk biaya Medical Checkup dan biaya lain dapat kita negosiasi bersama tergantung jumlah peserta, jarak lokasi, dan jenis paket (pemeriksaan) yang Anda inginkan. Terimakasih atas perhatian dan kerjasamanya.<br><br>Admin Medical Checkup<br><br>[Message from: MMC Information System, $long_date_show, Bekasi, Indonesia]";
  } else {
    return 'event undefined at pesan_wa()';
  }
}

function html2wa($pesan_html)
{
  $pesan_html = str_replace('<br>', '%0a', $pesan_html);
  $pesan_html = str_replace('<b>', '*', $pesan_html);
  $pesan_html = str_replace('</b>', '*', $pesan_html);
  $pesan_html = str_replace('<i>', '_', $pesan_html);
  $pesan_html = str_replace('</i>', '_', $pesan_html);
  return $pesan_html;
}
