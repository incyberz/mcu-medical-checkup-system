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

























# ============================================================
# INCLUDES
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/arr_user.php';


# ===========================================================
# HASIL (IF EXISTS)
# ===========================================================
$arr_id_detail = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];
$arr_sampel_tanggal = [];
$arr_sampel_by = [];

include 'pemeriksaan-hasil_at_db.php';

# ============================================================
# MAIN SELECT PASIEN
# ============================================================
// $id_harga_perusahaan = $pasien['id_harga_perusahaan'] ?? '';
include 'tampil_pasien-data_pasien.php';
$id_paket = $pasien['id_paket'] ?? '';

if (!$id_paket and !$id_harga_perusahaan) die(div_alert('danger', "Pasien ini belum mempunyai Paket Pemeriksaan. | <a href='?manage_paket_custom&id_pasien=$id_pasien'>Manage Paket</a>"));
$nama_paket = $pasien['nama_paket'];



































# ============================================================
# LIST PEMERIKSAAN
# ============================================================
if ($JENIS == 'COR') {
  if ($id_harga_perusahaan) {
    $s_pemeriksaan = "SELECT  
    e.id as id_pemeriksaan,
    e.nama as nama_pemeriksaan,
    f.nama as jenis_pemeriksaan,
    e.singkatan,
    e.sampel,
    e.jenis,
    2 as status_bayar, -- status bayar 2 = corporate
    (SELECT COUNT(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=e.id) count_pemeriksaan_detail
    FROM tb_pasien a 
    JOIN tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id 
    JOIN tb_paket c ON b.id_paket=c.id 
    JOIN tb_paket_detail d ON d.id_paket=c.id 
    JOIN tb_pemeriksaan e ON d.id_pemeriksaan=e.id 
    JOIN tb_jenis_pemeriksaan f ON e.jenis=f.jenis 
    WHERE a.id=$id_pasien 
    ";
  } else {
    $s_pemeriksaan = "SELECT 
    e.id as id_pemeriksaan,
    e.nama as nama_pemeriksaan,
    f.nama as jenis_pemeriksaan,
    e.singkatan,
    e.sampel,
    e.jenis,
    2 as status_bayar, -- status bayar 2 = corporate
    (SELECT COUNT(1) FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=e.id) count_pemeriksaan_detail
    FROM tb_pasien a 
    JOIN tb_order b ON a.order_no=b.order_no 
    JOIN tb_paket c ON b.id_paket=c.id 
    JOIN tb_paket_detail d ON d.id_paket=c.id 
    JOIN tb_pemeriksaan e ON d.id_pemeriksaan=e.id 
    JOIN tb_jenis_pemeriksaan f ON e.jenis=f.jenis 
    WHERE a.id=$id_pasien 
    ";
  }
} else { // pasien non COR
  $s_pemeriksaan = "SELECT 
  d.id as id_pemeriksaan,
  d.nama as nama_pemeriksaan,
  e.nama as jenis_pemeriksaan,
  d.singkatan,
  b.status_bayar,
  d.sampel,
  d.jenis,
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
$tr_progress_mobile = '';
$jenis_pemeriksaan = '';
$no = 0;
$jumlah_pemeriksaan_selesai = 0;
$jumlah_sampel_selesai = 0;
if (!mysqli_num_rows($q_pemeriksaan)) {
  $tr_progress = div_alert('danger', "
    Paket ini belum punya List Pemeriksaan | 
    <a href='?assign_pemeriksaan&id_paket=$id_paket&nama_paket=$nama_paket'>
      Assign Pemeriksaan
    </a>
  ");
  $tr_progress_mobile = $tr_progress;
}
$arr_csampel = [];
$link_hasil_penunjang = '';
while ($pemeriksaan = mysqli_fetch_assoc($q_pemeriksaan)) {
  $no++;
  $id_pemeriksaan = $pemeriksaan['id_pemeriksaan'];
  $nama_pemeriksaan = $pemeriksaan['nama_pemeriksaan'];
  $jenis_pemeriksaan = $pemeriksaan['jenis_pemeriksaan'];
  $count_pemeriksaan_detail = $pemeriksaan['count_pemeriksaan_detail'];
  $status_bayar = $pemeriksaan['status_bayar'];
  if ($jenis == 'idv' and $status_bayar == '') {
    $buttons = div_alert('danger', "Pasien Individu ini belum melakukan Pembayaran. <hr><a class='btn btn-primary' href='?manage_paket_custom&id_pasien=$id_pasien'>Manage Paket</a>");
    exit;
  }

  if (strtolower($pemeriksaan['jenis']) != 'mcu') $link_hasil_penunjang .= " 
    <a class='btn btn-primary' href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$pemeriksaan[jenis]&id_pemeriksaan=$id_pemeriksaan'>
      $nama_pemeriksaan
    </a>
  ";

  $sampel = $pemeriksaan['sampel'];
  if (!in_array($sampel, $arr_csampel) and $sampel) array_push($arr_csampel, $sampel);

  // info progres pemeriksaan
  $tanggal_periksa = $arr_pemeriksaan_tanggal[$id_pemeriksaan] ?? '';
  $id_pemeriksa = $arr_pemeriksaan_by[$id_pemeriksaan] ?? '';
  $info_pemeriksaan = '<span class="f12 miring abu">belum menjalani pemeriksaan di bagian ini.</span>';
  if ($tanggal_periksa) {
    $jumlah_pemeriksaan_selesai++;
    $hari = hari_tanggal($tanggal_periksa);
    $info_pemeriksaan = "by <b class=darkblue>$arr_user[$id_pemeriksa]</b> pada  <b class=darkblue>$hari</b>";
  }

  # ============================================================
  # TR PROGRESS PEMERIKSAAN
  # ============================================================
  $tr_progress .= "
    <tr>
      <td>$no</td>
      <td class=kiri>$pemeriksaan[nama_pemeriksaan]</td>
      <td class=kiri>$info_pemeriksaan</td>
      <td>
        <a href='?pemeriksaan&id_pemeriksaan=$id_pemeriksaan&id_pasien=$id_pasien'>$img_next</a>
      </td>
    </tr>
  ";

  $tr_progress_mobile .= "
    <div class='wadah bg-white'>
      <div class=row>
        <div class=' col-lg-4'><div class='f12 abu miring'>$no</div> $pemeriksaan[nama_pemeriksaan]</div>
        <div class=' col-lg-8'>
          $info_pemeriksaan
          <div><a href='?pemeriksaan&id_pemeriksaan=$id_pemeriksaan&id_pasien=$id_pasien'>$img_next</a></div>
        </div>
      </div>
    </div>
    ";
}


$tr_sampel = '';
$tr_sampel_mobile = '';
$info_sampel = '<span class="f12 miring abu">belum ambil sampel</span>';
$no = 0;
$jumlah_sampel = count($arr_csampel);
if ($arr_csampel) {
  include 'include/arr_sampel.php';
  foreach ($arr_csampel as $sampel) {
    $no++;

    // info progres sampel
    $tanggal_periksa = $arr_sampel_tanggal[$sampel] ?? '';
    $id_pemeriksa = $arr_sampel_by[$sampel] ?? '';
    $info_sampel = '<span class="f12 miring abu">belum menjalani pemeriksaan di bagian ini.</span>';
    if ($tanggal_periksa) {
      $jumlah_sampel_selesai++;
      $hari = hari_tanggal($tanggal_periksa);
      $info_sampel = "by <b class=darkblue>$arr_user[$id_pemeriksa]</b> pada  <b class=darkblue>$hari</b>";
    }


    $tr_sampel .= "
      <tr>
        <td>$no</td>
        <td class=kiri>$arr_sampel[$sampel]</td>
        <td class=kiri>$info_sampel</td>
        <td>
          <a href='?pemeriksaan&ambil_sampel=1&sampel=$sampel&id_pasien=$id_pasien'>$img_next</a>
        </td>
      </tr>
    ";
    $tr_sampel_mobile .= "
    <div class='wadah bg-white'>
      <div class=row>
        <div class=' col-lg-4'><div class='f12 abu miring'>$no</div> $arr_sampel[$sampel]</div>
        <div class=' col-lg-8'>
          $info_sampel
          <div><a href='?pemeriksaan&ambil_sampel=1&sampel=$sampel&id_pasien=$id_pasien'>$img_next</a></div>
        </div>
      </div>
    </div>
    ";
  }
}

# ============================================================
# INFO SELESAI PEMERIKSAAN
# ============================================================
$info_selesai = '';
if ($jumlah_pemeriksaan_selesai == $jumlah_pemeriksaan and $jumlah_sampel_selesai == $jumlah_sampel and $jumlah_pemeriksaan) {
  if ($status == 9) {
    //update status pasien menjadi 10 (pasien selesai)
    $s2 = "UPDATE tb_pasien SET status=10 WHERE id='$id_pasien'";
    $q_pemeriksaan = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    jsurl();
  }
  $info_selesai =  "
    <div class='alert alert-success mt2'>
      Pasien telah menjalani semua pemeriksaan $img_check

      <div class=mt2>
        <a class='btn btn-primary' href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=mcu'>Hasil Pemeriksaan MCU</a>
      </div>
      <div class='wadah mt2'>
        <div class='mb2 mt1 abu'>Pemeriksaan Penunjang</div>
        $link_hasil_penunjang 
      </div>
    </div>
  ";
}


$status_show = $status ? "$arr_status_pasien[$status] ($status)" : '<span class="f12 red">Belum pernah login</span>';
$src = "$lokasi_pasien/$foto_profil";


# ============================================================
# FINAL ECHO PEM MCU
# ============================================================
?>
<style>
  .mobile_only {
    display: none;
  }

  @media (max-width: 550px) {
    .mobile_only {
      display: block;
    }

    .desktop_only {
      display: none;
    }
  }
</style>
<?php
echo "

  <div class='wadah tengah gradasi-hijau'>
    <div><a href='?cari_pasien'>$img_prev</a></div>
    <div><img src='$src' class='foto_profil'></div>
    <div class='mb1'>$gender_icon $pasien[nama_pasien]</div>
    <div class='border-bottom mb2 pb2 biru f12'>MCU-$id_pasien | $status_show</div>
    <h4 class=mb4>MCU $pasien[nama_paket] $pasien[jenis_pasien]</h4>
    <div class='flexy flex-center'>
      <div class=wadah>
        Sampel Pemeriksaan
        <table class='table table-striped table-hover mt4 desktop_only'>$tr_sampel</table>
        <div class='mt4 mobile_only tengah'>$tr_sampel_mobile</div>
      </div>
    </div>
    <div class='flexy flex-center'>
      <div class=wadah>
        Progress Pemeriksaan
        <table class='table table-striped table-hover mt4 desktop_only'>$tr_progress</table>
        <div class='mt4 mobile_only tengah'>$tr_progress_mobile</div>
        $info_selesai
      </div>
    </div>
  </div>
";

# ============================================================
# FINAL ECHO PASIEN
# ============================================================
echo "
  <div class='tengah mb4'><span class='btn_aksi bold f14 darkblue' id=tb_detail__toggle>$img_detail Info Detail Pasien</span></div>
  <div class='hideit border-top pt3' id=tb_detail>
    <table class='table '>
      $tr
    </table>
  </div>
";
