<?php
set_title('Monitoring Pasien');

$s = "SELECT 
a.id,
a.nama,
a.foto_profil,
a.no_ktp as biodata,
a.tanggal_mengisi_gejala_penyakit as gejala_penyakit,
a.tanggal_mengisi_riwayat_penyakit as riwayat_penyakit,
a.tanggal_mengisi_gaya_hidup as gaya_hidup,
a.tanggal_mengisi_keluhan as keluhan,
c.id as id_paket,
c.nama as nama_paket,
c.singkatan as singkatan_paket,
(SELECT awal_periksa FROM tb_hasil_pemeriksaan WHERE id_pasien=a.id) awal_periksa 

FROM tb_pasien a 
JOIN tb_order b ON a.order_no=b.order_no 
JOIN tb_paket c ON b.id_paket=c.id 
WHERE b.id_perusahaan=$id_perusahaan 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$total_pasien = mysqli_num_rows($q);
$jumlah_pasien_sudah_periksa = 0;

if (!mysqli_num_rows($q)) {
  div_alert('danger', "Data pasien $perusahaan[nama] belum ada.");
} else {
  $tr = '';
  $i = 0;
  $paket = [];
  $koloms = [];
  $arr_sudah = [];
  $arr_mengisi = [
    'foto_profil',
    'biodata',
    'riwayat_penyakit',
    'gejala_penyakit',
    'gaya_hidup',
    'keluhan',
  ];
  $th_pem = '';
  $th_sudah_mengisi = '';
  $count = [];
  foreach ($arr_mengisi as $v) {
    $count[$v] = 0;
  }


  while ($pasien = mysqli_fetch_assoc($q)) {
    $i++;
    $arr_hasil = [];
    $bg = 'gradasi-merah';
    if ($pasien['awal_periksa']) {
      $bg = '';
      $jumlah_pasien_sudah_periksa++;
      $s2 = "SELECT * FROM tb_hasil_pemeriksaan WHERE id_pasien=$pasien[id]";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $hasil = mysqli_fetch_assoc($q2);
      $arr = explode('||', $hasil['arr_tanggal_by']);
      foreach ($arr as $v) {
        if ($v) {
          $arr2 = explode('=', $v);
          $id_pemeriksaan = $arr2[0];
          $arr_hasil[$id_pemeriksaan] = $arr2[1];
        }
      }
    }
    if ($i == 1) {
      // create header dan get data paket
      $s2 = "SELECT * FROM tb_paket WHERE id=$pasien[id_paket]";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
      $paket = mysqli_fetch_assoc($q2);
    }
    $s2 = "SELECT *,
    b.id as id_pemeriksaan 
    FROM tb_paket_detail a 
    JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id 
    WHERE a.id_paket=$pasien[id_paket] 
    ORDER BY b.nomor
    ";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));

    $td_pem = '';
    while ($d2 = mysqli_fetch_assoc($q2)) {
      $sudah = array_key_exists($d2['id'], $arr_hasil) ? $img_check : 'blm';
      $id_pemeriksaan = $d2['id_pemeriksaan'];
      if (!isset($count[$id_pemeriksaan])) $count[$id_pemeriksaan] = 0;
      if (array_key_exists($d2['id'], $arr_hasil)) $count[$id_pemeriksaan]++;


      if ($i == $total_pasien) {
        $persen = round($count[$id_pemeriksaan] * 100 / $total_pasien);
        $th_pem .= "<th>$d2[singkatan] <div class='f10'>$count[$id_pemeriksaan] <span class=f8>of $total_pasien</span> ($persen%)</div></th>";
      }

      $td_pem .= "<td>$sudah</td>";
    }

    $td_sudah_mengisi = '';
    foreach ($arr_mengisi as $value) {
      $kolom = key2kolom($value);
      $sudah = $pasien[$value] ? $img_check : 'blm';
      if ($pasien[$value]) $count[$value]++;
      $td_sudah_mengisi .= "<td>$sudah</td>";
      if ($i == $total_pasien) {
        $persen = round($count[$value] * 100 / $total_pasien);
        $th_sudah_mengisi .= "<th>$kolom <div class='f10'>$count[$value] <span class=f8>of $total_pasien</span> ($persen%)</div></th>";
      }
    }
    $tr .= "
      <tr class='$bg'>
        <td>$i</td>
        <td>mcu-$pasien[id]</td>
        <td>$pasien[nama]</td>
        $td_sudah_mengisi
        $td_pem
      </tr>
    ";
  }

  echo "
    <h3>Sudah Pemeriksaan: $jumlah_pasien_sudah_periksa of $total_pasien</h3>
    <div style='height:70vh; overflow-y:scroll; position:relative; ' class='gradasi-hijau'>
      <table class='table th_toska td_trans f12'>
        <thead style='position:sticky;top:0'>
          <th>No</th>
          <th>No.MCU</th>
          <th>Nama</th>
          $th_sudah_mengisi
          $th_pem
        </thead>
        $tr
      </table>
    </div>
  ";
}
