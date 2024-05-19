<?php
function array_gigi(
  $question,
  $value_default = '',
  $question_class = '',
  $wrapper_class = '',
  $global_class = '',

) {

  if ($value_default) {
    $arr_status_gigi = explode(',', $value_default);
  } else {
    for ($i = 0; $i < 32; $i++) {
      $arr_status_gigi[$i] = 1; // gigi OK
    }
  }

  $gigi['atas_kiri'] = [18,  17,  16,  15,  14,  13,  12,  11];
  $gigi['atas_kanan'] = [21,  22,  23,  24,  25,  26,  27,  28];
  $gigi['bawah_kiri'] = [48,  47,  46,  45,  44,  43,  42,  41];
  $gigi['bawah_kanan'] = [31,  32,  33,  34,  35,  36,  37,  38];

  $divs = '';
  $div_gigi = [];
  $i = 0;
  foreach ($gigi as $posisi => $arrg) {
    $div_gigi[$posisi] = '';
    foreach ($arrg as $kode_gigi) {
      $status_gigi = $arr_status_gigi[$i];
      $bg = 'bg-white';
      if ($status_gigi == -1) {
        $bg = 'gradasi-kuning';
      } elseif ($status_gigi == -2) {
        $bg = 'gradasi-merah';
      }
      $div_gigi[$posisi] .= "
        <div class='$bg p1 bordered br5 item-gigi pointer' id=item-gigi__$kode_gigi>
          <div class='f12'>$kode_gigi</div>
          <input 
            class='debuga bg-red f9' 
            id='status_gigi__$kode_gigi' 
            name='status_gigi__$kode_gigi' 
            value=$status_gigi 
            style='width:20px'
          />
          <div class='f30' id='simbol_gigi__$kode_gigi'>
            <i class='bx bx-check'></i>
          </div>
        </div>
      ";
      $i++; // next gigi
    }

    $divs .= "
      <div class='col-6 mb4'>
        <div class='blok-gigi p2 pl2 pr2 flexy flex-between br5'>
          $div_gigi[$posisi]
        </div>
      </div>
    ";
  }

  return "
    <div class='$global_class'>
      <label class='darkblue mb2 $question_class'>$question</label>
      <div class='radio-toolbar $wrapper_class'>
        <div class='row'>
          $divs
        </div>
      </div>
    </div>
  ";
}


function create_radio(
  $question,
  $labels,
  $values,
  $name,
  $value_default = '',
  $required = 'required',
  $question_class = [],
  $radios_class = [],
  $wrapper_class = '',
  $global_class = '',

) {

  $col = intval(12 / count($values));
  $labels = $labels ? $labels : $values;

  $html_radios = '';
  foreach ($values as $key => $value) {
    $class_radio = $radios_class ? $radios_class[$key] : '';
    $label = $labels[$key];
    $checked = $value == $value_default ? 'checked' : '';

    $html_radios .= "
        <div class='col-$col'>
          <input type='radio' name='$name' id='$name-$value' class='opsi_radio $class_radio' $required value='$value' $checked >
          <label class='proper' for='$name-$value'>$label</label>
        </div>    
    ";
  }

  return "
    <div class='$global_class'>
      <label class='darkblue mb2 $question_class'>$question</label>
      <div class='radio-toolbar $wrapper_class'>
        <div class='row'>
          $html_radios
        </div>
      </div>
    </div>
  ";
}

function create_radio_yt(
  $label,
  $name,
  $nilai_default = '',
  $caption = '',
  $Ya = 'Ya',
  $Tidak = 'Tidak',
  $class_label = '',
  $value_tidak = 0,
  $value_ya = 1,
  $required = 'required'
) {
  $id_tidak = "$name-tidak";
  $id_ya = "$name-ya";

  $checked_ya = '';
  $checked_tidak = '';
  if ($nilai_default != '') {
    if ($nilai_default == 1) {
      $checked_ya = 'checked';
    } else {
      $checked_tidak = 'checked';
    }
  }

  return "
    <label class='darkblue mb2 $class_label'>$label</label>
    <div class='radio-toolbar abu'>
      <div class='row'>
        <div class='col-6'>
          <input type='radio' name='$name' id='$id_tidak' class='opsi_radio' $required value='$value_tidak' $checked_tidak >
          <label class='proper' for='$id_tidak'>$Tidak $caption</label>
        </div>
        <div class='col-6'>
          <input type='radio' name='$name' id='$id_ya' class='opsi_radio' $required value='$value_ya' $checked_ya>
          <label class='proper' for='$id_ya'>$Ya. $caption</label>
        </div>
      </div>
    </div>
  ";
}
