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
set_title("Update Status All Pasien");
only('admin');

$id_pemeriksaan_glu_pu = 44; // glu_pu
$id_pemeriksaan_glu_se = 46; // glu_se
$id_pemeriksaan_dl = 3; // glu_se


# ============================================================
# INCLUDES
# ============================================================
include 'include/arr_status_pasien.php';
include 'include/arr_user.php';




$s = "SELECT a.id as id_pasien,a.* FROM tb_pasien a 
WHERE a.status < 10 

";
$q_pasienz = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($pz = mysqli_fetch_assoc($q_pasienz)) {
  $id_pasien = $pz['id_pasien'];
  $order_no = $pz['order_no'];
  $id_harga_perusahaan = $pz['id_harga_perusahaan'];
  $jenis = $pz['jenis'];
  $status = $pz['status'];
  $JENIS = strtoupper($jenis);
  echo "<hr>Updating... $pz[nama] : status:$status";


  # ===========================================================
  # HASIL (IF EXISTS)
  # ===========================================================
  $arr_id_detail = [];
  $arr_pemeriksaan_tanggal = [];
  $arr_pemeriksaan_by = [];

  include 'pemeriksaan-hasil_at_db.php';


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
  if (!mysqli_num_rows($q_pemeriksaan)) {
    $tr_progress = div_alert('danger', "
      Paket ini belum punya List Pemeriksaan | 
      <a href='?assign_pemeriksaan&id_paket=$id_paket&nama_paket=$nama_paket'>
        Assign Pemeriksaan
      </a>
    ");
    $tr_progress_mobile = $tr_progress;
  }
  $link_hasil_penunjang = '';
  $jumlah_pemeriksaan_with_gula = 0;
  $ada_glu_pu = 0;
  $ada_glu_se = 0;
  while ($pemeriksaan = mysqli_fetch_assoc($q_pemeriksaan)) {
    $no++;
    $id_pemeriksaan = $pemeriksaan['id_pemeriksaan'];


    # ============================================================
    # EXCEPTION PEMERIKSAAN GULA
    # ============================================================
    $jumlah_pemeriksaan_with_gula++;
    if ($id_pemeriksaan == $id_pemeriksaan_glu_pu) {
      $ada_glu_pu = 1;
    }
    if ($id_pemeriksaan == $id_pemeriksaan_glu_se) {
      $ada_glu_se = 1;
    }
    if ($ada_glu_pu and $ada_glu_se) {
      $jumlah_pemeriksaan_with_gula--;
      $ada_glu_pu = 0;
      $ada_glu_se = 0;
    }


    $nama_pemeriksaan = $pemeriksaan['nama_pemeriksaan'];
    $jenis_pemeriksaan = $pemeriksaan['jenis_pemeriksaan'];
    $count_pemeriksaan_detail = $pemeriksaan['count_pemeriksaan_detail'];
    $status_bayar = $pemeriksaan['status_bayar'];
    if ($jenis == 'idv' and $status_bayar == '') {
      $buttons = div_alert('danger', "Pasien Individu ini belum melakukan Pembayaran. <hr><a class='btn btn-primary' href='?manage_paket_custom&id_pasien=$id_pasien'>Manage Paket</a>");
      exit;
    }

    if (strtolower($pemeriksaan['jenis']) != 'mcu') {
      $np_show = strtoupper($nama_pemeriksaan);
      if ($np_show == 'URINE LENGKAP') $np_show = 'Hasil MCU Urine';
      if ($np_show == 'DARAH LENGKAP') $np_show = 'Hasil MCU Darah';
      if ($np_show == 'RONTGEN (DADA)') $np_show = 'Hasil Rontgen';

      $link_hasil_penunjang .= " 
        <a class='btn btn-primary' href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$pemeriksaan[jenis]&id_pemeriksaan=$id_pemeriksaan'>
          $np_show
        </a>
      ";
    }

    // info progres pemeriksaan
    $tanggal_periksa = $arr_pemeriksaan_tanggal[$id_pemeriksaan] ?? '';
    $id_pemeriksa = $arr_pemeriksaan_by[$id_pemeriksaan] ?? '';
    $info_pemeriksaan = '<span class="f12 miring abu">belum menjalani pemeriksaan di bagian ini.</span>';
    if ($tanggal_periksa) {
      $jumlah_pemeriksaan_selesai++;
      $hari = hari_tanggal($tanggal_periksa);
      $info_pemeriksaan = "by <b class=darkblue>$arr_user[$id_pemeriksa]</b> pada  <b class=darkblue>$hari</b>";
    }
  }


  echo "<hr>jumlah_pemeriksaan_selesai$jumlah_pemeriksaan_selesai == jumlah_pemeriksaan$jumlah_pemeriksaan == jumlah_pemeriksaan_with_gula$jumlah_pemeriksaan_with_gula";
  // echo "<hr>";

  # ============================================================
  # INFO SELESAI PEMERIKSAAN
  # ============================================================
  $info_selesai = '';
  if ($jumlah_pemeriksaan_selesai == $jumlah_pemeriksaan_with_gula and  $jumlah_pemeriksaan_with_gula) {
    if ($status < 10 || $status == '') {
      //update status pasien menjadi 10 (pasien selesai)
      $s2 = "UPDATE tb_pasien SET status=10 WHERE id='$id_pasien'";
      echolog($s2);
      $q_pemeriksaan = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      // jsurl();
    }
  }
}
