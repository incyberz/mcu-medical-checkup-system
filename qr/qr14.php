<?php
include '../include/enkrip14.php';
$id_perusahaan = dekrip14($parameter);

include '../conn.php';
$s = "SELECT 
a.nama as nama_perusahaan,
d.awal_periksa 
FROM tb_perusahaan a 
JOIN tb_harga_perusahaan b ON a.id=b.id_perusahaan 
JOIN tb_pasien c ON c.id_harga_perusahaan=b.id 
JOIN tb_hasil_pemeriksaan d ON d.id_pasien=c.id 
WHERE a.id=$id_perusahaan 
ORDER BY d.awal_periksa DESC 
LIMIT 1
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {
  $d = mysqli_fetch_assoc($q);

  $nama_perusahaan = strtoupper($d['nama_perusahaan']);
  $nama_pemeriksaan = $nama_perusahaan;
  $jenis_pemeriksaan = 'MCU CORPORATE';
  $awal_periksa = $d['awal_periksa'];
} else {
  $is_valid = 0;
}
