<?php
$arr = [
  'tekanan_sistolik' => [
    'blok' => 'input-range',
    'label' => 'Tekanan Sistolik',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['tekanan_sistolik'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 60,
    'max' => 150,
    'range' => [80, 90, 100, 110, 120, 130],
    'satuan' => 'mmHg'
  ],
  'tekanan_diastol' => [
    'blok' => 'input-range',
    'label' => 'Tekanan Diastol',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['tekanan_diastol'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 40,
    'max' => 110,
    'range' => [40, 50, 60, 70, 80, 90, 100],
    'satuan' => 'mmHg'
  ],
  'pernafasan' => [
    'blok' => 'input-range',
    'label' => 'Pernafasan',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['pernafasan'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 8,
    'max' => 30,
    'range' => [10, 12, 14, 16, 18, 20, 22],
    'satuan' => 'per menit'
  ],
  'saturasi' => [
    'blok' => 'input-range',
    'label' => 'Saturasi Oksigen',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['saturasi'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 50,
    'max' => 100,
    'range' => [80, 85, 90, 95, 100],
    'satuan' => 'persen'
  ],
  'buta_warna' => [
    'blok' => 'input-range',
    'label' => 'Kemampuan membedakan warna',
    'type' => 'number',
    'placeholder' => '...',
    'value' => $mcu['buta_warna'],
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 3,
    'max' => 12,
    'range' => [3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
  ],
];
