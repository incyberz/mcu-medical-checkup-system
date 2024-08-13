<?php
$id_detail = 134;
$hasil = $arr_id_detail[134] ?? ''; // atau kosong jika blm ada
if ($hasil) {
  $red = $hasil == 'dalam batas normal' ? 'green' : 'red';
  $hasil = str_replace(', ', '<br>', $hasil);
  $hasil = "
    <div class='wadah gradasi-kuning'>
      <div class='mb2 mt2'>Hasil Pemeriksaan sebelumnya:</div>
      <div class='$red bold'>$hasil</div>
    </div>
    <hr style='margin: 60px 0'>
  ";
}


$input = [];

$arr = [
  'COR' => [
    'label' => 'Jantung',
    'NORMAL' => 'Tidak Membesar <br>(CTR < 50%)',
    'ABNOR' => 'Membesar <br>(CTR > 50%)',
  ],
  'AORTA' => [
    'label' => 'Aorta',
    'NORMAL' => 'Normal',
    'ABNOR' => 'Meningkat',
  ],

  'PULMO_LESI' => [
    'label' => 'Infiltrat/Lesi',
    'NORMAL' => 'Tidak Tampak',
    'ABNOR' => 'Tampak',
  ],

  'PULMO_BRON' => [
    'label' => 'Corakan Bronchovasculer',
    'NORMAL' => 'Normal',
    'ABNOR' => 'Meningkat',
  ],

  'PULMO_HEMI' => [
    'label' => 'Hemidiafragma',
    'KA' => [
      'NORMAL' => 'Licin',
      'ABNOR' => 'Kasar',
    ],
    'KI' => [
      'NORMAL' => 'Licin',
      'ABNOR' => 'Kasar',
    ],
  ],

  'PULMO_SIN' => [
    'label' => 'Sinus Kostoferenikus',
    'KA' => [
      'NORMAL' => 'Lancip',
      'ABNOR' => 'Tumpul',
    ],
    'KI' => [
      'NORMAL' => 'Lancip',
      'ABNOR' => 'Tumpul',
    ],
  ],

  'PULMO_TST' => [
    'label' => 'Tulang-tulang dan Soft Tisue',
    'NORMAL' => 'Normal',
    'ABNOR' => 'Abnormal',
  ],

  'KESAN' => [
    'label' => 'Kesan / Kesimpulan',
    'NORMAL' => 'Dalam Batas Normal',
    'ABNOR' => 'Abnormal',
  ],

];

$zzz = '';

foreach ($arr as $key => $value) {
  $label =  $value['label'];
  $NORMAL =  $value['NORMAL'] ?? $value['KA'];
  $ABNOR =  $value['ABNOR'] ?? $value['KI'];
  if (substr($key, 0, 5) == 'PULMO') $label = "Pulmonary > $label";

  if (is_array($NORMAL)) {
    $opsi_bagian = [];
    $opsi_bagian['KA'] = '';
    $opsi_bagian['KI'] = '';

    $KA = $NORMAL;
    $KI = $value['KI'];

    $arr2 = [
      'KA' => $KA,
      'KI' => $KI,
    ];
    $arr3 = [
      'KA' => 'Kanan',
      'KI' => 'Kiri',
    ];

    foreach ($arr2 as $KA_KI => $arr_ka_ki) {

      $label2 =   "$label > $arr3[$KA_KI]";

      $key2 = $key . "__" . $KA_KI;

      $option_values = $key2 . "__ABNOR,$key2" . "__NORMAL";
      $option_labels = $key2 . "__ABNOR,$key2" . "__NORMAL";
      $option_labels = "$arr_ka_ki[ABNOR],$arr_ka_ki[NORMAL]";
      $class = '';
      $option_class = 'opsi_rontgen';
      $value_default = $key2 . '__NORMAL';
      $value_from_db = null;

      $opsi_bagian[$KA_KI] = radio_toolbar2(
        $label2,
        $key2,
        $option_values,
        $option_labels,
        $class,
        $option_class,
        $value_default,
        $value_from_db,
        false,
        false,
        false
      );
    }



    $opsi = "
      <div class=row>
        <div class='col-6'>
          $opsi_bagian[KI]
        </div>
        <div class='col-6'>
          $opsi_bagian[KA]
        </div>
      </div>
    ";
    $div_kelainan_kika = "
      <div class='kelainan red bold hideita' id=kelainan_ka__$key></div>
      <div class='kelainan red bold hideita' id=kelainan_ki__$key></div>
    ";
  } else { // no array
    $div_kelainan_kika = $key == 'KESAN' ? '' : "<div class='kelainan red bold hideita' id=kelainan__$key></div>";
    $option_values = $key . "__ABNOR,$key" . "__NORMAL";
    $option_labels = $key . "__ABNOR,$key" . "__NORMAL";
    $option_labels = "$ABNOR,$NORMAL";
    $class = '';
    $option_class = 'opsi_rontgen';
    $value_default = $key . '__NORMAL';
    $value_from_db = null;

    $opsi = radio_toolbar2(
      $label,
      $key,
      $option_values,
      $option_labels,
      $class,
      $option_class,
      $value_default,
      $value_from_db,
      false,
      false,
      false
    );
  }

  $toggle_id = "blok_catatan$key" . '__toggle';
  $Catatan_hide = $key == 'KESAN' ? 'hideit' : '';
  $ket_abnormal = $key == 'KESAN' ? "<div class='red bold mb2 f20'>ABNORMAL</div>" : '';

  $zzz .= "
    <div class='wadah gradasi-toska mb4'>
      <div>$opsi</div>
      $div_kelainan_kika
      <div class='kiri $Catatan_hide'><span class='btn_aksi f12' id=$toggle_id>Catatan :</span></div>
      <div class='hideit mt2' id=blok_catatan$key>
        $ket_abnormal
        <textarea class='form-control mb4 catatan' rows=5 name=catatan__$key id=catatan__$key></textarea>
      </div>
    </div>
  ";
}

