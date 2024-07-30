<?php
// $arr = ['COR', 'AORTA', 'PULMO_LESI', 'PULMO_BRON', 'PULMO_HEMI', 'PULMO_SIN', 'PULMO_TST'];

$input = [];

$caption = [
  'COR' => [
    'NORMAL' => 'Tidak Membesar <br>(CTR < 50%)',
    'ABNOR' => 'Membesar <br>(CTR > 50%)',
  ],
  'AORTA' => [
    'NORMAL' => 'Normal',
    'ABNOR' => 'Meningkat',
  ],

  'PULMO_LESI' => [
    'NORMAL' => 'Tidak Tampak',
    'ABNOR' => 'Tampak',
  ],

  'PULMO_BRON' => [
    'NORMAL' => 'Normal',
    'ABNOR' => 'Meningkat',
  ],

  'PULMO_HEMI' => [
    'NORMAL' => [
      'KA' => 'Kanan Licin',
      'KI' => 'Kiri Licin',
    ],
    'ABNOR' => [
      'KA' => 'Kanan Kasar',
      'KI' => 'Kiri Kasar',
    ],
  ],

  'PULMO_SIN' => [
    'NORMAL' => [
      'KA' => 'Kanan Licin',
      'KI' => 'Kiri Licin',
    ],
    'ABNOR' => [
      'KA' => 'Kanan Kasar',
      'KI' => 'Kiri Kasar',
    ],
  ],

  'PULMO_TST' => [
    'NORMAL' => 'Normal',
    'ABNOR' => 'Abnormal',
  ],

];

$arr_radio = [
  'COR_ABNOR' => [
    'id_detail' => 'COR',
    'id' => 'COR__COR_ABNOR',
    'option_value' => 'COR_ABNOR',
    'caption' => $caption['COR_ABNOR'],
  ],
  'COR_NORMAL' => [
    'id_detail' => 'COR',
    'id' => 'COR__COR_NORMAL',
    'option_value' => 'COR_NORMAL',
    'value_default' => 'COR_NORMAL',
    'caption' => $caption['COR_NORMAL'],
  ],
];
$input['COR'] = radio_toolbar($arr_radio);

# ============================================================
# AORTA
# ============================================================
$arr_radio = [
  'AORTA_ABNOR' => [
    'id_detail' => 'AORTA',
    'id' => 'AORTA__AORTA_ABNOR',
    'option_value' => 'AORTA_ABNOR',
    'caption' => $caption['AORTA_ABNOR'],
  ],
  'AORTA_NORMAL' => [
    'id_detail' => 'AORTA',
    'id' => 'AORTA__AORTA_NORMAL',
    'option_value' => 'AORTA_NORMAL',
    'value_default' => 'AORTA_NORMAL',
    'caption' => $caption['AORTA_NORMAL'],
  ],
];
$input['AORTA'] =  radio_toolbar($arr_radio);
$input['PULMO_LESI'] = ''; // radio_toolbar($arr_radio);
$input['KESAN'] = ''; // radio_toolbar($arr_radio);

$blok_inputs = "
  <h3 class='tengah f16'>HASIL PEMERIKSAAN FOTO THORAX</h3>

  <table class='table table-bordered'>
    <tr><td>COR</td><td><div class='tengah'>Jantung</div>$input[COR]</td></tr>
    <tr><td>AORTA</td><td><div class='tengah'>Aorta</div>$input[AORTA]</td></tr>
    <tr><td>PULMO_LESI</td><td><div class='tengah'>Infiltrat/Lesi</div>$input[PULMO_LESI]</td></tr>
    <tr><td>KESAN</td><td><div class='tengah'>Kesimpulan</div>$input[KESAN]</td></tr>
  </table>

";

$form_pemeriksaan = "
  <form method='post' class='form-pemeriksaan wadah bg-white' id=blok_form>

    $blok_inputs

    <div class='flexy mb2 flex-center'>
      <input type=checkbox required id=cek>
      <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
    </div>
    <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$id_pasien'>Submit Data</button>
    <input type=hidden name=last_pemeriksaan value='$nama_pemeriksaan by $nama_user'>
    <input type=hidden name=id_pemeriksaan value='$id_pemeriksaan'>
  </form>
";
