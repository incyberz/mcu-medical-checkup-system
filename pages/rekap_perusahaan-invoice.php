<?php
if ($perusahaan['cara_bayar'] == 'bc' || $perusahaan['cara_bayar'] == 'bi') { // by corporate

  $s = "SELECT a.*,b.nama as nama_paket FROM tb_harga_perusahaan a 
  JOIN tb_paket b ON a.id_paket=b.id 
  WHERE a.id_perusahaan=$id_perusahaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    div_alert('danger', 'Data [harga_perusahaan] tidak ditemukan');
  } else {
    $harga_perusahaan = mysqli_fetch_assoc($q);
    $harga = $harga_perusahaan['harga'];
  }

  $tr = '';
  $total_bayar = 0;
  $i = 0;
  foreach ($arr_tanggal_periksa as $tanggal_periksa) {
    $i++;
    $s = "SELECT 1 FROM tb_pasien a
    JOIN tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id
    JOIN tb_hasil_pemeriksaan c ON a.id=c.id_pasien
    WHERE b.id_perusahaan=$id_perusahaan
    AND date(c.awal_periksa) = '$tanggal_periksa'";

    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $jumlah_pasien = mysqli_num_rows($q);

    $tgl = hari_tanggal($tanggal_periksa, 1, 0, 0);

    $jumlah_rp = $jumlah_pasien * $harga;
    $total_bayar += $jumlah_rp;

    $harga_show = number_format($harga);
    $jumlah_rp_show = number_format($jumlah_rp);


    $tr .= "
      <tr>
        <td>$i</td>
        <td>$tgl</td>
        <td>
          Biaya Medical Checkup Karyawan
          <div class='f14 miring mt1'>$harga_perusahaan[nama_paket]</div>
        </td>
        <td>$harga_show</td>
        <td>$jumlah_pasien</td>
        <td class=kanan>$jumlah_rp_show</td>
      </tr>
    ";
  }


  $hari = hari_tanggal($today, 1, 0, 0);
  $total_bayar_show = number_format($total_bayar);

  $fs = 'f14';

  echo "
    <div class='kiri border-top pt2 border-bottom pb2 $fs'>
      <div class=kanan>Bekasi, $hari</div>
      <div>Kepada Yth. <b>PIMPINAN $NAMA_PERUSAHAAN</b></div>
      <div class=mb4>di Tempat</div>
      <div class=mb2>Berikut adalah Invoice Medical Checkup dengan rincian:</div>
      <table class='table th_toska th_kiri td_trans'>
        <thead>
          <th>No</th>
          <th>Tanggal</th>
          <th>Uraian</th>
          <th>Biaya</th>
          <th>Jumlah Pasien</th>
          <th class=kanan>Jumlah Rp</th>
        </thead>
        $tr
        <tr style='background: #dff' class='bold'>
          <td colspan=5 class=kanan>
            TOTAL BAYAR
          </td>
          <td class=kanan>$total_bayar_show</td>
        </tr>
      </table>
    </div>
    <div style='margin-left: 12cm'>
      <div class='mt2 mb1 $fs'>Admin Mutiara Medical Center</div>
  ";

  include 'include/enkrip14.php';
  $z = enkrip14($id_perusahaan);

  require_once 'include/qrcode.php';
  $qr = QRCode::getMinimumQRCode("https://mmc-clinic.com/qr?$z", QR_ERROR_CORRECT_LEVEL_L);
  $qr->printHTML('3px');
  echo '</div>'; // end margin left xxx cm
} else {
  echo div_alert('danger', "BELUM ADA HANDLER INVOICE UNTUK CARA_BAYAR [$perusahaan[cara_bayar] ]");
}
