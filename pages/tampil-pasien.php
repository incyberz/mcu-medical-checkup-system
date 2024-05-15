<?php
$judul = 'Tampil Pasien';
// $id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
// $nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
// $sub_judul = "<a href='?manage-paket'>Back</a> | Manage Sticker untuk <b class='biru'>$nama_paket</b>";
$sub_judul = 'Berikut adalah data pasien dan fitur pemeriksaan sesuai role Anda';
set_title($judul);
set_judul($judul, $sub_judul);
only('users');
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));









# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_paket'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  echo div_alert('success', "Delete Paket sukses.");
  jsurl('', 3000);
}



# ============================================================
# START TAMPIL PASIEN
# ============================================================
// check order_no (pasien non-mandiri)
$s = "SELECT order_no FROM tb_pasien WHERE id=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));


if (!mysqli_num_rows($q)) {
  div_alert('danger', 'Data pasien tidak ditemukan');
} else {
  $d = mysqli_fetch_assoc($q);
  $order_no = $d['order_no'];
}


if (!$order_no) {
  echo div_alert('danger', 'belum ada handler untuk pasien mandiri. Silahkan hubungi developer!');
} else {
  # ============================================================
  # KHUSUS PASIEN PERUSAHAAN
  # ============================================================
  include 'include/arr_status_pasien.php';

  # ============================================================
  # MAIN SELECT PASIEN
  # ============================================================
  $s = "SELECT 
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
  c.nama as nama_paket 

  FROM tb_pasien a 
  JOIN tb_order b ON a.order_no=b.order_no -- Pasien Non Mandiri
  JOIN tb_paket c ON b.id_paket=c.id 
  WHERE a.id='$id_pasien'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  if (mysqli_num_rows($q)) {
    $i = 0;
    // while (
    $d = mysqli_fetch_assoc($q);
    $foto_profil = $d['foto_profil'];
    $status = $d['status'];
    $nama_paket = $d['nama_paket'];
    $NIK = $d['NIK'];
    $nomor_MCU = $d['nomor_MCU'];
    // ) {
    // $i++;
    foreach ($d as $key => $value) {
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
  }

  include 'include/arr_fitur_nakes.php';
  include 'include/arr_fitur_dokter.php';


  $src = "$lokasi_pasien/$foto_profil";

  $fiturs = [];
  if ($role == 'dokter') {
    $fiturs = $arr_fitur_dokter;
  } elseif ($role == 'nakes') {
    $fiturs = $arr_fitur_nakes;
  }

  $fitur_pasien_header = "<div>Pemeriksaan <b class=darkblue>$nama_paket</b> bagi role  <span class='tebal darkblue'>$jabatan ($role)</span></div>";
  if ($fiturs) {
    $fitur_pemeriksaan = '';
    foreach ($fiturs as $fitur => $Fitur) {
      $fitur_pemeriksaan .= "<div><a class='btn btn-primary ' href='?pemeriksaan&fitur=$fitur&id_pasien=$id_pasien'>$Fitur</a></div> ";
    }
  } else {
    $fitur_pemeriksaan = div_alert('danger', 'Maaf, tidak ada Fitur Pemeriksaan Pasien untuk Anda.');
  }

  $status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';

  echo "
    <div class='wadah tengah gradasi-hijau'>
      <div><a href='?cari-pasien'>$img_prev</a></div>
      <div><img src='$src' class='foto_profil'></div>
      <div class='mb1'>$d[nama]</div>
      <div class='border-bottom mb2 pb2 biru f12'>$NIK | MCU-$nomor_MCU | $status_show</div>
      <div class=''>
        $fitur_pasien_header
        <div class='flexy mt2 flex-center'>
          $fitur_pemeriksaan
        </div>
      </div>
    </div>
    <div class='tengah mb4'><span class=btn_aksi id=tb_detail__toggle>$img_detail</span></div>

    <div class=hideit id=tb_detail>
      <table class='table '>
        $tr
      </table>
    </div>
  ";
}
