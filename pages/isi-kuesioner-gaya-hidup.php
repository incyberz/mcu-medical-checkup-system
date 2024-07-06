<style>
  .blok-gaya-hidup {
    padding: 60px 15px !important;
    /* background: red; */
  }
</style>
<?php


$img_tidur = ilustrasi('tidur');
$img_merokok = ilustrasi('merokok');
$img_stress = ilustrasi('stress');
$img_olahraga = ilustrasi('olahraga');
$img_tato = ilustrasi('tato');
$img_tindik = ilustrasi('tindik');
$img_obat = ilustrasi('obat');
$img_minum = ilustrasi('minum');























# ============================================================
# PROCESSORS
# ============================================================
// at isi-kuesioner.php















# ============================================================
# MAIN ARRAY DATA
# ============================================================
$gayas = [
  'lama_waktu_tidur' => [
    'Kebiasaan Tidur',
    $img_tidur
  ],
  'kebiasaan_merokok' => [
    'Kebiasaan Merokok',
    $img_merokok
  ],
  'kebiasaan_merokok_count' => [
    '',
    ''
  ],
  'stress_kerja' => [
    'Stress Kerja',
    $img_stress
  ],
  'kebiasaan_olahraga' => [
    'Kebiasaan Olahraga',
    $img_olahraga
  ],
  'tato' => [
    'Tato',
    $img_tato
  ],
  'tato_di_bagian' => [
    '',
    ''
  ],
  'tindik' => [
    'Tindik',
    $img_tindik
  ],
  'tindik_di_bagian' => [
    '',
    ''
  ],
  'kebiasaan_ngobat' => [
    'Kebiasaan Ngobat',
    $img_obat
  ],
  'tahun_pakai_obat' => [
    '',
    ''
  ],
  'kebiasaan_nginum' => [
    'Kebiasaan Minuman Keras',
    $img_minum
  ],
  'tahun_minum' => [
    '',
    ''
  ],
  'jumlah_gelas' => [
    '',
    ''
  ],

];

$opsi['lama_waktu_tidur'] = [
  3 => 'Saya tidur 3 jam per hari',
  4 => 'Saya tidur 4 jam per hari',
  5 => 'Saya tidur 5 jam per hari',
  6 => 'Saya tidur 6 jam per hari',
  7 => 'Saya tidur 7 jam per hari',
  8 => 'Saya tidur 8 jam per hari',
  9 => 'Saya tidur 9 jam per hari',
  10 => 'Saya tidur 10 jam per hari',
  11 => 'Saya tidur 11 jam per hari',
  12 => 'Saya tidur 12 jam per hari',
];

$opsi['kebiasaan_merokok'] = [
  -2 => 'Saya Tidak Pernah Merokok',
  1 => 'Saya Sudah Berhenti Merokok',
  2 => 'Saya Perokok Pasif (Pasangan/Rekan Perokok)',
  3 => 'Saya merokok 1 s.d 3 batang per hari',
  4 => 'Saya merokok 4 s.d 6 batang per hari',
  5 => 'Saya merokok 7 s.d 12 batang per hari',
  6 => 'Saya merokok sebungkus per hari',
  7 => 'Saya merokok sebungkus lebih per hari',
];

$opsi['kebiasaan_merokok_count'] = [
  1 => 'Berhenti merokok sekitar seminggu yang lalu',
  2 => 'Berhenti merokok sekitar sebulan yang lalu',
  3 => 'Berhenti merokok sekitar enam bulan yang lalu',
  4 => 'Berhenti merokok sekitar setahun yang lalu',
  5 => 'Berhenti merokok sekitar 5 tahun yang lalu',
  6 => 'Berhenti merokok sekitar 10 tahun yang lalu',
];

// $opsi['kebiasaan_merokok_count'] = [
//   1 => '1 s.d 3 batang per hari',
//   4 => '4 s.d 6 batang per hari',
//   7 => '7 s.d 12 batang per hari',
//   13 => 'Sebungkus per hari',
//   25 => 'Sebungkus lebih per hari',
// ];

$opsi['stress_kerja'] = [
  1 => 'Beban Kerja tidak membuat saya stress',
  2 => 'Beban Kerja terkadang membuat saya stress',
  3 => 'Beban Kerja sering membuat saya stress',
];

$opsi['kebiasaan_olahraga'] = [
  -1 => 'Saya tidak pernah berolahraga',
  1 => 'Saya jarang berolahraga',
  2 => 'Saya olahraga 1 - 2 kali per minggu',
  3 => 'Saya olahraga hampir setiap hari',
  4 => 'Saya adalah atlit/olahragawan',
];

