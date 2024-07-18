<?php
$arr_radio = [
  'bpj' => [
    'id_detail' => 'jenis_pasien',
    'id' => 'jenis_pasien__bpj',
    'option_class' => 'jenis_pasien',
    'option_value' => 'bpj',
    'checked' => '',
    'caption' => 'BPJS',
  ],
  'idv' => [
    'id_detail' => 'jenis_pasien',
    'id' => 'jenis_pasien__idv',
    'option_class' => 'jenis_pasien',
    'option_value' => 'idv',
    'checked' => '',
    'caption' => 'Individu',
  ],
  'cor' => [
    'id_detail' => 'jenis_pasien',
    'id' => 'jenis_pasien__cor',
    'option_class' => 'jenis_pasien',
    'option_value' => 'cor',
    'checked' => 'checked',
    'caption' => 'Corporate',
    'value_default' => 'cor'
  ],
];
echo "<style>  .radio-toolbar input[type='radio']:checked + label {border: solid 3px blue}
</style>";
$radio_jenis_pasien = radio_toolbar($arr_radio);
