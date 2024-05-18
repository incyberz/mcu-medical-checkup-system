<?php
function create_radio(
  $label,
  $label_class = '',
  $names,
  $values,
  $label_radios = '',
  $radios_class = '',
  $nilai_default = '',
  $required = 'required',
  $all_radios_class = '',
  $global_class = '',

) {

  if ($nilai_default != '') {
    if ($nilai_default == 1) {
      $checked_ya = 'checked';
    } else {
      $checked_tidak = 'checked';
    }
  }

  $html_radios = '';
  foreach ($names as $key => $name) {
    $value = $values[$key];
    $class_radio = $radios_class[$key];
    $label_radio = $label_radios[$key];
    $checked = ''; // zzz by pass

    $html_radios .= "
        <div class='col-6'>
          <input type='radio' name='$name' id='$name-$value' class='opsi_radio $class_radio' $required value='$value' $checked >
          <label class='proper' for='$name-$value'>$label_radio</label>
        </div>    
    ";
  }

  return "
    <div class='$global_class'>
      <label class='darkblue mb2 $label_class'>$label</label>
      <div class='radio-toolbar $all_radios_class'>
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