$blok_inputs = "
  <h3 class='tengah f16'>HASIL PEMERIKSAAN FOTO THORAX</h3>

  $zzz

";

$form_pemeriksaan = "
  $hasil
  <form method='post' class='form-pemeriksaan wadahZZZ bg-whiteZZZ' id=blok_form>

    $blok_inputs

    <div class='flexy mb2 flex-center'>
      <input type=checkbox required id=cek>
      <label for=cek>Saya menyatakan bahwa data diatas sudah benar.</label>
    </div>
    <button class='btn btn-primary w-100' name=btn_submit_data_pasien value='$id_pasien'>Submit Data</button>
    <input type=hidden name=last_pemeriksaan value='$nama_pemeriksaan by $nama_user'>
    <input type=hiddena name=id_pemeriksaan value='$id_pemeriksaan'>
  </form>
";


?>
<script>
  function set_kelainan() {
    let r = [];
    $('.kelainan').each(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let key = rid[1];
      // console.log(aksi, key);
      if ($(this).text()) {
        r.push($(this).text());
      }
      console.log($('#catatan__' + key).val(), '|| #catatan__' + key);
      if ($('#catatan__' + key).val()) {
        r.push(
          $('#title__' + key).text() + ' > Note: ' +
          $('#catatan__' + key).val()
        );
      }
    });

    if (r.length) {
      console.log('berisi');
      $('#blok_catatanKESAN').slideDown();
      $('#catatan__KESAN').prop('required', true);
      $('#catatan__KESAN').val(r.join(', '));
      $('#blok_option_radio__KESAN').slideUp();
      $('#KESAN__kesan__abnor').prop('checked', true);
    } else {
      $('#KESAN__kesan__normal').prop('checked', true);
      $('#blok_option_radio__KESAN').slideDown();
      $('#blok_catatanKESAN').slideUp();
      $('#catatan__KESAN').val('');
      $('#catatan__KESAN').prop('false', true);
    }
  }

  $(function() {
    $(".catatan").focusout(function() {
      set_kelainan()
    });
    $(".opsi_rontgen").click(function() {
      let tid = $(this).prop('id');
      let val = $(this).text();
      let rid = tid.split('__');
      let part0 = rid[0] ?? null;
      let part1 = rid[1] ?? null;
      let part2 = rid[2] ?? null;
      let part3 = rid[3] ?? null;
      let part4 = rid[4] ?? null;
      let part5 = rid[5] ?? null;
      // console.log(tid, part0, part1, part2, part3, part4, part5);

      let status = part5; // normal | abnormal
      let ki_ka = part4;
      let kolom = part3;
      let KI_KA = part2;
      let KOLOM = part1;

      if (!part5) {
        status = part3;
        kolom = part2;
        // console.log(`status : ${status} | ki_ka : ${ki_ka} | kolom : ${kolom} |  val : ${val}  `);

      }
      if (status == 'abnor') {
        if (part5) {
          $('#kelainan_' + ki_ka + '__' + part1).text(
            $('#title__' + KOLOM + '__' + KI_KA).text() + ': ' + val
          );
        } else {
          $('#kelainan__' + part1).text(
            $('#title__' + KOLOM).text() + ': ' + val
          );
        }

      } else {
        if (part5) {
          $('#kelainan_' + ki_ka + '__' + part1).text('');
        } else {
          $('#kelainan__' + part1).text('');

        }
      }

      set_kelainan();
    });

  });
</script>