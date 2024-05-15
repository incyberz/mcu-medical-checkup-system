<?php
set_title('Awal Input Data');
set_judul('Awal Input Data');

$section = 'awal-input-data';

$arr = [
  'berat_badan' => [
    'label' => 'Berat Badan',
    'type' => 'number',
    'placeholder' => '...',
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 40,
    'max' => 120,
    'minlength' => 0,
    'maxlength' => 0,
    'range' => [40, 50, 60, 70, 80, 90],
    'satuan' => 'kg'
  ],
  'tinggi_badan' => [
    'label' => 'Tinggi Badan',
    'type' => 'number',
    'placeholder' => '...',
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 120,
    'max' => 200,
    'minlength' => 0,
    'maxlength' => 0,
    'range' => [140, 150, 160, 170, 180],
    'satuan' => 'cm'
  ],
  'lingkar_perut' => [
    'label' => 'Lingkar Perut',
    'type' => 'number',
    'placeholder' => '...',
    'required' => 1,
    'class' => 'mb2 f18 darkblue tengah',
    'min' => 60,
    'max' => 120,
    'minlength' => 0,
    'maxlength' => 0,
    'range' => [70, 80, 90, 100],
    'satuan' => 'cm'
  ],
];

$blok_inputs = '';
foreach ($arr as $key => $v) {

  $div_range = '';
  $min_range = 0;
  $max_range = 0;
  $i = 0;
  foreach ($v['range'] as $key2 => $range_value) {
    $i++;
    if ($i == 1) $min_range = $range_value;
    $div_range .= "<div>$range_value</div>";
    $max_range = $range_value;
  }
  $val_range = intval(($max_range - $min_range) / 2) + $min_range;
  $required = $v['required'] ? 'required' : '';
}

$tanggal_show = date('d-F-Y H:i');
$tanggal = date('d-F-Y');
$pukul = date('H:i');

$form_pemeriksaan = "
  <form method='post' class='form-pemeriksaan wadah bg-white'>
    <div class='wadah gradasi-toska'>
      <img src='assets/img/ilustrasi/medical-checkup.jpg' class='img-fluid img-thumbnail' />
    </div>  
    <div class='flexy mb2 flex-center'>
      <input type=checkbox required id=cek>
      <label for=cek>Pasien mulai masuk pemeriksaan pada tanggal <b class=darkblue>$tanggal</b> pukul <b class=darkblue>$pukul</b>.</label>
    </div>
    <button class='btn btn-primary w-100' name=btn_mulai_pemeriksaan value='$section'>Mulai Pemeriksaan</button>
    <div class='tengah f12 mt1 abu'>Disubmit oleh <span class='darkblue'>$nama_user</span> pada tanggal <span class=consolas>$tanggal_show</span></div>
  </form>

";
