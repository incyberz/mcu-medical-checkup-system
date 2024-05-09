<style>
  .radio-toolbar-penyakit input[type="radio"]:checked+label {
    background-color: rgb(249, 255, 136);
    border-color: rgb(204, 159, 68);
    color: rgb(139, 0, 44);
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
</style>
<?php
$judul = 'Kuesioner Online';

$id_program = $_GET['id_program'] ?? die('id_program belum didefinisikan.');

$Saudara = $gender == 'l' ? 'Saudara' : 'Saudari';
$sub_judul = "<span class=blue>Yth. $Saudara $nama_user! <br><br>Agar proses pemeriksaan Medical Checkup Anda lebih cepat, sangat disarankan Anda menjawab kuesioner berikut dengan sejujur-jujurnya agar kami dapat menyimpulkan dan merekomendasikan tentang kesehatan Anda secara tepat.</span>";
$start = $_GET['start'] ?? '';
if ($start) $sub_judul = "Login as: <span class=darkblue>$nama_user</span>";
only(['pasien']);

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

      $s2 = "SELECT *,id as id_pertanyaan FROM tb_pertanyaan WHERE id_program=$id_program AND penyakit='$d[penyakit]'";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $isi = '';
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $id_pertanyaan = $d2['id_pertanyaan'];
        $isi .= "
          <div class='$d2[section] mb1'>$d2[pertanyaan]</div>

          <div class='radio-toolbar radio-toolbar-penyakit abu mb4 '>
            <div class='row'>
              <div class='col-6'>
                <input type='radio' name='pertanyaan-$id_pertanyaan' id='radio1-$id_pertanyaan' class='opsi_radio' required value='0'  >
                <label class='proper label-tidak' for='radio1-$id_pertanyaan'>Tidak</label>
              </div>
              <div class='col-6'>
                <input type='radio' name='pertanyaan-$id_pertanyaan' id='radio2-$id_pertanyaan' class='opsi_radio' required value='1' >
                <label class='proper label-ya' for='radio2-$id_pertanyaan'>Ya</label>
              </div>
            </div>
          </div>
        ";
      }

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
          <div class='f16 darkred proper mb2 mt4'>$i. riwayat penyakit $d[penyakit]</div>
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
