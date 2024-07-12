<?php
function radio_toolbar($arr_radio, $full_width = true)

{
  if (!is_array($arr_radio)) die('radio_toolbar function membutuhkan parameter berupa array');
  $html = '';
  $i = 0;
  foreach ($arr_radio as $key => $radio) {
    $i++;
    $name = $radio['name'] ?? die('radio_toolbar function need index [name]');
    $value = $radio['value'] ?? die('radio_toolbar function need index [value]');
    $id = $radio['id'] ?? $name . "__$value";
    $classes = $radio['classes'] ?? die('radio_toolbar function need index [classes]');
    $checked = $radio['checked'] ?? die('radio_toolbar function need index [checked]');
    $caption = $radio['caption'] ?? die('radio_toolbar function need index [caption]');
    $flex_1 = $full_width ? 'flex:1' : '';
    $html .= "
      <div class='radio-toolbar abu mb2 mt2' style='$flex_1'>
        <input type='radio' name='$name' id='$id' class='opsi_radio' required value='$value' $checked >
        <label class='proper $classes' for='$id'>$caption</label>
      </div>
    ";
  }
  return "<div style='display:flex; gap:5px'>$html</div>";
}

function radio_toolbar2($label, $id_row_as_name, $option_values, $option_labels = '', $class = 'mb2 f18 darkblue tengah', $option_class = '', $value_default = '')
{
  $arr_value = explode(',', $option_values);
  $arr_label = explode(',', $option_labels);


  $arr = [];
  foreach ($arr_value as $key => $value) {
    $value = trim($value);
    if ($value == '') continue;
    $caption = (isset($arr_label[$key]) and $arr_label[$key]) ? $arr_label[$key] : $value;
    $arr[$value] = [
      'name' => "$id_row_as_name",
      'id' => "$id_row_as_name" . "__$value",
      'classes' => $option_class ?? '',
      'value' => $value,
      'checked' => $value_default,
      'caption' => $caption,
    ];
  }
  // echo '<pre>';
  // var_dump($arr_value);
  // echo '</pre>';
  // exit;

  return "<div class='$class'>$label</div>" . radio_toolbar($arr);
}
