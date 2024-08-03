<?php

# ============================================================
# PROCESSORS
# ============================================================
if (isset($_POST['btn_submit_kesiapan'])) {
  $s = "UPDATE tb_pasien SET status = 7 WHERE id = '$id_user'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "Update kesiapan berhasil.");
  jsurl('?pasien_home', 1000);
  exit;
}



$info_kesiapan = "
  <div class='abu miring tengah mt2 mb2'>
    Saat ini prasyarat pemeriksaan sudah lengkap. Jika Anda sudah siap dengan Jadwal Pemeriksaannya, Silahkan Anda ceklis kesiapan dan Submit.
  </div>
";

$form_kesiapan = "
  <form method=post>
    <div>
      <label class='blue tebal pointer'>
        <input type=checkbox required class=''> Saya sudah siap menjalani Pemeriksaan Medical Checkup secara offline sesuai jadwal yang sudah ditentukan 
      </label>
    </div>
    <button class='btn btn-primary w-100 mt2' name=btn_submit_kesiapan >Saya Siap</button>

  </form>
";



$blok_kesiapan = $status < 6 ? '' : "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body tengah'>
      <h3>Kesiapan Pemeriksaan</h3>
      <img src='assets/img/ilustrasi/ready.png' class='img-thumbnail img-fluid' />
      $info_kesiapan
      $form_kesiapan
    </div>
  </div>
";

$pesan = "
  <div class='mb2'>
    <img src='assets/img/ilustrasi/lengkap.jpg' class='img-thumbnail img-fluid' />
  </div>
  Silahkan Anda mengikuti Pemeriksaan Medical Checkup sesuai dengan jadwal Anda. Nantikan info terbaru tentang progres pemeriksaan MCU Anda di laman ini.
";
if ($is_login_as) {
  $pesan = div_alert('danger', "Anda (Petugas) sedang login as sebagai pasien. Mohon berhati-hati dalam mengubah data pasien.");
}

if ($status >= 7) { // sudah mengisi kesiapan
  $blok_kesiapan = "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body tengah'>
      <h3>Progress Medical Checkup</h3>
      $pesan
      <hr>
      Jika Petugas sudah publish hasil Medical Checkup, maka Anda dapat Download Hasil MCU disini

      <button class='btn btn-secondary w-100 mt4 mb2' onclick='alert(\"Hasil MCU untuk Anda belum ada.\")'>Download Hasil Medical Checkup</button>
      <div class='abu miring f14'>Saat ini belum ada data Hasil MCU untuk Anda.</div>
    </div>
  </div>
";
}
