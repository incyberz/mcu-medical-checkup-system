<?php
$s = "SELECT 
a.order_no,
a.nama,
a.nomor as nomor_MCU,
a.gender,
a.usia,
a.tanggal_lahir,
a.nikepeg as NIK,
a.username,
a.status,
a.foto_profil,
a.riwayat_penyakit,
a.gejala_penyakit,
a.gaya_hidup,
a.keluhan,
(SELECT 1 FROM tb_hasil_pemeriksaan WHERE id_pasien=a.id) punya_hasil

FROM tb_pasien a WHERE id='$id_pasien'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$punya_hasil = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  // while (
  $pasien = mysqli_fetch_assoc($q);
  $foto_profil = $pasien['foto_profil'];
  $order_no = $pasien['order_no'];
  $status = $pasien['status'];
  $NIK = $pasien['NIK'];
  $punya_hasil = $pasien['punya_hasil'];

  $gender = $pasien['gender'];
  $gender_icon = $gender ? "<img src='$lokasi_icon/gender-$gender.png' height=20px>" : $img_warning;
  $gender_show = gender($gender);

  foreach ($pasien as $key => $value) {
    if (
      $key == 'id'
      || $key == 'foto_profil'
    ) continue;

    if ($key == 'gender') {
      $value = gender($value);
    } elseif ($key == 'nomor_MCU') {
      $value = "MCU-$value";
    } elseif ($key == 'tanggal_lahir') {
      $value = tanggal($value);
    } elseif ($key == 'status') {
      if ($value) {
        $value = '<span class="blue tebal">' . $arr_status_pasien[$value] . " ($value)</span>";
      } else {
        $value = $null;
      }
    } elseif (
      $key == 'riwayat_penyakit'
      || $key == 'gejala_penyakit'
      || $key == 'gaya_hidup'
    ) {
      $arr = explode(',', $value);
      $value = '';
      foreach ($arr as $k => $v) if ($v) $value .= "<li>$v</li>";
      $value = "<ol class=pl4>$value</ol>";
    }

    $kolom = key2kolom($key);
    $tr .= "
      <tr>
        <td class=kolom>$kolom</td>
        <td>$value</td>
      </tr>
    ";
  }
} else {
  die(div_alert('danger', 'Pasien tidak ditemukan'));
}
