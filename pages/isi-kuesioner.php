<?php
only(['pasien']);
set_title('Isi Kuesioner');
include 'isi-kuesioner-styles.php';

















# ============================================================
# PROCESSORS
# ============================================================
$id_program = $_GET['id_program'] ?? die('id_program belum didefinisikan.');
$kolom = $_GET['kolom'] ?? 'riwayat';
if (isset($_POST['btn_submit_jawaban'])) {
  // field target
  if ($kolom == 'riwayat') {
    $field_target = 'riwayat_penyakit';
    $new_status = 3;
  } elseif ($kolom == 'gejala') {
    $field_target = 'gejala_penyakit';
    $new_status = 4;
  } elseif ($kolom == 'gaya_hidup') {
    $field_target = $kolom;
    $new_status = 5;
  } elseif ($kolom == 'keluhan') {
    $field_target = $kolom;
    $new_status = 6;
  } else {
    die(div_alert('danger', "Field Target belum didefinisikan untuk kolom $kolom."));
  }

  // status lama
  $s = "SELECT status FROM tb_pasien WHERE id=$id_user";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $status_lama = $d['status'];
  if ($status_lama > $new_status) $new_status = $status_lama;

  // update tb_pasien
  $s = "UPDATE tb_pasien SET 
  $field_target = '$_POST[jawaban]', 
  tanggal_mengisi_$field_target = CURRENT_TIMESTAMP,
  status = $new_status -- Update Kuesioner 
  -- Riwayat Penyakit (3)   
  -- Gejala Penyakit (4)   
  WHERE id = '$id_user'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // echo $s;
  echo div_alert('success', "Jawaban kolom $kolom berhasil disimpan.");
  jsurl('?pasien_home', 1000);
  exit;
}

















$judul = 'Kuesioner ' . ucwords(strtolower($kolom)) . ' Penyakit';
if ($kolom == 'gaya_hidup') $judul = 'Kuesioner Gaya Hidup';
$care = "<img src='assets/img/icon/care.png' height=35px />";
$parent = "<img src='assets/img/icon/parent.png' height=35px />";


$Saudara = $gender == 'l' ? 'Saudara' : 'Saudari';

$kalimat_pembuka = '';
if ($kolom == 'riwayat') {
  $kalimat_pembuka = "Agar proses pemeriksaan Medical Checkup Anda lebih cepat, sangat disarankan Anda menjawab kuesioner berikut dengan sejujur-jujurnya agar kami dapat menyimpulkan dan merekomendasikan tentang kesehatan Anda secara tepat.";
} else if ($kolom == 'gejala') {
  $kalimat_pembuka = "Silahkan Anda lanjutkan ke pengisian Kuesioner Gejala Penyakit agar Dokter dapat dengan mudah menganalisis kesehatan Anda";
} else if ($kolom == 'gaya_hidup') {
  $kalimat_pembuka = "Silahkan Anda jelaskan keseharian Anda dengan memilih opsi-opsi berikut, agar Dokter dapat dengan mudah menganalisis kesehatan Anda";
} else {
  die(div_alert('danger', "kalimat_pembuka untuk kolom $kolom belum didefinisikan."));
}

$sub_judul = "<span class=blue>Yth. $Saudara $nama_user! <br><br>$kalimat_pembuka</span>";
$start = $_GET['start'] ?? '';
if ($start) $sub_judul = "Login as: <span class=darkblue>$nama_user</span>";

