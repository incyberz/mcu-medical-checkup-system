<?php
set_title("Pemeriksaan");
only('users');
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));
$s = "SELECT order_no,jenis FROM tb_pasien WHERE id=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$order_no = '';
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', 'Data pasien tidak ditemukan'));
} else {
  $d = mysqli_fetch_assoc($q);
  $order_no = $d['order_no'];
  $jenis = $d['jenis'];
  $JENIS = strtoupper($jenis);
}























if (!$order_no) {
  echo div_alert('info tengah', 'Pasien ini mendaftar pada jalur mandiri (BPJS/Individu)');
  // echo "<div class=tengah><a class='btn btn-primary' href='?pemeriksaan'>Langsung ke Pemeriksaan</a></div>";
  // exit;
}



# ============================================================
# KHUSUS PASIEN PERUSAHAAN
# ============================================================
include 'include/arr_status_pasien.php';

# ============================================================
# MAIN SELECT PASIEN
# ============================================================
include 'tampil_pasien-data_pasien.php';
$id_paket = $pasien['id_paket'];
$nama_paket = $pasien['nama_paket'];



































$buttons = '';
# ============================================================
# LIST PEMERIKSAAN
# ============================================================
if ($JENIS == 'COR') {
  $s_pemeriksaan = "SELECT 
  e.id as id_pemeriksaan,
  e.nama as nama_pemeriksaan,
  f.nama as jenis_pemeriksaan,
  e.singkatan,
  (SELECT COUNT(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=e.id) count_pemeriksaan_detail
  FROM tb_pasien a 
  JOIN tb_order b ON a.order_no=b.order_no 
  JOIN tb_paket c ON b.id_paket=c.id 
  JOIN tb_paket_detail d ON d.id_paket=c.id 
  JOIN tb_pemeriksaan e ON d.id_pemeriksaan=e.id 
  JOIN tb_jenis_pemeriksaan f ON e.jenis=f.jenis 
  WHERE a.id=$id_pasien 
  ";
} else { // pasien non COR
  $s_pemeriksaan = "SELECT 
  d.id as id_pemeriksaan,
  d.nama as nama_pemeriksaan,
  e.nama as jenis_pemeriksaan,
  d.singkatan,
  (SELECT COUNT(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=d.id) count_pemeriksaan_detail
  FROM tb_pasien a 
  JOIN tb_paket_custom b ON a.id_paket_custom=b.id  
  JOIN tb_paket_custom_detail c ON c.id_paket_custom=b.id 
  JOIN tb_pemeriksaan d ON c.id_pemeriksaan=d.id 
  JOIN tb_jenis_pemeriksaan e ON d.jenis=e.jenis 
  WHERE a.id=$id_pasien 
  ";
}

$q_pemeriksaan = mysqli_query($cn, $s_pemeriksaan) or die(mysqli_error($cn));
$jumlah_pemeriksaan = mysqli_num_rows($q_pemeriksaan);
$tr_progress = '';
$jenis_pemeriksaan = '';
$no = 0;
$jumlah_pemeriksaan_selesai = 0;
if (!mysqli_num_rows($q_pemeriksaan)) {
  $tr_progress = div_alert('danger', "
    Paket ini belum punya List Pemeriksaan | 
    <a href='?assign_pemeriksaan&id_paket=$id_paket&nama_paket=$nama_paket'>
      Assign Pemeriksaan
    </a>
  ");
} else {
}
while ($pemeriksaan = mysqli_fetch_assoc($q_pemeriksaan)) {
  $no++;
  $id_pemeriksaan = $pemeriksaan['id_pemeriksaan'];
  $nama_pemeriksaan = $pemeriksaan['nama_pemeriksaan'];
  $jenis_pemeriksaan = $pemeriksaan['jenis_pemeriksaan'];
  $count_pemeriksaan_detail = $pemeriksaan['count_pemeriksaan_detail'];



  # ============================================================
  # ZZZ 
  # ============================================================
  $s3 = "SELECT 
  '' as tanggal_periksa, 
  '' as pemeriksa 
  FROM tb_mcu a 
  WHERE a.id_pasien=$id_pasien";
  // echo $s3;
  $q3 = mysqli_query($cn, $s3) or die(mysqli_error($cn));
  $d3 = mysqli_fetch_assoc($q3);
  // $tanggal_periksa_show = '<span class="consolas darkblue">' . date('d-F-Y H:i:s', strtotime($d3['tanggal_periksa'])) . '</span> ~ <span class="f12 abu miring">  ' . eta2($d3['tanggal_periksa']) . '</span>';
  $tanggal_periksa_show = 'TANGGAL_PERIKSA_SHOW';
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
        $pemeriksaan[nama_pemeriksaan]
      </td>
      <td class=kiri>
        $info_pemeriksaan
      </td>
    </tr>
  ";

  // die($s3);

  $button = "<div><a class='btn btn-$btn ' href='?pemeriksaan&id_pemeriksaan=$id_pemeriksaan&id_pasien=$id_pasien&nama_pemeriksaan=$nama_pemeriksaan&JENIS=$JENIS&id_paket=$id_paket'>$pemeriksaan[singkatan]</a></div> ";
  if (!$count_pemeriksaan_detail) {
    $link = "<a href='?manage_pemeriksaan_detail&id_pemeriksaan=$id_pemeriksaan&nama_pemeriksaan=$pemeriksaan[nama_pemeriksaan]'>Manage</a>";
    $pesan = "Pemeriksaan <b class=darkblue>$pemeriksaan[nama_pemeriksaan]</b> belum punya detail pemeriksaan";
    echo (div_alert('danger',   "$pesan | $link"));
    $button = "<div><span class='btn btn-secondary' onclick='alert(`" . strip_tags($pesan) . "`)'>$pemeriksaan[singkatan]</span></div> ";
  }

  $buttons .= $button;
}

$info_pemeriksaan = '';
if ($jumlah_pemeriksaan_selesai == $jumlah_pemeriksaan and $jumlah_pemeriksaan) {
  if ($status == 9) {
    //update status pasien menjadi 10 (pasien selesai)
    $s2 = "UPDATE tb_pasien SET status=10 WHERE id='$id_pasien'";
    $q_pemeriksaan = mysqli_query($cn, $s2) or die(mysqli_error($cn));
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
    <div><a href='?cari_pasien'>$img_prev</a></div>
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $pasien[nama_pasien]</div>
    <div class='border-bottom mb2 pb2 biru f12'>MCU-$id_pasien | $status_show</div>
    <div class=''>
      <h4 class=mb4>MCU $pasien[nama_paket] $pasien[jenis_pasien]</h4>
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
