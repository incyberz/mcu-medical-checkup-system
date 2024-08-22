<?php
require_once 'include/resize_img.php';
require_once 'include/editable_js_for_td.php';
$lokasi_img_perusahaan = 'assets/img/perusahaan';
$lokasi_perusahaan = $lokasi_img_perusahaan;
$div_slide = '';
$lebar = 150; // lebar gambar logo berjalan
















# ===============================================================
# PROCESSORS
# ===============================================================

if (isset($_POST['btn_add_perusahaan'])) {
  echolog('processing image upload');
  $new_image = "perusahaan-$_POST[new_nomor]-$detik.png";

  $telepon_or_null = $_POST['telepon'] ? "'$_POST[telepon]'" : 'NULL';
  $alamat_or_null = $_POST['alamat'] ? "'$_POST[alamat]'" : 'NULL';
  $jumlah_peserta_or_null = $_POST['jumlah_peserta'] ? "'$_POST[jumlah_peserta]'" : 'NULL';

  $s = "INSERT INTO tb_perusahaan (
    nama,
    telepon,
    alamat,
    nomor,
    jumlah_peserta,
    image
  ) VALUES (
    '$_POST[nama]',
    $telepon_or_null,
    $alamat_or_null,
    $jumlah_peserta_or_null,
    $_POST[new_nomor],
    '$new_image'
  )";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $img_asal = $_FILES['image']['tmp_name'];
  $img_baru = "$lokasi_perusahaan/$new_image";
  move_uploaded_file($img_asal, $img_baru);

  echo resize_img($img_baru, '', 300, 300);
  echo div_alert('success', 'Upload logo partner success');
  jsurl('', 2000);
  exit;
} elseif (isset($_POST['btn_delete_perusahaan'])) {
  $id_perusahaan = $_POST['btn_delete_perusahaan'];
  $image = $_POST['image'];
  $s = "DELETE FROM tb_perusahaan WHERE id=$id_perusahaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // echo $s;
  $img = "$lokasi_perusahaan/$image";
  if (unlink($img)) {
    echo div_alert('success', 'Deleting image success.');
  }

  jsurl('', 1000);
  // echo "unlink(\"$lokasi_perusahaan/$image\")";
  exit;
}

































# ===============================================================
# NORMAL FLOW
# ===============================================================
$s = "SELECT 
a.nama as nama,
a.telepon,
a.alamat,
a.jumlah_peserta,
a.image,
a.id as id_perusahaan 
FROM tb_perusahaan a ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_partner = mysqli_num_rows($q);
$jumlah_partner_dikali_dua = $jumlah_partner * 2;
$div = '';
$get_id_perusahaan = $_GET['id_perusahaan'] ?? '';
while ($d = mysqli_fetch_assoc($q)) {
  $id_perusahaan = $d['id_perusahaan'];
  $nama = $d['nama'];
  $lokasi_image = "$lokasi_perusahaan/$d[image]";
  if (!file_exists($lokasi_image)) { // jika image master hilang
    // $s2 = "DELETE FROM tb_perusahaan WHERE id=$id_perusahaan";
    // $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    echo "<div class='red debuga'>DEBUG: DELETE FROM tb_perusahaan WHERE id=$id_perusahaan</div>";
    continue;
  }

  $div_slide .= "
    <div class='slide'>
      <a href='?partner&id_perusahaan=$id_perusahaan'>
        <img src='$lokasi_img_perusahaan/$d[image]' alt='partner-$nama' />
      </a>
    </div>
  ";

  // jika ada id_perusahaan maka isi dengan data perusahaan tersebut
  if ($get_id_perusahaan == $id_perusahaan) {
    $div .= "
      <div class='wadah gradasi-hijau'>
        <h3>Detail Perusahaan</h3>
        <table class=table>
          <tr><td>Nama Perusahaan</td><td>$d[nama]</td></tr>
          <tr><td>Telepon</td><td>$d[telepon]</td></tr>
          <tr><td>Alamat</td><td>$d[alamat]</td></tr>
          <tr><td>Jumlah Peserta MCU</td><td>$d[jumlah_peserta] orang</td></tr>
        </table>
      </div>
    ";
  }
}
