$opsi['tato'] = [
  -1 => 'Saya tidak punya tato',
  1 => 'Dulu saya punya tato',
  2 => 'Saya punya satu tato',
  3 => 'Saya punya banyak tato',
];

$opsi['tindik'] = [
  -1 => 'Saya tidak pernah di-tindik',
  1 => 'Dulu saya pernah di-tindik',
  2 => 'Saya punya satu tindik',
  3 => 'Saya punya banyak tindik',
];

$opsi['kebiasaan_ngobat'] = [
  -1 => 'Saya Tidak Pernah Pakai Obat Terlarang',
  1 => 'Di masa lalu pernah pakai obat',
  2 => 'Saya Kadang-kadang Pakai Obat',
  3 => 'Saya Sering Pakai Obat',
];

$opsi['kebiasaan_ngobat_count'] = [
  1 => 'Berhenti ngobat sekitar seminggu yang lalu',
  2 => 'Berhenti ngobat sekitar sebulan yang lalu',
  3 => 'Berhenti ngobat sekitar enam bulan yang lalu',
  4 => 'Berhenti ngobat sekitar setahun yang lalu',
  5 => 'Berhenti ngobat sekitar 5 tahun yang lalu',
  6 => 'Berhenti ngobat sekitar 10 tahun yang lalu',
];

$opsi['kebiasaan_nginum'] = [
  -1 => 'Saya Tidak Pernah Minum-minuman Keras',
  1 => 'Dahulu Saya Pernah Minum',
  2 => 'Saya minum Kurang dari 1 gelas per minggu',
  3 => 'Saya minum 1 s.d 2 gelas per minggu',
  4 => 'Saya minum 3 s.d 5 gelas per minggu',
  5 => 'Saya minum sebotol per minggu',
  6 => 'Saya minum sebotol lebih per minggu',
];

$opsi['kebiasaan_nginum_count'] = [
  1 => 'Berhenti nginum sekitar seminggu yang lalu',
  2 => 'Berhenti nginum sekitar sebulan yang lalu',
  3 => 'Berhenti nginum sekitar enam bulan yang lalu',
  4 => 'Berhenti nginum sekitar setahun yang lalu',
  5 => 'Berhenti nginum sekitar 5 tahun yang lalu',
  6 => 'Berhenti nginum sekitar 10 tahun yang lalu',
];



























# ============================================================
# LOOPING
# ============================================================
$i = 0;
$divs = '';
$jumlah_kuesioner = 0;
foreach ($gayas as $key => $gaya) {
  $kebiasaan = $gaya[0];
  if ($kebiasaan) {
    $jumlah_kuesioner++;
    $i++;
    $opsies = '';
    foreach ($opsi[$key] as $k => $v) {
      $opsies .= "<option value='$k'>$v</option>";
    }

    // exception for multiple input
    $sub_select = '';
    # ============================================================
    # KEBIASAAN MEROKOK || NGOBAT || NGINUM
    # ============================================================
    if (
      $key == 'kebiasaan_merokok'
      || $key == 'kebiasaan_ngobat'
      || $key == 'kebiasaan_nginum'
    ) {
      $sub_key = $key . '_count';
      $sub_opsies = '';
      foreach ($opsi[$sub_key] as $sub_value => $sub_pilihan) {
        $sub_opsies .= "<option value='$sub_value'>$sub_pilihan</option>";
      }

      $operand_and_val = $key == 'kebiasaan_merokok' ? '== 1' : '';
      $operand_and_val = $key == 'kebiasaan_ngobat' ? '== 1' : $operand_and_val;
      $operand_and_val = $key == 'kebiasaan_nginum' ? '== 1' : $operand_and_val;

      $sub_select = "
        <div class='hideit bg-yellow' id=tmp-$sub_key></div>
        <select class='form-control mb2 tengah select-gaya-hidup' name=$sub_key id=$sub_key>
          <option value='0'>-- Pilih --</option>
          $sub_opsies
        </select>

        <script>
          $(function() {
            $('#$key').change(function() {
              if ($(this).val() $operand_and_val) {
                $('#$sub_key').fadeIn();
                $('#$sub_key').val('0');
                $('#blok-gaya-hidup__$key').addClass('gradasi-merah');

              } else {
                $('#$sub_key').fadeOut();
                if($(this).val() == '0'){
                  $('#blok-gaya-hidup__$key').addClass('gradasi-merah');
                } else {
                  $('#blok-gaya-hidup__$key').removeClass('gradasi-merah');
                }
              }
            });

            // auto change when form load
            $('#$key').change();

            // sub key changed
            $('#$sub_key').change(function() {
              if($(this).val()=='0'){
                $('#blok-gaya-hidup__$key').addClass('gradasi-merah');
              } else {
                $('#blok-gaya-hidup__$key').removeClass('gradasi-merah');
              }
            });

          })
        </script>

      ";
    } else { // tanpa sub-input
      // sub select digantikan dengan script
      $sub_select = "
        <script>
          $(function() {
            $('#$key').change(function() {
              if ($(this).val() == '0') {
                $('#blok-gaya-hidup__$key').addClass('gradasi-merah');
              } else {
                $('#blok-gaya-hidup__$key').removeClass('gradasi-merah');
              }
            });
          });
        </script>

      ";
    }

    # ============================================================
    # FINAL OUTPUT PER BLOCK GAYA HIDUP
    # ============================================================
    $divs .= "
      <div class='wadah gradasi-hijau gradasi-merah blok-gaya-hidup' id='blok-gaya-hidup__$key'>
        <div class='tengah mb4'>
          <div class='lightabu f20 tebal'>$i</div>
          <div class='darkblue mb2'>$gaya[0]</div>
          <div>$gaya[1]</div>
        </div>
        <div class='hideit bg-yellow' id=tmp-$key></div>
        <select class='form-control mb2 tengah select-gaya-hidup' name=$key id=$key>
          <option value='0'>-- Pilih --</option>
          $opsies
        </select>
        $sub_select
      </div>  
    ";
  } // end if ($kebiasaan)
} // end foreach $gayas


