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
  <hr>
";

// zzz debug
$pesan = '';
if ($is_login_as) {
  $pesan = div_alert('danger', "Anda (Petugas) sedang login as sebagai pasien. Mohon berhati-hati dalam mengubah data pasien.");
}


# ============================================================
# ARRAY HASIL PEMERIKSAAN
# ============================================================
$arr_hasil = [];
if ($awal_periksa) {
  $s2 = "SELECT * FROM tb_hasil_pemeriksaan WHERE id_pasien=$id_pasien";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  $hasil = mysqli_fetch_assoc($q2);
  $arr = explode('||', $hasil['arr_tanggal_by']);
  foreach ($arr as $v) {
    if ($v) {
      $arr2 = explode('=', $v);
      $id_pemeriksaan = $arr2[0];
      $arr_hasil[$id_pemeriksaan] = $arr2[1];
    }
  }
}

# ============================================================
# DETAIL PEMERIKSAAN 
# ============================================================
$s2 = "SELECT 
c.id as id_pemeriksaan, 
c.nama as nama_pemeriksaan 
FROM tb_paket a 
JOIN tb_paket_detail b ON a.id=b.id_paket 
JOIN tb_pemeriksaan c ON b.id_pemeriksaan=c.id 
WHERE a.id='$id_paket' 
ORDER BY c.nomor
";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
$li = '';
if (mysqli_num_rows($q2)) {
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $sudah = array_key_exists($d2['id_pemeriksaan'], $arr_hasil) ? $img_check : '~ <span class="f10 miring abu">blm di-input</span>';
    $li .= "<li>$d2[nama_pemeriksaan] $sudah</li>";
  }
}
$detail_pemeriksaan = "<ol>$li</ol>";


if ($status >= 7) { // sudah mengisi kesiapan
  $blok_kesiapan = "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body tengah'>
      <h3>Progress Medical Checkup</h3>
      $pesan
      <div id=detail_pemeriksaan class='mt2 kiri'>$detail_pemeriksaan</div>
      <hr>
      Jika Petugas sudah publish hasil Medical Checkup, maka Anda dapat Download Hasil MCU disini

      <button class='btn btn-secondary w-100 mt4 mb2' onclick='alert(\"Hasil MCU untuk Anda belum ada.\")'>Download Hasil Medical Checkup</button>
      <div class='abu miring f14'>Saat ini belum ada data Hasil MCU untuk Anda.</div>
    </div>
  </div>
";
}
