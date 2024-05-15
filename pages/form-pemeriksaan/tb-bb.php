<?php
$section = 'tb_bb';

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

  $blok_inputs .= "
    <div class='wadah gradasi-toska'>
      <div class='flexy flex-center'>
        <div class='f14 darkblue miring pt1'>$v[label]</div>
        <div>
          <input 
            id='$key' 
            name='$key' 
            placeholder='$v[placeholder]' 
            type='$v[type]' 
            $required
            class='form-control $v[class]' 
            min='$v[min]' 
            max='$v[max]' 
            minlength='$v[minlength]' 
            maxlength='$v[maxlength]' 
            style='max-width:100px'
          >          
        </div>
        <div class='f14 abu miring pt1'>$v[satuan]</div>
      </div>
      <input type='range' class='form-range range' min='$min_range' max='$max_range' id='range__$key' value='$val_range'>
      <div class='flexy flex-between f12 consolas abu'>
        $div_range
      </div>
    </div>  
  ";
}

$tanggal_show = date('d-F-Y H:i');

$form_pemeriksaan = "
  <form method='post' class='form-pemeriksaan wadah bg-white'>

    $blok_inputs
    <div class='flexy mb2 flex-center'>
      <input type=checkbox required id=cek>
      <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
    </div>
    <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$section'>Submit Data Pasien</button>
    <div class='tengah f12 mt1 abu'>Diperiksa oleh <span class='darkblue'>$nama_user</span> pada tanggal <span class=consolas>$tanggal_show</span></div>
  </form>

";
