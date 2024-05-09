<style>
  .radio-toolbar-penyakit input[type="radio"]:checked+label {
    color: white;
    background-color: #55f;
    border-color: #44c;
  }

  .radio-toolbar-penyakit label {
    background-color: #eee;
    border: 2px solid #bbb;
    /* padding: 7px 10px;
    font-family: sans-serif, Arial;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    text-align: center; */
  }

  .radio-toolbar-penyakit label:hover {
    background-color: #dd8888;
    /* color: darkblue; */
  }

  .riwayat-penyakit {
    color: #55f;
  }

  .riwayat-pengobatan {
    color: #55c;
  }

  .riwayat-keluarga {
    color: #55a;
  }

  .blok-pertanyaan {
    border-bottom: solid 3px #ccc;
  }

  /* tmp */
  .flex-center {
    justify-content: center;
  }
</style>
<?php
only(['pasien']);
$judul = 'Kuesioner Online';
$care = "<img src='assets/img/icons/care.png' height=35px />";
$parent = "<img src='assets/img/icons/parent.png' height=35px />";

$id_program = $_GET['id_program'] ?? die('id_program belum didefinisikan.');

$Saudara = $gender == 'l' ? 'Saudara' : 'Saudari';
$sub_judul = "<span class=blue>Yth. $Saudara $nama_user! <br><br>Agar proses pemeriksaan Medical Checkup Anda lebih cepat, sangat disarankan Anda menjawab kuesioner berikut dengan sejujur-jujurnya agar kami dapat menyimpulkan dan merekomendasikan tentang kesehatan Anda secara tepat.</span>";
$start = $_GET['start'] ?? '';
if ($start) $sub_judul = "Login as: <span class=darkblue>$nama_user</span>";

if (!$start) {
  set_title($judul);
  set_h2($judul, $sub_judul);
  $arr = explode('?', $_SERVER['REQUEST_URI']);
  echo "<a class='btn btn-primary w-100' href='?$arr[1]&start=1'>Mulai Mengisi Kuesioner</a>";
} else {
  echo "
    <div style='position:fixed; bottom:0; left:0; background: white; padding: 15px; width: 100vw; z-index:999; border-top: solid 1px #ccc'>
      <div class='mb1 f14 abu'>Progress pengisian: 70%</div>
      <div class=progress>
        <div class=progress-bar role=progressbar aria-valuenow=70 aria-valuemin=0 aria-valuemax=100 style=width:70%  >
          <span class='sr-only'>70%</span>
        </div>
      </div>
    </div>
  ";

  $s = "SELECT 
  a.*
  FROM tb_penyakit a 
  WHERE id_klinik='$id_klinik' 
  ORDER BY a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  if (mysqli_num_rows($q)) {
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $penyakit = $d['penyakit'];

      $s2 = "SELECT *,id as id_pertanyaan FROM tb_pertanyaan WHERE id_program=$id_program AND penyakit='$penyakit'";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $isi = '';
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $id_pertanyaan = $d2['id_pertanyaan'];
        $section = $d2['section'];
        $section__penyakit = $section . "__$penyakit";
        $opsi = '';
        if ($section == 'riwayat-penyakit' || $section == 'riwayat-pengobatan') {
          $icon = $section == 'riwayat-pengobatan' ? $care : '';
          $opsi = "
            <div class='radio-toolbar radio-toolbar-penyakit abu mb4 '>
              <div class='row'>
                <div class='col-6'>
                  <input type='radio' name='pertanyaan-$id_pertanyaan' id='radio1-$section__penyakit' class='opsi_radio radio__$section' required value='0'  >
                  <label class='proper label-tidak' for='radio1-$section__penyakit'>Tidak</label>
                </div>
                <div class='col-6'>
                  <input type='radio' name='pertanyaan-$id_pertanyaan' id='radio2-$section__penyakit' class='opsi_radio radio__$section' required value='1' >
                  <label class='proper label-ya' for='radio2-$section__penyakit'>Ya</label>
                </div>
              </div>
            </div>
          ";
        } elseif ($section == 'riwayat-keluarga') {
          $icon = $parent;
          $opsi = "
            <div class='flexy mb2 left flex-center'>
              <div class=''>
                <div class='form-check form-switch'>
                  <input class='form-check-input' type='checkbox' id='ayah-$penyakit' name='ayah-$penyakit' value='ayah-$penyakit' value=1>
                  <label class='form-check-label proper' for='ayah-$penyakit'>ayah</label>
                </div>
              </div>
              <div class='ml4'>
                <div class='form-check form-switch'>
                  <input class='form-check-input' type='checkbox' id='ibu-$penyakit' name='ibu-$penyakit' value='ibu-$penyakit' value=1>
                  <label class='form-check-label proper' for='ibu-$penyakit'>ibu</label>
                </div>
              </div>
            </div>
          ";
        }
        $section__penyakit = $section . '__' . $penyakit;
        $isi .= "
          <div class='debuga red'>id='blok-$section-$penyakit' class='$section mb4 blok-pertanyaan tengah'</div>
          <div id='blok-$section-$penyakit' class='$section mb4 blok-pertanyaan tengah'>
            <div class='debuga red f10'>id='$section__penyakit' class='$section blok-pertanyaan'</div>
            $icon
            <div class='pertanyaan-$id_pertanyaan mb2 mt2'>$d2[pertanyaan]</div>
            $opsi
          </div>
        ";
      } // end while

      $ilustrasi = '';
      if ($d['image']) {
        $src = "$lokasi_ilustrasi/$d[image]";
        if (file_exists($src)) {
          $ilustrasi = "
            <div class='tengah mb2'>
              <img src='$src' class='img-thumbnail img-penyakit'>
            </div>
          ";
        }
      }

      $tr .= "
        <div class='gradasi-merah' style='margin: 0 -15px; padding: 60px 15px'>
          <div class='f16 darkred proper mb2 mt4 tengah'>$i. riwayat penyakit $penyakit</div>
          $ilustrasi
          $isi
        </div>
      ";
    }
  }



  $blok_penyakit = $tr ? "
    <style>.img-penyakit{max-width:300px}</style>
    $tr
  " : div_alert('danger', "Data penyakit tidak ditemukan.");
  echo "$blok_penyakit";


  echo "
    <div class='wadah mt2'>
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti quia quas vel provident accusamus excepturi odio vero harum id magnam, aut fugit minus sint doloribus at optio cupiditate officia voluptatibus.
    </div>
  ";
}






























?>
<script>
  $(function() {
    $('.radio__riwayat-penyakit').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      let val = $(this).val();
      console.log(aksi, id, val);
      if (parseInt(val)) { // Ya
        $('#blok-riwayat-perawatan-' + id).show();
        console.log('#blok-riwayat-perawatan-' + id, 'show');

      } else { // Tidak
        $('#blok-riwayat-perawatan-' + id).hide();
        console.log('#blok-riwayat-perawatan-' + id, 'hide');

      }
    });
  });
</script>