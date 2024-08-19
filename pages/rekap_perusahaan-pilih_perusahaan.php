<?php
set_h2('Pilih Perusahaan');
$s = "SELECT 
a.id,
a.nama,
(
  SELECT p.awal_periksa FROM tb_hasil_pemeriksaan p
  JOIN tb_pasien q ON p.id_pasien = q.id 
  JOIN tb_harga_perusahaan r ON q.id_harga_perusahaan=r.id 
  WHERE r.id_perusahaan=a.id 
  ORDER BY p.awal_periksa DESC LIMIT 1
  ) last_active,
(
  SELECT p.awal_periksa FROM tb_hasil_pemeriksaan p
  JOIN tb_pasien q ON p.id_pasien = q.id 
  JOIN tb_order r ON q.order_no=r.order_no  
  WHERE r.id_perusahaan=a.id 
  ORDER BY p.awal_periksa DESC LIMIT 1
  ) last_active_corporate
FROM tb_perusahaan a 
ORDER BY last_active DESC, a.nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = 0;
while ($pasien = mysqli_fetch_assoc($q)) {
  if ($pasien['last_active']) {
    if (strtotime($pasien['last_active']) < strtotime('-1 years')) continue;
    $last_active_show = hari_tanggal($pasien['last_active']) . ' | ' . eta2($pasien['last_active']);
  }
  if ($pasien['last_active_corporate']) {
    if (strtotime($pasien['last_active_corporate']) < strtotime('-1 years')) continue;
    $last_active_show = hari_tanggal($pasien['last_active_corporate']) . ' | ' . eta2($pasien['last_active_corporate']);
  }
  if (!$pasien['last_active'] && !$pasien['last_active_corporate']) continue;
  $i++;

  $tr .= "
    <tr>
      <td>$i</td>
      <td>
        <a href='?rekap_perusahaan&id_perusahaan=$pasien[id]&mode=$mode'>
          $pasien[nama] $img_next
        </a>  
      </td>
      <td>$last_active_show</td>
    </tr>
  ";
}
echo "
  <table class='table th_toska'>
    <thead>
      <th>No</th>
      <th>Perusahaan</th>
      <th>Last Active $img_filter</th>
    </thead>
    $tr
  </table>
";
