<?php
// set_h2('Tambah Pasien');
$bm = '<b class=red>*</b>';

# ============================================================
# PROCESSORS 
# ============================================================
if (isset($_POST['btn_tambah_pasien'])) {
  unset($_POST['btn_tambah_pasien']);
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  $koloms = '__';
  $isis = '__';


  foreach ($_POST as $key => $value) {
    if (!$value) continue;
    $koloms .= ",$key";
    $isis .= ",'$value'";
  }
  $koloms = str_replace('__,', '', $koloms);
  $isis = str_replace('__,', '', $isis);

  $s = "INSERT INTO tb_pasien ($koloms,id_klinik) VALUES ($isis,$id_klinik) ";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl('?pendaftaran');
  exit;
}


















require_once 'include/mcu_functions.php';
require_once 'include/radio_toolbar_functions.php';
// require_once 'include/radio_jenis_pasien.php';

$arr_gender = [
  'l' => [
    'id_detail' => 'gender',
    'option_class' => 'gender',
    'option_value' => 'l',
    'caption' => 'Laki-laki',
  ],
  'p' => [
    'id_detail' => 'gender',
    'option_class' => 'gender',
    'option_value' => 'p',
    'caption' => 'Perempuan',
  ],
];
$radio_gender = radio_toolbar($arr_gender, false);

$arr_jenis = [
  'bpjs' => [
    'id_detail' => 'jenis',
    'option_class' => 'jenis_pasien',
    'option_value' => 'bpj',
    'caption' => 'BPJS',
  ],
  'individu' => [
    'id_detail' => 'jenis',
    'option_class' => 'jenis_pasien',
    'option_value' => 'idv',
    'caption' => 'Individu',
  ],
  // 'corporate' => [
  //   'id_detail' => 'jenis_pasien',
  //   'option_class' => 'jenis_pasien',
  //   'value' => 1,
  //   'caption' => 'Corporate',
  // ],
];
$radio_jenis = radio_toolbar($arr_jenis, false);

$input_tgl = '';
$input_bln = '';
$input_thn = '';
$year = date('Y');
for ($i = 1; $i <= 31; $i++) $input_tgl .= "<option>$i</option>";
for ($i = $year; $i >= $year - 60; $i--) $input_thn .= "<option>$i</option>";
foreach ($arr_nama_bulan as $key => $value) $input_bln .= "<option value='" . ($key + 1) . "'>$value</option>";
$input_tgl = "<select class='form-control input_tanggal_lahir' id=input_tgl>$input_tgl</select>";
$input_bln = "<select class='form-control input_tanggal_lahir' id=input_bln>$input_bln</select>";
$input_thn = "<select class='form-control input_tanggal_lahir' id=input_thn>$input_thn</select>";


$input_tanggal_lahir = "
  <div class=flexy>
    <div>$input_tgl</div>
    <div>$input_bln</div>
    <div>$input_thn</div>
    <div class='pt2 f14 abu'>usia <span id=usia>0</span> tahun</div>
    <div><input type=hidden id=tanggal_lahir name=tanggal_lahir class='bg-red'></div>
  </div>
  
";

$input_alamat = "
  <div class=flexy>
    <div class='kanan abu f14 miring pt2' style='min-width:100px'>Kabupaten $bm</div>
    <div>
      <input required class='form-control' name=kabupaten placeholder='Kabupaten...' autocomplete=off>
      <div class=hideit id=list_kabupaten>
        <ul>
          <li>asd</li>
          <li>asd</li>
          <li>asd</li>
        </ul>
      </div>
    </div>
  </div>
  <div class=flexy>
    <div class='kanan abu f14 miring pt2' style='min-width:100px'>Kecamatan $bm</div>
    <div>
      <input required class='form-control' name=kecamatan placeholder='Kecamatan...'>
      <div id=list_kabupaten></div>
    </div>
  </div>
  <div style='display:grid; grid-template-columns: 100px auto; gap:15px'>
    <div class='kanan abu f14 miring pt2' style='min-width:100px'>Desa, Rw, RT $bm</div>
    <div>
      <textarea required name=alamat id=alamat class='form-control' style='width:100%' placeholder='Desa, Blok, RT, RW...'></textarea>
    </div>
  </div>
";

