<?php


# ============================================================
# PROCESSORS
# ============================================================
if (isset($_POST['btn_upload_foto_profil'])) {
  include 'include/resize_img.php';
  $id_pasien = $_POST['btn_upload_foto_profil'];
  $new_foto_profil = "pasien-$id_pasien-$detik.jpg";
  $new_foto_profil_thumb = "pasien-$id_pasien-$detik-thumb.jpg";
  $file_tmp = $_FILES['foto_profil']['tmp_name'];
  $file_baru = "$lokasi_pasien/$new_foto_profil";
  $file_baru_thumb = "$lokasi_pasien/$new_foto_profil_thumb";

  if (move_uploaded_file($file_tmp, $file_baru)) {
    echo resize_img($file_baru, '', 500, 500);
    echo resize_img($file_baru, $file_baru_thumb, 150, 150);
  }

  $s = "UPDATE tb_pasien SET foto_profil='$new_foto_profil' WHERE id=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  echo div_alert('success', 'Upload foto profil berhasil.');
  jsurl('', 1000);
}













# ============================================================
# NORMAL FLOW
# ============================================================
$type_btn_foto_profil = !$foto_profil ? 'primary' : 'secondary';
$notif_btn_foto_profil = !$foto_profil ? "<div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>Silahkan upload foto profil Anda agar dapat meneruskan proses Medical Checkup</div>" : "<div class='tengah f12 abu mt1' id=btn_update_biodata_info>Anda sudah upload foto profil</div>";


$hide_form = '';
$reupload = '';
$img_profil = '';
if ($foto_profil) {
  $src = "$lokasi_pasien/$foto_profil";
  if (!file_exists($src)) {
    echo (div_alert('danger', 'Profil Image Anda hilang di sisi server. Segera laporkan hal ini ke Petugas via nomor whatsApp paling atas, atau silahkan Anda upload ulang.'));
  } else {
    $hide_form = 'hideit';
    $reupload = "<div class=tengah><span class=btn_aksi id=form_upload_foto_profil__toggle>Reupload</span></div>";
    $img_profil = "<div class=tengah><img src='$src' class=foto_profil></div>";
  }
}

$blok_foto_profil = "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body'>
      <h3>Foto Profil Anda</h3>
      $img_profil
      $reupload
      <form method=post enctype=multipart/form-data class='$hide_form' id=form_upload_foto_profil>
        <div class='tengah blue mt2 mb2'>Sediakanlah Foto Wajah Anda (foto formal setengah badan). Crop foto agar ukuran tinggi dan lebarnya hampir sama. Pakaian boleh apa saja asal sopan. Hal ini untuk mempercepat proses pendaftaran Anda saat di Bagian Registrasi.</div>

        <input type=file required class='form-control mb2' name=foto_profil id=foto_profil accept=.jpg>
        <button class='btn btn-$type_btn_foto_profil w-100' name=btn_upload_foto_profil id=btn_upload_foto_profil value=$id_pasien>Upload Foto Profil</button>
        $notif_btn_foto_profil
        <hr>
        <div class='tengah btn_aksi darkblue mb4' id=contoh_profil__toggle><u>Lihat contoh profil</u></div>
        <div id=contoh_profil class=hideit>
          <div class=flexy style='justify-content:center'>
            <div><img src='assets/img/example-profil/1.jpg' class='foto_profil example-profil'></div>
            <div><img src='assets/img/example-profil/2.jpg' class='foto_profil example-profil'></div>
            <div><img src='assets/img/example-profil/3.jpg' class='foto_profil example-profil'></div>
            <div><img src='assets/img/example-profil/4.jpg' class='foto_profil example-profil'></div>
          </div>
        </div>

      </form>
    </div>
  </div>
";
