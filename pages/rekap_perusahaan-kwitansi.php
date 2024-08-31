<?php
include 'rekap_perusahaan-invoice.php';

$tanggal_periksa_header = $tanggal_periksa_header ? $tanggal_periksa_header : hari_tanggal($get_tanggal_periksa, 1, 1, 0);
$tmp = explode(',', $get_tanggal_periksa);
$arr_tgl = [];
foreach ($tmp as $tgl) {
  if ($tgl) {
    array_push($arr_tgl, $tgl);
  }
}

if (!$arr_tgl) {
  $arr_tgl = [$tanggal_periksa];
}
sort($arr_tgl);
$tgl1 = $arr_tgl[0];
$tgl2 = $arr_tgl[count($arr_tgl) - 1];

if ($tgl1 == $tgl2) {
  $tanggal_periksa_show = hari_tanggal($tgl1, 1, 1, 0);
} else {
  $tanggal_periksa_show = hari_tanggal($tgl1, 1, 0, 0) . ' s.d ' .  hari_tanggal($tgl2, 1, 0, 0);
}

$nama = $perusahaan['nama_kontak'];
if ($nama == 'HRD. HRD' || !trim($nama)) $nama = "HRD. $perusahaan[nama]";

echo "
<form action='kwitansi.php' method='post'>
  <table class='table gradasi-hijau td_trans table-striped'>
    <tr>
      <td>Telah terima dari</td>
      <td>:</td>
      <td>
        <input required name='nama' minlength='3' class='form-control form-control-sm' value='$nama'>
      </td>
    </tr>
    <tr>
      <td>Uang sejumlah</td>
      <td>:</td>
      <td>
        <input required min='10000' name='nominal' class='form-control form-control-sm' value='$total_bayar'>
      </td>
    </tr>
    <tr>
      <td>Untuk Pembayaran</td>
      <td>:</td>
      <td>
        <textarea name='untuk_pembayaran' class='form-control form-control-sm'>Biaya Medical Checkup Karyawan Paket MCU Karyawan (Basic)</textarea>
      </td>
    </tr>
    <tr>
      <td>Total Pasien</td>
      <td>:</td>
      <td>
        <input readonly name='total_pasien' class='form-control form-control-sm' value='$total_pasien'>
      </td>
    </tr>
    <tr>
      <td>Tanggal Pemeriksaan</td>
      <td>:</td>
      <td>
        <input required name='tanggal_periksa' class='form-control form-control-sm' value='$tanggal_periksa_show'>
      </td>
    </tr>
    <tr>
      <td>Printed by</td>
      <td>:</td>
      <td>$nama_user</td>
    </tr>
  </table>

  <input type='hidden' name='id_perusahaan' value='$id_perusahaan'>
  <input type='hidden' name='nama_kasir' value='$nama_user'>
  <div class=tengah>
    <button class='btn btn-primary'>Cetak Kwitansi</button>
  </div>
</form>
";

?>
<script>
  $(function() {
    $('#btn_print').hide();
  })
</script>