<?php
# ============================================================
# VISUS MATA
# ============================================================
if (isset($arr_id_detail[142])) {
  $pasien['visus_mata'] = [
    'ki' => [
      'Visus Mata Kiri' => "$arr_id_detail[142]/20 ",
      'Refleks Cahaya' => $arr_id_detail[143],
      'Konjungtiva' => $arr_id_detail[144],
      'Bentuk Bola Mata' => $arr_id_detail[145],
      'Ukuran Pupil' => "$arr_id_detail[146] mm",
    ],
    'ka' => [
      'Visus Mata Kanan' => "$arr_id_detail[14]/20 ",
      'Refleks Cahaya' => $arr_id_detail[15],
      'Konjungtiva' => $arr_id_detail[16],
      'Bentuk Bola Mata' => $arr_id_detail[17],
      'Ukuran Pupil' => "$arr_id_detail[18] mm",
    ],
  ];
} else {
  $pasien['visus_mata'] = [
    'ki' => [
      'Visus Mata Kiri' => 'no-data',
      'Refleks Cahaya' => 'no-data',
      'Konjungtiva' => 'no-data',
      'Bentuk Bola Mata' => 'no-data',
      'Ukuran Pupil' => 'no-data',
    ],
    'ka' => [
      'Visus Mata Kanan' => 'no-data',
      'Refleks Cahaya' => 'no-data',
      'Konjungtiva' => 'no-data',
      'Bentuk Bola Mata' => 'no-data',
      'Ukuran Pupil' => 'no-data',
    ],
  ];
}
