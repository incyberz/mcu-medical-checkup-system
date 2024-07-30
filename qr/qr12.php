<?php
$j = 0;
$id_pasien = '';
$id_pemeriksaan = '';
for ($i = 0; $i < strlen($parameter); $i++) {
  $j++;
  // if ($j % 2 == 0) echo '<hr>' . substr($parameter, $i, 1);
  if ($j % 2 == 0) $id_pasien .= substr($parameter, $i, 1);
  if ($j % 2 != 0) $id_pemeriksaan .= substr($parameter, $i, 1);
}

// echo '<hr>id_pasien: ';
// echo intval($id_pasien);

// echo '<hr>id_pemeriksaan: ';
// echo intval($id_pemeriksaan);

# ============================================================
# XXXDDD => DDD = id_pemeriksaan
# ============================================================
$id_pemeriksaan = intval(substr($id_pemeriksaan, 3, 3));
// echo "<hr>id_pemeriksaan: $id_pemeriksaan";

include '../conn.php';
$s = "SELECT awal_periksa FROM tb_hasil_pemeriksaan 
WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)) {

  $d = mysqli_fetch_assoc($q);
  $awal_periksa = $d['awal_periksa'];

  $s = "SELECT 
  a.nama as nama_pemeriksaan, 
  b.nama as jenis_pemeriksaan 
  FROM tb_pemeriksaan a 
  JOIN tb_jenis_pemeriksaan b ON a.jenis=b.jenis 
  WHERE a.id=$id_pemeriksaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $d = mysqli_fetch_assoc($q);
    $nama_pemeriksaan = $d['nama_pemeriksaan'];
    $jenis_pemeriksaan = $d['jenis_pemeriksaan'];
  } else {
    $is_valid = 0;
  }
} else {
  $is_valid = 0;
}
