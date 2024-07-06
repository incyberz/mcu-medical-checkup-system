<?php
$arr_radio = [
  'bpj' => [
    'name' => 'jenis_pasien',
    'id' => 'jenis_pasien__bpj',
    'classes' => 'jenis_pasien',
    'value' => 1,
    'checked' => '',
    'caption' => 'BPJS',
  ],
  'idv' => [
    'name' => 'jenis_pasien',
    'id' => 'jenis_pasien__idv',
    'classes' => 'jenis_pasien',
    'value' => 1,
    'checked' => '',
    'caption' => 'Individu',
  ],
  'cor' => [
    'name' => 'jenis_pasien',
    'id' => 'jenis_pasien__cor',
    'classes' => 'jenis_pasien',
    'value' => 1,
    'checked' => 'checked',
    'caption' => 'Corporate',
  ],
];
echo "<style>  .radio-toolbar input[type='radio']:checked + label {border: solid 3px blue}
</style>";
$radio_jenis_pasien = radio_toolbar($arr_radio);
