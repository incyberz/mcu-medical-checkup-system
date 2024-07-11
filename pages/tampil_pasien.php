<?php
$jenis = $_GET['jenis'] ?? die(div_alert('danger', "Page ini membutuhkan index [jenis]"));
$JENIS = strtoupper($jenis);
$MCU = $jenis == 'mcu' ? 'MCU' : 'Lab';
set_title("Pemeriksaan $MCU");
only('users');
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));









# ============================================================
# VALIDATION
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
  exit;
}



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
a.password,
a.status,
a.foto_profil,
a.riwayat_penyakit,
a.gejala_penyakit,
a.gaya_hidup,
a.keluhan,
c.id as id_paket, 
c.nama as nama_paket,
d.nama as nama_program,
(
  SELECT 1 FROM tb_mcu WHERE id_pasien=a.id) data_pemeriksaan 

FROM tb_pasien a 
JOIN tb_order b ON a.order_no=b.order_no -- Pasien Non Mandiri
JOIN tb_paket c ON b.id_paket=c.id 
JOIN tb_program d ON c.id_program=d.id 
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
  $nama_program = $d['nama_program'];
  $NIK = $d['NIK'];
  $nomor_MCU = $d['nomor_MCU'];
  $data_pemeriksaan = $d['data_pemeriksaan'];

  $gender = $d['gender'];
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
  foreach ($d as $key => $value) {
    if (
      $key == 'id'
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
      $value = $value ? " $username ~ <a target=_blank href='?login_as&role=pasien&username=$value' onclick='return confirm(`Login sebagai pasien: $d[nama] ?`)'>$img_login_as login as pasien</a>" : die(erid('username'));
    } elseif ($key == 'password') {
      $value = $value ? '<span class="f12 green">sudah diubah</span>' : $d['username'] . ' <span class="f12 miring abu">( masih default )</span>';
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
      $value = $value ? 'Ada' : '<span class="f12 miring abu">belum menjalani pemeriksaan</span>';
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



































# ============================================================
# LIST PEMERIKSAAN
# ============================================================
$info_paket = "<div>Pemeriksaan $MCU <b class=darkblue>$nama_paket</b> | <b class=darkblue>$nama_program</b> </div>";

$buttons = '';
$s = "SELECT 
b.pemeriksaan,
c.nama as nama_pemeriksaan,  
d.nama as jenis_pemeriksaan  
FROM tb_paket_sticker a 
JOIN tb_sticker b ON a.id_sticker=b.id 
JOIN tb_pemeriksaan c ON b.pemeriksaan=c.pemeriksaan 
JOIN tb_jenis_pemeriksaan d ON c.jenis=d.jenis 
WHERE a.id_paket=$id_paket 
AND c.jenis='$jenis'
ORDER BY b.nomor
";

if ($JENIS == 'COR') {
  $s = "SELECT 
  1,
  e.id as id_pemeriksaan,
  e.nama as nama_pemeriksaan,
  e.jenis as jenis_pemeriksaan,
  -- e.kode as pemeriksaan
  (SELECT COUNT(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=e.id) count_pemeriksaan_detail
  FROM tb_pasien a 
  JOIN tb_order b ON a.order_no=b.order_no 
  JOIN tb_paket c ON b.id_paket=c.id 
  JOIN tb_paket_detail d ON d.id_paket=c.id 
  JOIN tb_pemeriksaan e ON d.id_pemeriksaan=e.id 
  WHERE a.id=$id_pasien
  ";
} else {
  die(div_alert('danger', "Invalid Jenis Pasien: $JENIS"));
}


$q2 = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pemeriksaan = mysqli_num_rows($q2);
$tr_progress = '';
$jenis_pemeriksaan = '';
$no = 0;
$jumlah_pemeriksaan_selesai = 0;
if (!mysqli_num_rows($q2)) {
  $tr_progress = div_alert('danger', "
    Paket ini belum punya List Pemeriksaan | 
    <a href='?assign_pemeriksaan&id_paket=$id_paket&nama_paket=$nama_paket'>
      Assign Pemeriksaan
    </a>
  ");
} else {
}
while ($d2 = mysqli_fetch_assoc($q2)) {
  $no++;
  $id_pemeriksaan = $d2['id_pemeriksaan'];
  $jenis_pemeriksaan = $d2['jenis_pemeriksaan'];
  $count_pemeriksaan_detail = $d2['count_pemeriksaan_detail'];


  if (!$count_pemeriksaan_detail) {
    $link = "<a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan'>Manage</a>";
    echo (div_alert('danger', "Pemeriksaan <b class=darkblue>$d2[nama_pemeriksaan]</b> belum punya detail pemeriksaan | $link"));
  }

  $s3 = "SELECT 
  '' as tanggal_periksa, 
  '' as pemeriksa 
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

  $buttons .= "<div><a class='btn btn-$btn ' href='?pemeriksaan&pemeriksaan=ZZZ'>$d2[nama_pemeriksaan]</a></div> ";
}

$info_pemeriksaan = '';
if ($jumlah_pemeriksaan_selesai == $jumlah_pemeriksaan and $jumlah_pemeriksaan) {
  if ($status == 9) {
    //update status pasien menjadi 10 (pasien selesai)
    $s2 = "UPDATE tb_pasien SET status=10 WHERE id='$id_pasien'";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    jsurl();
  }
  $info_pemeriksaan =  "<div class='alert alert-success mt2'>Pasien telah menjalani semua pemeriksaan $img_check</div>";
}

$tb_progress = '';
if ($data_pemeriksaan) {
  $tb_progress = "<table class='table table-striped table-hover mt4'>$tr_progress</table>";
} else {
  $tb_progress = div_alert('info mt2', 'Pasien ini belum menjalani pemeriksaan');
}

$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';
$src = "$lokasi_pasien/$foto_profil";

# ============================================================
# FINAL ECHO PEM MCU
# ============================================================
echo "
  <div class='wadah tengah gradasi-hijau'>
    <h2>$jenis_pemeriksaan</h2>
    <div><a href='?cari_pasien'>$img_prev</a></div>
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $d[nama]</div>
    <div class='border-bottom mb2 pb2 biru f12'>$NIK | MCU-$nomor_MCU | $status_show</div>
    <div class=''>
      $info_paket
      <div class='flexy mt2 flex-center'>
        $buttons
      </div>
    </div>
    <div class='flexy flex-center'>
      <div>
        $tb_progress
        $info_pemeriksaan
      </div>
    </div>
  </div>
";

# ============================================================
# FINAL ECHO PASIEN
# ============================================================
echo "
  <div class='tengah mb4'><span class='btn_aksi bold f14 darkblue' id=tb_detail__toggle>$img_detail Info Detail Pasien</span></div>
  <div class='hideita border-top pt3' id=tb_detail>
    <table class='table '>
      $tr
    </table>
  </div>
";
