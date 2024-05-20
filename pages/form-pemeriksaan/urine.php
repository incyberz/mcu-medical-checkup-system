<?php
$arr = [
  'urine_warna' => [
    'blok' => 'multi-radio',
    'question' => 'urine_warna?',
    'value_default' => $mcu['urine_warna'] ?? 'Kuning',
    'values' => ['Bening', 'Kuning', 'Kemerahan'],
  ],
  'urine_kejernihan' => [
    'blok' => 'multi-radio',
    'question' => 'urine_kejernihan?',
    'value_default' => $mcu['urine_kejernihan'] ?? 'Biasa',
    'values' => ['Bening', 'Biasa', 'Keruh'],
  ],
  'urine_leucocytes' => [
    'blok' => 'multi-radio',
    'question' => 'urine_leucocytes?',
    'value_default' => $mcu['urine_leucocytes'] ?? 'Negatif',
    'values' => ['Negatif +', 'Negatif'],
  ],
  'urine_nitrit' => [
    'blok' => 'multi-radio',
    'question' => 'urine_nitrit?',
    'value_default' => $mcu['urine_nitrit'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'urine_urobilinogen' => [
    'blok' => 'multi-radio',
    'question' => 'urine_urobilinogen?',
    'value_default' => $mcu['urine_urobilinogen'] ?? 'Positif 1',
    'values' => ['Negatif', 'Positif 1'],
  ],
  'urine_protein' => [
    'blok' => 'multi-radio',
    'question' => 'urine_protein?',
    'value_default' => $mcu['urine_protein'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif 1'],
  ],
  'urine_ph' => [
    'blok' => 'input-range',
    'label' => 'urine_ph?',
    'type' => 'number',
    'range' => [5, 6, 7, 8],
    'step' => '0.1'
  ],
  'urine_blood' => [
    'blok' => 'multi-radio',
    'question' => 'urine_blood?',
    'value_default' => $mcu['urine_blood'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'urine_sg' => [
    'blok' => 'input-range',
    'label' => 'urine_sg?',
    'type' => 'number',
    'range' => [1.000, 1.100],
    'step' => '0.001'
  ],
  'urine_keton' => [
    'blok' => 'multi-radio',
    'question' => 'urine_keton?',
    'value_default' => $mcu['urine_keton'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'urine_bilirubin' => [
    'blok' => 'multi-radio',
    'question' => 'urine_bilirubin?',
    'value_default' => $mcu['urine_bilirubin'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'urine_glucosa' => [
    'blok' => 'multi-radio',
    'question' => 'urine_glucosa?',
    'value_default' => $mcu['urine_glucosa'] ?? 'Negatif',
    'values' => ['Negatif', 'Positif'],
  ],
  'urine_leukosit' => [
    'blok' => 'multi-radio',
    'question' => 'urine_leukosit?',
    'value_default' => $mcu['urine_leukosit'] ?? 'Opsi Default',
    'values' => ['Opsi Zzz', 'Opsi Default'],
  ],
  'urine_eritrosit' => [
    'blok' => 'multi-radio',
    'question' => 'urine_eritrosit?',
    'value_default' => $mcu['urine_eritrosit'] ?? 'Opsi Default',
    'values' => ['Opsi Zzz', 'Opsi Default'],
  ],
  'urine_silinder' => [
    'blok' => 'multi-radio',
    'question' => 'urine_silinder?',
    'value_default' => $mcu['urine_silinder'] ?? 'Opsi Default',
    'values' => ['Opsi Zzz', 'Opsi Default'],
  ],
  'urine_sel_epitel' => [
    'blok' => 'multi-radio',
    'question' => 'urine_sel_epitel?',
    'value_default' => $mcu['urine_sel_epitel'] ?? 'Opsi Default',
    'values' => ['Opsi Zzz', 'Opsi Default'],
  ],
  'urine_kristal' => [
    'blok' => 'multi-radio',
    'question' => 'urine_kristal?',
    'value_default' => $mcu['urine_kristal'] ?? 'Opsi Default',
    'values' => ['Opsi Zzz', 'Opsi Default'],
  ],
  'urine_bakteri' => [
    'blok' => 'multi-radio',
    'question' => 'urine_bakteri?',
    'value_default' => $mcu['urine_bakteri'] ?? 'Opsi Default',
    'values' => ['Opsi Zzz', 'Opsi Default'],
  ],
];