if (!$start) {
  set_title($judul);
  set_h2($judul, $sub_judul);
  $arr = explode('?', $_SERVER['REQUEST_URI']);

  if ($kolom == 'riwayat') {
    $link_mulai = "<a class='btn btn-primary w-100' href='?$arr[1]&start=1'>Mulai Mengisi Kuesioner</a>";
  } elseif ($kolom == 'gejala') {
    $link_mulai = "<a class='btn btn-primary w-100' href='?$arr[1]&start=1'>Mulai Isi Gejala</a>";
  } elseif ($kolom == 'gaya_hidup') {
    $link_mulai = '';
    include 'isi-kuesioner-gaya-hidup.php';
  } else {
    die(div_alert('danger', "Kuesioner $kolom belum dibuat"));
  }

  echo $link_mulai;
} else {

  # ============================================================
  # MAIN SELECT PENYAKIT || START RENDERING KUESIONER
  # ============================================================
  $tb = $kolom == 'riwayat' ? 'penyakit' : 'gejala';
  $s = "SELECT 
  a.*
  FROM tb_$tb a 
  WHERE id_klinik='$id_klinik' 
  ORDER BY a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jumlah_kuesioner = mysqli_num_rows($q);
  $blok_kuesioner = '';
  if (mysqli_num_rows($q)) {
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $kode = $d['penyakit'] ?? $d['gejala'];

      $id_program = 1;
      // $s2 = "SELECT *,id as id_pertanyaan FROM tb_pertanyaan WHERE id_program=$id_program AND (penyakit='$kode' OR gejala='$kode') ";
      $s2 = "SELECT *,id as id_pertanyaan FROM tb_pertanyaan WHERE id_program=$id_program AND (penyakit='$kode' OR gejala='$kode') ";
      // echo $s2;
      // exit;
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $isi = '';
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $id_pertanyaan = $d2['id_pertanyaan'];
        $section = $d2['section'];
        $section__kode = $section . "__$kode";
        if ($d2['untuk'] == 'riwayat') {
          $opsi = '';
          if ($section == 'riwayat-penyakit' || $section == 'riwayat-pengobatan') {
            $icon = $section == 'riwayat-pengobatan' ? $care : '';
            $opsi = "
              <div class='radio-toolbar radio-toolbar-penyakit abu mb4'>
                <div class='row'>
                  <div class='col-6'>
                    <input type='radio' name='radio-$section__kode' id='radio1-$section__kode' class='opsi_radio radio__$section' required value='0'  >
                    <label class='proper label-tidak' for='radio1-$section__kode'>Tidak</label>
                  </div>
                  <div class='col-6'>
                    <input type='radio' name='radio-$section__kode' id='radio2-$section__kode' class='opsi_radio radio__$section' value='1' >
                    <label class='proper label-ya' for='radio2-$section__kode'>Ya</label>
                  </div>
                </div>
              </div>
            ";
          } elseif ($section == 'riwayat-keluarga') {
            $icon = $parent;
            $opsi = "
              <div class='flexy mb2 left flex-center hideit'>
                <div class=''>
                  <div class='form-check form-switch'>
                    <input class='form-check-input checkbox-parent' type='checkbox' id='ayah--$kode' name='checkbox-ayah--$kode'>
                    <label class='form-check-label proper pointer' for='ayah--$kode'>ayah</label>
                  </div>
                </div>
                <div class='ml4'>
                  <div class='form-check form-switch'>
                    <input class='form-check-input checkbox-parent' type='checkbox' id='ibu--$kode' name='checkbox-ibu--$kode'>
                    <label class='form-check-label proper pointer' for='ibu--$kode'>ibu</label>
                  </div>
                </div>
              </div>
            ";
          }
          $section__kode = $section . '__' . $kode;
          $hideit = $section != 'riwayat-penyakit' ? 'hideit' : '';
          $isi .= "
            <div id='blok-$section-$kode' class='$section mb4 blok-pertanyaan tengah $hideit'>
              $icon
              <div class='pertanyaan-$id_pertanyaan mb2 mt2'>$d2[pertanyaan]</div>
              $opsi
            </div>
          ";
        } else if ($d2['untuk'] == 'gejala') { // untuk gejala
          # ============================================================
          # OPSI GEJALA PENYAKIT 
          # ============================================================
          $icon = $img_help;
          $opsi = "
            <div class='radio-toolbar radio-toolbar-penyakit abu mb4'>
              <div class='row'>
                <div class='col-6'>
                  <input type='radio' name='radio-gejala-$kode' id='radio1-gejala__$kode' class='opsi_radio radio__gejala' required value='0'  >
                  <label class='proper label-tidak' for='radio1-gejala__$kode'>Tidak</label>
                </div>
                <div class='col-6'>
                  <input type='radio' name='radio-gejala-$kode' id='radio2-gejala__$kode' class='opsi_radio radio__gejala' value='1' >
                  <label class='proper label-ya' for='radio2-gejala__$kode'>Ya</label>
                </div>
              </div>
            </div>
          ";

          $isi .= "
            <div id='blok-gejala-$kode' class='$section mb4 blok-pertanyaan tengah'>
              $icon
              <div class='pertanyaan pertanyaan-$id_pertanyaan mb2 mt2'>$d2[pertanyaan]</div>
              $opsi
            </div>
          ";
        }
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

      # ============================================================
      # FINAL TR
      # ============================================================
      $kode_show = $kolom == 'riwayat' ? "riwayat penyakit $kode" : $kode;
      $blok_kuesioner .= "
        <div id='blok-$kolom-$kode' class='blok-$kolom gradasi-merah' style='margin: 0 -15px; padding: 40px 15px 60px 15px;'>
          <div class='f16 darkblue proper mb2 mt4 tengah'>
            <div class='lightabu bold f26'>$i</div> 
            $kode_show
          </div>
          $ilustrasi
          $isi
        </div>
      ";
    } // END WHILE LOOP
  } // END NUM ROWS LOOP



  # ============================================================
  # PROGRESS PENGISIAN DAN SUBMIT
  # ============================================================
  $progres_pengisian = "
    <div style='position:fixed; bottom:0; left:0; background: white; padding: 5px 15px 15px 15px; width: 100vw; z-index:999; border-top: solid 1px #ccc'>
      <div class=container>
        <div class='mb1 f14 abu tengah'>Progress pengisian: <span id=persen_terjawab class='f20'>0</span>% (<span id=jumlah_terjawab>0</span> <span class=f12>dari <span id=jumlah_kuesioner>$jumlah_kuesioner</span> terjawab</span>)</div>
        <div class=progress>
          <div id=progres class=progress-bar role=progressbar aria-valuenow=0 aria-valuemin=0 aria-valuemax=100 style='width:0%;'></div>
        </div>
        <button class='hideit btn btn-primary w-100 mt2' name=btn_submit_jawaban id=btn_submit_jawaban >Submit Jawaban</button>
      </div>
    </div>
  ";


  # ============================================================
  # FINAL OUTPUT
  # ============================================================
  if ($blok_kuesioner) {
    echo "
      $blok_kuesioner
      <form method='POST'>
        <input type='hidden' name='kolom' value='$kolom'>
        <textarea name='jawaban' id=jawaban class='hideit form-control bg-red' rows=4 style='position:fixed;top:0; z-index:999'></textarea>
        $progres_pengisian
      </form>
    ";
  } else {
    echo div_alert('danger', "Belum ada data untuk kuesioner.");
  }
}






