# ============================================================
# PROGRESS PENGISIAN DAN SUBMIT
# ============================================================
$form_progres = "
  <form method='POST'>
    <input type='hidden' name='kolom' value='$kolom'>
    <textarea name='jawaban' id=jawaban class='hideita form-control bg-red' rows=4 style='position:fixed;top:0; z-index:999'></textarea>

    <div style='position:fixed; bottom:0; left:0; background: white; padding: 5px 15px 15px 15px; width: 100vw; z-index:999; border-top: solid 1px #ccc'>
      <div class=container>
        <div class='mb1 f14 abu tengah'>Progress pengisian: <span id=persen_terjawab class='f20'>0</span>% (<span id=jumlah_terjawab>0</span> <span class=f12>dari <span id=jumlah_kuesioner>$jumlah_kuesioner</span> terjawab</span>)</div>
        <div class=progress>
          <div id=progres class=progress-bar role=progressbar aria-valuenow=0 aria-valuemin=0 aria-valuemax=100 style='width:0%;'></div>
        </div>
        <button class='hideit btn btn-primary w-100 mt2' name=btn_submit_jawaban id=btn_submit_jawaban >Submit Jawaban</button>
      </div>
    </div>

  </form>


";

echo "
  $divs
  $form_progres
";




























?>
<script>
  // function progres_gaya() {
  //   let jumlah_kuesioner = $('#jumlah_kuesioner').text();
  //   let belum_terjawab = $('.gradasi-merah').length;
  //   let jumlah_terjawab = jumlah_kuesioner - belum_terjawab;
  //   let persen_terjawab = Math.round((jumlah_terjawab / jumlah_kuesioner) * 100);
  //   $('#persen_terjawab').text(persen_terjawab);
  //   $('#progres').prop('style', 'width:' + persen_terjawab + '%;');
  //   if (persen_terjawab == 100) {
  //     $('#btn_submit_jawaban').slideDown();
  //   } else {
  //     $('#btn_submit_jawaban').slideUp();
  //   }
  //   $('#jumlah_terjawab').text(jumlah_terjawab);
  // }

  // function set_jawaban_gaya(jawaban, add = true) {
  //   let str = jawaban + ',';
  //   console.log('DEBUG str:', str);
  //   if (add) {
  //     jawaban += str;
  //     console.log('add new jawaban', jawaban);
  //   } else {
  //     jawaban = jawaban.replace(str, '')
  //     console.log('remove new jawaban', jawaban);
  //   }
  //   $('#jawaban').val(jawaban);

  // }

  $(function() {

    $('.select-gaya-hidup').change(function() {
      let id = $(this).prop('id');
      let val = $(this).val();
      let text = $('#' + id + ' option:selected').text();
      console.log(id, val, text)

      // remove jawaban sebelumnya
      let tmp_text = $('#tmp-' + id).text();
      set_jawaban(tmp_text, false);
      if (val != '0') {
        set_jawaban(text);
        $('#tmp-' + id).text(text);
      }
      progres();

    });


  });
</script>