# ===============================================================
# EDIT SECTION
# ===============================================================
$edit_section = $role == 'admin' ? edit_section('perusahaan', 'perusahaan') : '';
if ($edit_section) {

  $s = "SELECT 
  a.nomor,
  a.nama,
  a.telepon,
  a.alamat,
  a.jumlah_peserta,
  a.image,
  a.id as id_perusahaan,
  (SELECT MAX(nomor) FROM tb_perusahaan) max_nomor 
  FROM tb_perusahaan a order by a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  $tr = '';
  $th = '';
  $i = 0;
  $max_nomor = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_perusahaan = $d['id_perusahaan'];
    $max_nomor = $d['max_nomor'];
    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
        || $key == 'id_perusahaan'
        || $key == 'max_nomor'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th class='tengah'>$kolom</th>";
      }
      $triple_id = $key . "__perusahaan__" . $id_perusahaan;
      $editable = 'editable';
      // $editable = '';
      $tengah = '';
      if ($key == 'image') {
        $editable = 'tengah';
        $src = "$lokasi_perusahaan/$value";
        if (file_exists($src)) {
          $value = "
            <a href='$src' target=_blank>
              <img src='$lokasi_perusahaan/$d[image]' class='img-thumbnail' style='max-width:300px;max-height:300px;'>
            </a> 
            <form method=post style='display:inline'>
              <input type=hidden name=image value=$d[image]>
              <button class='btn-transparan' name=btn_delete_perusahaan value=$id_perusahaan onclick='return confirm(\"Hapus gambar ini?\")'>$img_delete</button>
            </form>
          ";
        } else {
          $value = $img_warning;
        }
      } elseif ($key == 'nomor') {
        $tengah = 'tengah';
      }
      $td .= "<td class='$editable $tengah' id='$triple_id'>$value</td>";
    }
    $tr .= "
      <tr>
        $td
      </tr>
    ";
  }

  $new_nomor = $max_nomor + 1;

  $tb_perusahaan = "
    <table class=table>
      <thead>$th</thead>
      $tr
      <tr>
        <td colspan=100%>
          <h3>Tambah Partner</h3>
          <form method=post class='flexy' enctype='multipart/form-data'>
            <div>
              <input required minlength=5 maxlength=50 class='form-control' name=nama placeholder='Nama Perusahaan...'>
            </div>
            <div>
              <input minlength=5 maxlength=14 class='form-control' name=telepon placeholder='Telepon...'>
            </div>
            <div>
              <input minlength=5 maxlength=100 class='form-control' name=alamat placeholder='Alamat...'>
            </div>
            <div>
              <input type=number min=1 max=99999 class='form-control' name=jumlah_peserta placeholder='Jumlah Peserta MCU...'>
            </div>
            <div>
              <input type=hidden name=new_nomor value=$new_nomor>
              <input required class='form-control' type=file name=image accept=.png>
              <div class='f12 biru mt2'>Wajib file PNG, disarankan berlatar putih, max 250x250 pixel</div>
            </div>
            <div>
              <button class='btn btn-success ' name='btn_add_perusahaan'>Add Partner</button>
            </div>
          </form>
        </td>
      </tr>
    </table>
  ";


  $edit_section .= "
  <div class='hideita wadah gradasi-kuning mt2' id=edit_perusahaan>
    <h3>Header Partner</h3>
    <form method=post>
      <input required class='form-control mb2' name='partner_header' value='$partner_header' placeholder='Team Section Header...'>
      <input required class='form-control mb2' name='partner_desc' value='$partner_desc' placeholder='Team Description...'>
      <button class='btn btn-success btn-sm' name=btn_save_settings value=partner>Save Settings</button>
    </form>
    <hr>
    <h3>Edit Image Partner</h3>
    $tb_perusahaan
  </div>
  ";
}

































?>
<section id="partner" class="partner p0">
  <div class="container">

    <div class="section-title">
      <h2><?= $partner_header ?></h2>
      <p><?= $partner_desc ?></p>
    </div>

    <?= $div ?>



    <style>
      .slider {
        height: 250px;
        margin: auto;
        position: relative;
        /* width: 90%; */
        display: grid;
        place-items: center;
        /* border: solid 2px red; */
        overflow: hidden;
      }

      .slide-track {
        display: flex;
        /* Slide track width = total number of slides (9x2=18) x individual slide width(100px) */
        width: calc(<?= $lebar ?>px * <?= $jumlah_partner_dikali_dua ?>);
        border: solid 2px #ccf;

        /* animation */
        animation: scroll 40s linear infinite;
      }

      .slide-track:hover {
        animation-play-state: paused;
      }

      .slide {
        height: 200px;
        width: <?= $lebar ?>px;
        display: flex;
        align-items: center;
        padding: 15px;

      }

      img {
        width: 100%;
        transition: transform 1s;
        cursor: pointer;
      }

      img:hover {
        transform: scale(1.2);
      }

      /* GRADIENT SHADOW */

      .slider::before,
      .slider::after {
        background: linear-gradient(to right,
            rgba(255, 255, 255, 1) 0%,
            rgba(255, 255, 255, 0) 100%);
        content: "";
        height: 100%;
        position: absolute;
        width: 15%;
        z-index: 2;
      }

      .slider::before {
        left: 0;
        top: 0;
      }

      .slider::after {
        right: 0;
        top: 0;
        transform: rotateZ(180deg);
      }

      /* AUTO SCROLL ANIMATION RULE */
      @keyframes scroll {
        0% {
          transform: translateX(0);
        }

        100% {
          /* Move the slide track LEFTWARDS (-100px) by half (18 images/2=9) of its width */
          transform: translateX(calc(-<?= $lebar ?>px * <?= $jumlah_partner ?>));
        }
      }
    </style>
    <div class="slider">
      <div class="slide-track">
        <?= $div_slide ?>

        <!-- SAME SLIDES (DOUBLED) -->
        <?= $div_slide ?>

      </div>
    </div>
  </div>

  <div class="container-fluid">
    <?= $edit_section ?>
  </div>


</section>