$input_image = "
<input type=file capture=camera required name=image id=image class='form-control' >
";
?>
<!-- <script src='https://unpkg.com/webcam-easy/dist/webcam-easy.min.js'></script>
<video id=webCam autoplay playsinline width=800 height=600></video>
<canvas id=canvas></canvas>
<a download onClick='takeAPicture()'>SNAP</a>
<script>
  const webCamElement = document.getElementById('webCam');
  const canvasElement = document.getElementById('canvas');
  const webcam = new Webcam(webCamElement, 'capture', canvasElement);
  webcam.start();

  function takeAPicture() {
    let picture = webcam.snap();
    document.querySelector('a').href = picture;
  }
</script> -->

<?php


$arr_input = [
  'nama' => [
    'kolom' => 'Nama Pasien',
    'minlength' => '3',
    'maxlength' => '30',
  ],
  'gender' => $radio_gender,
  'tempat_lahir' => [
    'kolom' => 'Tempat Lahir',
    'minlength' => '3',
    'maxlength' => '30',
    'required' => ''
  ],
  'tanggal_lahir' => $input_tanggal_lahir,
  'jenis' => $radio_jenis,
  'no_bpjs' => [
    'kolom' => 'No. BPJS',
    'minlength' => '13',
    'maxlength' => '16',
  ],
  'no_ktp' => [
    'kolom' => 'No. KTP',
    'minlength' => '16',
    'maxlength' => '16',
    'required' => '',
  ],
  'alamat' => $input_alamat,
  // 'image' => $input_image,

];
$html_inputs = '';
foreach ($arr_input as $key => $arr) {
  $required = $arr['required'] ?? 'required';
  $bm_input = $required == 'required' ? $bm : '';

  if (is_array($arr)) {
    $kolom = $arr['kolom'] ?? key2kolom($key);
    $id = $arr['id'] ?? $key;
    $readonly = $arr['readonly'] ?? '';
    $checked = $arr['checked'] ?? '';
    $class = $arr['class'] ?? '';
    $type = $arr['type'] ?? 'text';
    $placeholder = $arr['placeholder'] ?? "$kolom...";
    $input = "
      <input 
        name='$key'
        $required
        $readonly
        $checked
        type='$type'
        id='$id'
        minlength='$arr[minlength]'
        maxlength='$arr[maxlength]'
        placeholder='$placeholder'
        class='form-control $class'
      />
    ";
  } else {
    $input = $arr;
    $kolom = key2kolom($key);
  }

  $html_inputs .= "<tr class=tr id=tr__$key><td><div class='pt2 f14 miring abu kanan'>$kolom $bm_input</div></td><td>$input</td></tr>";
}

echo "
  <div class='wadah gradasi-hijau'>
    <form method=post class=wadah>
      <table class=table>
        $html_inputs
      </table>
      <button class='btn btn-primary w-100' name=btn_tambah_pasien id=btn_tambah_pasien disabled>Tambah Pasien</button>
    </form>
  </div>
";




























// ZZZ NOTED
// ANDA SEDANG HAIDH


?>
<script>
  $(function() {
    $('.input_tanggal_lahir').change(function() {
      $('.input_tanggal_lahir').addClass('gradasi-merah');
      $("#btn_tambah_pasien").prop('disabled', true);
      let str =
        $('#input_thn').val() + '-' +
        $('#input_bln').val() + '-' +
        $('#input_tgl').val();

      $('#tanggal_lahir').val(str);
      let d = new Date(str);

      if (Object.prototype.toString.call(d) === "[object Date]") {
        // it is a date
        if (isNaN(d)) { // d.getTime() or d.valueOf() will also work
          // date object is not valid
          console.log('ZZZ');
        } else {
          // date object is valid
          let day = d.getDate();
          let month = d.getMonth();
          console.log('OK', day, month);
          if (day == parseInt($('#input_tgl').val())) {
            // ====================================================
            // DATE FORMAT OK
            // ====================================================

            // usia
            let t1 = Date.parse(new Date());
            let t0 = Date.parse(d);
            let usia = parseInt((t1 - t0) / (1000 * 60 * 60 * 24 * 365));
            $('#usia').text(usia);
            if (usia > 0) {
              $('.input_tanggal_lahir').removeClass('gradasi-merah');
              $("#btn_tambah_pasien").prop('disabled', false);

            }
          }
        }
      } else {
        // not a date object
        console.log('NOT date');
      }
    });

    $('.opsi_radio').change(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      if (aksi == 'jenis') {
        let jenis = rid[1];
        // console.log(aksi, jenis, 'ZZZ');
        if (jenis == 'bpj') {
          $('#tr__no_bpjs').show();
          $('#no_bpjs').prop('required', true);
        } else {
          $('#tr__no_bpjs').hide();
          $('#no_bpjs').prop('required', false);
          $('#no_bpjs').val('');
        }

      }
    });
  })
</script>