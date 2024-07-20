<?php
$img_check_pink = img_icon('check_pink');

$s = "SELECT * FROM tb_bagian_tubuh ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
while ($d = mysqli_fetch_assoc($q)) {
  $s2 = "SELECT * FROM tb_pemeriksaan_detail WHERE bagian='$d[bagian]' AND id_pemeriksaan=8";
  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  $hasil = '';
  $kelainan = '';
  while ($d2 = mysqli_fetch_assoc($q2)) {
    $id_detail = $d2['id'];
    $nama_pemeriksaan = trim($d2['label']);
    $koma = $hasil ? '; ' : ' ';
    $c_hasil = "$koma$nama_pemeriksaan: $arr_id_detail[$id_detail]";

    $option_default = strtolower($arr_pemeriksaan_detail[$id_detail]['option_default']);

    if ($option_default) {
      if ($option_default == $arr_id_detail[$id_detail]) {
        // $kelainan = '';
      } else {
        $kelainan = $img_check_pink; // "$nama_pemeriksaan: $arr_id_detail[$id_detail]";
        // $c_hasil = '';
      }
    }

    $hasil .= $c_hasil;

    // $kelainan = "$option_default == $arr_id_detail[$id_detail]";
  }

  $img = $kelainan == '' ? $img_check : ''; // $img_warning;

  $tr .= "
    <tr>
      <td>$d[bagian]</td>
      <td>$img</td>
      <td>$hasil</td>
      <td>$kelainan</td>
    </tr>
  ";
}

$tb_hasil = "
  <table class=table>
    <thead>
      <th>Bagian</th>
      <th>Normal</th>
      <th>Deskripsi</th>
      <th>Kelainan</th>
    </thead>
    $tr
  </table>
";

blok_hasil('PEMERIKSAAN FISIK DOKTER', $tb_hasil);
