<?php
$judul = 'Tampil Pasien';
// $id_paket = $_GET['id_paket'] ?? die(div_alert('danger', 'Index id_paket belum terdefinisi.'));
// $nama_paket = $_GET['nama_paket'] ?? die(div_alert('danger', 'Index nama_paket belum terdefinisi.'));
// $sub_judul = "<a href='?manage_paket'>Back</a> | Manage Sticker untuk <b class='biru'>$nama_paket</b>";
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


$order_no = '';
if (!mysqli_num_rows($q)) {
  div_alert('danger', 'Data pasien tidak ditemukan');
} else {
  $d = mysqli_fetch_assoc($q);
  $order_no = $d['order_no'];
}


if (!$order_no) {
  echo div_alert('danger', 'Pasien ini mendaftar pada jalur mandiri (tanpa order_no)<hr>System belum menyediakan handler untuk pasien mandiri. Silahkan hubungi developer!');
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
  c.id as id_paket, 
  c.nama as nama_paket,
  (
    SELECT 1 FROM tb_mcu WHERE id_pasien=a.id) punya_data 

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
    $id_paket = $d['id_paket'];
    $nama_paket = $d['nama_paket'];
    $NIK = $d['NIK'];
    $nomor_MCU = $d['nomor_MCU'];
    $punya_data = $d['punya_data'];

    $gender = $d['gender'];
    $gender_icon = $gender ? "<img src='$lokasi_icon/gender-$gender.png' height=20px>" : $img_warning;
    $gender_show = gender($gender);

    if ($status == 8 and $punya_data) {
      // update status pasien menjadi 9 (pasien sedang periksa)
      $s2 = "UPDATE tb_pasien SET status=9 WHERE id='$id_pasien'";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      jsurl();
    }

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

  $fitur_pasien_header = "<div>Pemeriksaan <b class=darkblue>$nama_paket</b> bagi role  <span class='tebal darkblue'>$jabatan ($role)</span></div>";

  $fitur_pemeriksaan = '';
  $s = "SELECT 
  b.pemeriksaan,
  c.nama as nama_pemeriksaan  
  FROM tb_paket_sticker a 
  JOIN tb_sticker b ON a.id_sticker=b.id 
  JOIN tb_pemeriksaan c ON b.pemeriksaan=c.pemeriksaan 
  WHERE a.id_paket=$id_paket 
  ORDER BY b.nomor
  ";
  $q2 = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jumlah_pemeriksaan = mysqli_num_rows($q2);
  $tr_progress = '';
  $no = 0;
  $jumlah_pemeriksaan_selesai = 0;
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $no++;
    $s3 = "SELECT 
    a.tanggal_simpan_$d2[pemeriksaan] as tanggal_periksa, 
    (
      SELECT nama FROM tb_user 
      WHERE id=a.pemeriksa_$d2[pemeriksaan] ) as pemeriksa 
    FROM tb_mcu a 
    WHERE a.id_pasien=$id_pasien";
    // echo $s3;
    $q3 = mysqli_query($cn, $s3) or die(mysqli_error($cn));
    $d3 = mysqli_fetch_assoc($q3);
    $tanggal_periksa_show = '<span class="consolas darkblue">' . date('d-F-Y H:i:s', strtotime($d3['tanggal_periksa'])) . '</span> ~ <span class="f12 abu miring">  ' . eta2($d3['tanggal_periksa']) . '</span>';
    if (mysqli_num_rows($q3) and $d3['tanggal_periksa']) {
      $btn = 'secondary';
      $jumlah_pemeriksaan_selesai++;
      $info_pemeriksaan = "<span class=darkabu>Telah diperiksa oleh <b class=darkblue>$d3[pemeriksa]</b>, $tanggal_periksa_show</span> $img_check";
    } else {
      $btn = 'primary';
      $info_pemeriksaan = '<span class="f12 miring abu">belum menjalani pemeriksaan di bagian ini.</span>';
    }

    $tr_progress .= "
      <tr>
        <td>
          $no
        </td>
        <td class=kiri>
          $d2[nama_pemeriksaan]
        </td>
        <td class=kiri>
          $info_pemeriksaan
        </td>
      </tr>
    ";

    // die($s3);

    $fitur_pemeriksaan .= "<div><a class='btn btn-$btn ' href='?pemeriksaan&pemeriksaan=$d2[pemeriksaan]&id_pasien=$id_pasien'>$d2[nama_pemeriksaan]</a></div> ";
  }

  $info_pemeriksaan = '';
  if ($jumlah_pemeriksaan_selesai == $jumlah_pemeriksaan) {
    if ($status == 9) {
      //update status pasien menjadi 10 (pasien selesai)
      $s2 = "UPDATE tb_pasien SET status=10 WHERE id='$id_pasien'";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      jsurl();
    }
    $info_pemeriksaan = "<div class='alert alert-success mt2'>Pasien telah menjalani semua pemeriksaan $img_check</div>";
  }

  $tb_progress = '';
  if ($punya_data) {
    $tb_progress = "<table class='table table-striped table-hover mt4'>$tr_progress</table>";
  } else {
    $tb_progress = div_alert('info mt2', 'Pasien ini belum menjalani pemeriksaan');
  }

  $status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';
  $src = "$lokasi_pasien/$foto_profil";

  echo "
    <div class='wadah tengah gradasi-hijau'>
      <div><a href='?cari-pasien'>$img_prev</a></div>
      <div><img src='$src' class='foto_profil'></div>
      <div class='mb1'>$gender_icon $d[nama]</div>
      <div class='border-bottom mb2 pb2 biru f12'>$NIK | MCU-$nomor_MCU | $status_show</div>
      <div class=''>
        $fitur_pasien_header
        <div class='flexy mt2 flex-center'>
          $fitur_pemeriksaan
        </div>
      </div>
      <div class='flexy flex-center'>
        <div>
          $tb_progress
          $info_pemeriksaan
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
