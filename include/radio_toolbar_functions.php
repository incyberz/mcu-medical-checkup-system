<?php
function radio_toolbar($arr_radio, $full_width = true)

{
  if (!is_array($arr_radio)) die('radio_toolbar function membutuhkan parameter berupa array');
  $html = '';
  $i = 0;
  foreach ($arr_radio as $key => $radio) {
    $i++;
    $id_detail = $radio['id_detail'] ?? die('radio_toolbar function need index [id_detail]');
    $value_from_db = $radio['value_from_db'] ?? null;
    $option_value = $radio['option_value'] ?? die('radio_toolbar function need index [option_value]');
    $option_class = $radio['option_class'] ?? null;
    $caption = $radio['caption'] ?? die('radio_toolbar function need index [caption]');
    $flex_1 = $full_width ? 'flex:1' : '';
    $value_default = $radio['value_default'] ?? null;

    // lowering id and option_value
    $option_value = strtolower(trim($option_value));
    $value_default = $value_default ? strtolower(trim($value_default)) : null;
    $id = $radio['id'] ?? $id_detail . "__$option_value";
    $label_id = "label__$id_detail" . "__$option_value";

    $checked = $option_value == $value_default ? 'checked' : '';

    // replace checked with value from DB
    if ($value_from_db) {
      $checked = $option_value == $value_from_db ? 'checked' : '';
    }
    echo "<hr>$checked = $option_value == $value_default ";

    $html .= "
      <div class='radio-toolbar abu mb2 mt2' style='$flex_1'>
        <input type='radio' name='$id_detail' id='$id' class='opsi_radio' required value='$option_value' $checked >
        <label id=$label_id class='label__$id_detail proper $option_class' for='$id'>$caption</label>
      </div>
    ";
  }
  return "<div style='display:flex; gap:5px'>$html</div>";
}

function radio_toolbar2($label, $id_detail, $option_values, $option_labels = null, $class = null, $option_class = null, $value_default = null, $value_from_db = null)
{
  $arr_value = explode(',', $option_values);
  $arr_label = explode(',', $option_labels);

  $class = $class ? $class : 'mb2 f18 darkblue tengah';
  $value_default = $value_default ? strtolower(trim($value_default)) : null;

  $arr = [];
  foreach ($arr_value as $key => $option_value) {
    $option_value = trim($option_value);
    if ($option_value == '') continue;
    $caption = (isset($arr_label[$key]) and $arr_label[$key]) ? $arr_label[$key] : $option_value;

    $arr[$option_value] = [
      'id_detail' => "$id_detail",
      'option_class' => $option_class,
      'option_value' => $option_value,
      'value_from_db' => $value_from_db,
      'value_default' => $value_default,
      'caption' => $caption,
    ];
  }

  $red_class = '';
  if (!$value_from_db) {
    $red_class = 'gradasi-merah p2';
    $red_class = $value_default ? '' : $red_class;
  }

  $radio_toolbar = radio_toolbar($arr);

  return "
    <div class='$red_class'>
      <div class='flexy flex-between' >
        <div class='f10 abu miring'>id: $id_detail</div>
        <div>
          <a href='?manage_pemeriksaan_detail&id_detail=$id_detail' target=_blank onclick='return confirm(`Edit Pertanyaan ini?`)'>
            <img src='assets/img/icon/edit5.png' height=20px>
          </a>
        </div>
      </div>
      <div class='$class'>$label</div>
      $radio_toolbar
      <div class='hideita f12 abu' ><i>default</i> : <span class=proper id=value_default__$id_detail>$value_default</span></div>
    </div>
  ";
}
