<?php
$fields = "
  a.nama as nama_pasien,
  a.status,
  a.gender,
  a.no_ktp,
  a.no_bpjs,
  a.nikepeg as NIK,
  a.tempat_lahir,
  a.tanggal_lahir,
  a.alamat,
  a.kecamatan,
  a.kabupaten,
  a.date_created as tanggal_daftar,
  a.last_update,
  a.username,
  a.password,
  a.riwayat_penyakit,
  a.tanggal_mengisi_riwayat_penyakit,
  a.gejala_penyakit,
  a.tanggal_mengisi_gejala_penyakit,
  a.gaya_hidup,
  a.tanggal_mengisi_gaya_hidup,
  a.keluhan,
  a.tanggal_mengisi_keluhan,
  a.id_paket_custom,
  a.foto_profil,
  (SELECT 1 FROM tb_hasil_pemeriksaan WHERE id_pasien=a.id) data_pemeriksaan
";

if ($JENIS == 'COR') {
  $s_pasien = "SELECT 
  c.id as id_paket,
  c.nama as nama_paket,
  'Corporate' as jenis_pasien,
  $fields
  FROM tb_pasien a 
  JOIN tb_order b ON a.order_no=b.order_no 
  JOIN tb_paket c ON b.id_paket=c.id 
  WHERE a.id=$id_pasien
  ";
} else {
  $s_pasien = "SELECT 
  b.id as id_paket,
  CONCAT('Paket Custom ',b.id) as nama_paket,
  e.nama as jenis_pasien,
  $fields
  FROM tb_pasien a 
  JOIN tb_paket_custom b ON b.id=a.id_paket_custom 
  JOIN tb_jenis_pasien e ON a.jenis=e.jenis
  WHERE a.id=$id_pasien
  ";
}
$q = mysqli_query($cn, $s_pasien) or die(mysqli_error($cn));
$tr = '';
$data_pemeriksaan = '';
$status = '';
if (mysqli_num_rows($q) > 1) die('Data pasien tidak unik : ' . mysqli_num_rows($q));
if (mysqli_num_rows($q)) {
  $i = 0;
  $pasien = mysqli_fetch_assoc($q);
  $foto_profil = $pasien['foto_profil'];
  $status = $pasien['status'];
  $id_paket = $pasien['id_paket'];
  $NIK = $pasien['NIK'];
  $data_pemeriksaan = $pasien['data_pemeriksaan'];

  $gender = $pasien['gender'];
  $gender_icon = $gender ? "<img src='$lokasi_icon/gender-$gender.png' height=20px>" : $img_warning;
  $gender_show = gender($gender);

  if ($status == 8 and $data_pemeriksaan) {
    // update status pasien menjadi 9 (pasien sedang periksa)
    $s2 = "UPDATE tb_pasien SET status=9 WHERE id='$id_pasien'";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    jsurl();
  }

  # ============================================================
  # LIST PROPERTIES PASIEN
  # ============================================================
  $belum = '<span class="red f12 miring">belum mengisi</span>';
  foreach ($pasien as $key => $value) {
    if (
      $key == 'id'
      || $key == 'id_klinik'
      || $key == 'foto_profil'
      || $key == 'id_paket'
      || $key == 'NIK'
    ) continue;

    if ($key == 'gender') {
      $value = $value ? gender($value) : $null;
    } elseif ($key == 'usia') {
      $value = $value ? "$value <span class='abu f12 miring'>tahun</span>" : $null;
    } elseif ($key == 'nomor_MCU') {
      $value = "MCU-$value";
    } elseif ($key == 'tanggal_lahir') {
      $value = $value ? tanggal($value) : $null;
    } elseif ($key == 'username') {
      $value = $value ? " $pasien[username] ~ <a target=_blank href='?login_as&role=pasien&username=$value' onclick='return confirm(`Login sebagai pasien: $pasien[nama_pasien] ?`)'>$img_login_as login as pasien</a>" : '<span class="f12 abu miring">(tanpa username)</span>';
    } elseif ($key == 'password') {
      $value = $value ? '<span class="f12 green">sudah diubah</span>' : $pasien['username'] . ' <span class="f12 miring abu">( masih default )</span>';
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
      || $key == 'keluhan'
    ) {
      $arr = explode(',', $value);
      $value = '';
      foreach ($arr as $k => $v) if ($v) $value .= "<li>$v</li>";
      $value = $value ? "<ol class=pl4>$value</ol>" : $belum;
    } elseif ($key == 'data_pemeriksaan') {
      $value = $value ? 'Sudah Ada' : '<span class="f12 miring red">belum menjalani pemeriksaan</span>';
    }

    $value = $value ? $value : $null;

    $kolom = key2kolom($key);
    $tr .= "
      <tr>
        <td class=kolom>$kolom</td>
        <td>$value</td>
      </tr>
    ";
  }
}
