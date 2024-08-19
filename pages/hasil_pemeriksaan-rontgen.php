<?php
set_title('Hasil Rontgen');

$id_detail = 134;
$str_hasil = $arr_id_detail[134];
if ($str_hasil) {
  $hasil['COR'] = 'Jantung Tidak Membesar ( CTR < 50% )';
  $hasil['AORTA'] = 'Normal';
  $hasil['PULMO'] = 'Tidak Tampak Infiltrat / Lesi Pada Kedua Paru. Corakan Bronchovasculer Normal. Kedua Hemidiafragma Licin. Sinus Kostoferenikus Kanan-Kiri Lancip. Tulang-Tulang Dan Soft Tissue Normal';
  $hasil['KESAN'] = "<span class='bold green proper'>Dalam batas normal</span>";

  if ($str_hasil == 'dalam batas normal' || $str_hasil == 'normal') {
    $red = 'green';
  } else {
    $red = 'red';
    $tmp = explode('kesan_tambahan: ', $str_hasil);
    $kesan_tambahan = $tmp[1] ?? null;
    $str_hasil2 = $tmp[0];

    $arr = explode(', ', $str_hasil2);

    $abnor['COR'] = '';
    $abnor['AORTA'] = '';
    $abnor['PULMO'] = '';
    $abnor['KESAN'] = '';
    foreach ($arr as $key => $value) {
      $awalan = strtolower(substr($value, 0, 5));
      if ($awalan == 'jantu') {
        $abnor['COR'] .= "<span class='red'>$value<span>";
      } elseif ($awalan == 'aorta') {
        $abnor['AORTA'] .= "<span class='red'>$value<span>";
      } elseif ($awalan == 'pulmo') {
        $abnor['PULMO'] .= "<li class='red'>$value</li>";
      }
    }

    $hasil['KESAN'] = $kesan_tambahan ?? 'Terdapat Kelainan Paru';
    $hasil['KESAN'] = "<span class=red>$hasil[KESAN]</span>"; // add style red

    $hasil['COR'] = $abnor['COR'] ? $abnor['COR'] : $hasil['COR'];
    $hasil['AORTA'] = $abnor['AORTA'] ? $abnor['AORTA'] : $hasil['AORTA'];
    $hasil['PULMO'] = $abnor['PULMO'] ? "<ul class='m0 pl2'>$abnor[PULMO]</ul>" : $hasil['PULMO'];
  }
  $str_hasil = "
    <div class=''>
      <div class='mb2 mt4 bold'>HASIL PEMERIKSAAN FOTO THORAX</div>
      <table class='table th_toska td_trans kiri'>
        <tr>
          <td>COR</td>
          <td>:</td>
          <td>$hasil[COR]</td>
        </tr>
        <tr>
          <td>AORTA</td>
          <td>:</td>
          <td>$hasil[AORTA]</td>
        </tr>
        <tr>
          <td>PULMO</td>
          <td>:</td>
          <td>$hasil[PULMO]</td>
        </tr>
        <tr>
          <td>KESAN</td>
          <td>:</td>
          <td>$hasil[KESAN]</td>
        </tr>
      </table>
    </div>
  ";
}



echo "
  $str_hasil

";