?>
<script>
  function progres() {
    let jumlah_kuesioner = $('#jumlah_kuesioner').text();
    let belum_terjawab = $('.gradasi-merah').length;
    let jumlah_terjawab = jumlah_kuesioner - belum_terjawab;
    let persen_terjawab = Math.round((jumlah_terjawab / jumlah_kuesioner) * 100);
    $('#persen_terjawab').text(persen_terjawab);
    $('#progres').prop('style', 'width:' + persen_terjawab + '%;');
    if (persen_terjawab == 100) {
      $('#btn_submit_jawaban').slideDown();
    } else {
      $('#btn_submit_jawaban').slideUp();
    }
    $('#jumlah_terjawab').text(jumlah_terjawab);
  }

  function set_jawaban(jawaban, add = true) {
    if (jawaban) {
      let new_jawaban = $('#jawaban').val();
      let str = jawaban + ',';
      if (add) {
        new_jawaban += str;
        console.log('add new jawaban', new_jawaban);
      } else {
        new_jawaban = new_jawaban.replace(str, '')
        console.log('remove new jawaban', new_jawaban);
      }
      $('#jawaban').val(new_jawaban);

    }

  }

  $(function() {
    $('.checkbox-parent').click(function() {
      let id = $(this).prop('id');
      let checked = $(this).prop('checked');
      console.log(id, checked)
      // let rid = id.split('-');
      // let penyakit = rid[1];

      set_jawaban(id, checked);

    });

    $('.radio__riwayat-penyakit').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let penyakit = rid[1];
      let val = parseInt($(this).val());
      console.log(aksi, penyakit, val);
      if (val) { // Ya
        $('#blok-riwayat-pengobatan-' + penyakit).slideDown();
        // $('#blok-riwayat-keluarga-' + penyakit).slideUp();
        console.log('#blok-riwayat-pengobatan-' + penyakit, 'show');

      } else { // Tidak
        $('#blok-riwayat-pengobatan-' + penyakit).slideUp();
        $('#blok-riwayat-keluarga-' + penyakit).slideDown();
        console.log('#blok-riwayat-pengobatan-' + penyakit, 'hide');

        // clear style gradasi-merah
        $('#blok-riwayat-' + penyakit).removeClass('gradasi-merah');
        console.log('remove gradasi-merah | say TIDAK');

      }

      set_jawaban('riwayat--' + penyakit, val);
      progres();

    });

    $('.radio__riwayat-pengobatan').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let penyakit = rid[1];
      let val = parseInt($(this).val());
      console.log(aksi, penyakit, val);
      $('#blok-riwayat-keluarga-' + penyakit).slideDown();

      // clear style gradasi-merah
      $('#blok-riwayat-' + penyakit).removeClass('gradasi-merah');
      console.log('remove gradasi-merah | say YA/TIDAK pada pengobatan');

      set_jawaban('pengobatan--' + penyakit, val);
      progres();

    });


    $('.radio__gejala').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let gejala = rid[1];
      let val = parseInt($(this).val());
      console.log(aksi, gejala, val);

      $('#blok-gejala-' + gejala).removeClass('gradasi-merah');
      console.log('remove gradasi-merah', '#blok-gejala-' + gejala);

      set_jawaban(gejala, val);
      progres();

    });
  });
</script>