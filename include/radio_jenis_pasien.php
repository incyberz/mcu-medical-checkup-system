<?php
$arr_radio = [
  'bpj' => [
    'id_detail' => 'jenis_pasien',
    'id' => 'jenis_pasien__bpj',
    'option_class' => 'jenis_pasien',
    'option_value' => 1,
    'checked' => '',
    'caption' => 'BPJS',
  ],
  'idv' => [
    'id_detail' => 'jenis_pasien',
    'id' => 'jenis_pasien__idv',
    'option_class' => 'jenis_pasien',
    'option_value' => 1,
    'checked' => '',
    'caption' => 'Individu',
  ],
  'cor' => [
    'id_detail' => 'jenis_pasien',
    'id' => 'jenis_pasien__cor',
    'option_class' => 'jenis_pasien',
    'option_value' => 1,
    'checked' => 'checked',
    'caption' => 'Corporate',
  ],
];
echo "<style>  .radio-toolbar input[type='radio']:checked + label {border: solid 3px blue}
</style>";
$radio_jenis_pasien = radio_toolbar($arr_radio);
