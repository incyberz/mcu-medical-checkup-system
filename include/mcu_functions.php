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
