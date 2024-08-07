<?php
if (isset($_POST['btn_mulai_pemeriksaan'])) {
  $s = "INSERT INTO tb_hasil_pemeriksaan (
    id_pasien,
    awal_periksa,
    awal_periksa_by,
    status
  ) VALUES (
    $id_pasien,
    CURRENT_TIMESTAMP,
    $id_user,
    1 -- status 1 artinya sudah mulai periksa
  ) ON DUPLICATE KEY UPDATE 
    awal_periksa = CURRENT_TIMESTAMP,
    awal_periksa_by = $id_user,
    status = 1
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // echo $s;
  echo div_alert('success', 'Data Awal Pemeriksaan MCU berhasil dibuat.');
  jsurl('', 1000);
  exit;
}

set_title('Awal Pemeriksaan');

$tanggal_show = date('d-F-Y H:i');
$tanggal = date('d-F-Y');
$pukul = date('H:i');

$form_pemeriksaan = "
  <form method='post' class='form-pemeriksaan wadah bg-white'>
    <div class='wadah gradasi-toska tengah'>
      <img src='assets/img/ilustrasi/medical-checkup.jpg' class='img-fluid img-thumbnail' />
    </div>  
    <div class='flexy mb2 flex-center'>
      <input type=checkbox required id=cek>
      <label for=cek>Pasien mulai masuk pemeriksaan pada tanggal <b class=darkblue>$tanggal</b> pukul <b class=darkblue>$pukul</b>.</label>
    </div>
    <button class='btn btn-primary w-100' name=btn_mulai_pemeriksaan value='$id_pasien'>Mulai Pemeriksaan</button>
    <div class='tengah f12 mt1 abu'>Disubmit oleh <span class='darkblue'>$nama_user</span> pada tanggal <span class=consolas>$tanggal_show</span></div>
  </form>

";
