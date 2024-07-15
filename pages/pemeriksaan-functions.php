<?php



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
