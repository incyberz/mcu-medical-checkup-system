<?php
$mode = $_GET['mode'] ?? 'detail';
set_h2('Rekap Pemeriksaan', "List Mode | <a href='?rekap_perusahaan'>Rekap Perusahaan</a>");









# ============================================================
# SELECT PERUSAHAAN
# ============================================================
$id_perusahaan = $_GET['id_perusahaan'] ?? '';
if (!$id_perusahaan) {
  $s = "SELECT * FROM tb_perusahaan ORDER BY nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  $i = 0;
  while ($d9 = mysqli_fetch_assoc($q)) {
    $i++;
    $tr .= "
      <tr>
        <td>$i</td>
        <td>
          <a href='?rekap_perusahaan&id_perusahaan=$d9[id]'>
            $d9[nama] $img_next
          </a>  
        </td>
      </tr>
    ";
  }
  echo "<table class=table>$tr</table>";
  exit;
}












# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT 
a.*,
a.id as id_pasien,
a.nama as nama_pasien,
b.nama as jenis_pasien,
(SELECT nama FROM tb_status_pasien WHERE status=a.status) status_pasien, 
(
  SELECT CONCAT(p.singkatan,' | ',q.perusahaan) FROM tb_paket p 
  JOIN tb_order q ON p.id=q.id_paket 
  JOIN tb_pasien r ON q.order_no=r.order_no 
  WHERE r.id=a.id) info_paket,
(
  SELECT p.status_bayar FROM tb_paket_custom p WHERE a.id_paket_custom=p.id
  ) status_bayar,
(
  SELECT p.status_bayar FROM tb_pembayaran p WHERE p.id_pasien=a.id
  ) status_bayar_corporate_mandiri, 
(
  SELECT arr_tanggal_by FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) arr_tanggal_by,
(
  SELECT approv_date FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) approv_date

FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
JOIN tb_harga_perusahaan c ON a.id_harga_perusahaan=c.id 
WHERE (a.status = 10) -- SELESAI PEMERIKSAAN  
AND c.id_perusahaan=$id_perusahaan 
ORDER BY a.nama 
";

$q9 = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_rekap = mysqli_num_rows($q9);
$tr = '';
$i = 0;
$jumlah_verif = 0;
$nav = div_alert('info', "Belum ada Pasien yang Selesai Pemeriksaan | <a href='?cari_pasien'>Lobby Pasien</a>");
if (mysqli_num_rows($q9)) {
  while ($d9 = mysqli_fetch_assoc($q9)) {
    $i++;
    if ($d9['approv_date']) $jumlah_verif++;
    $jenis = strtolower($d9['jenis']);
    $status = $d9['status'];
    $id_pasien = $d9['id_pasien'];

    include 'pages/pemeriksaan-hasil_at_db.php';

    $tr .= "
      <tr>
        <td>$i</td>
        <td>$d9[nama_pasien]</td>
        <td>$d9[tanggal_lahir]</td>
        <td><span class=hideit>KELUHAN</span></td>
        <td><span class=hideit>TENSI</span>$arr_id_detail[7]/$arr_id_detail[8] mmHg</td>
        <td><span class=hideit>NADI</span>$arr_id_detail[72]</td>
        <td><span class=hideit>PERNAFASAN</span>$arr_id_detail[9]</td>
        <td><span class=hideit>SUHU</span>$arr_id_detail[148]</td>
        <td><span class=hideit>SATURASI OKSIGEN</span>$arr_id_detail[10]</td>
        <td><span class=hideit>IMT</span>$arr_id_detail[ZZZ]</td>
        <td><span class=hideit>LINGKAR PERUT</span>$arr_id_detail[6]</td>
        <td><span class=hideit>TINGGI BADAN</span>$arr_id_detail[2]</td>
        <td><span class=hideit>BERAT BADAN</span>$arr_id_detail[1]</td>
        <td><span class=hideit>MATA KANAN</span>$arr_id_detail[14]</td>
        <td><span class=hideit>MATA KIRI</span>$arr_id_detail[142]</td>
        <td><span class=hideit>BUTA WARNA</span>$arr_id_detail[11]</td>
        <td><span class=hideit>LAB-HEMA</span>$arr_id_detail[ZZZ]</td>
        <td><span class=hideit>LAB-URINE</span>$arr_id_detail[ZZZ]</td>
        <td><span class=hideit>RONTGEN</span>$arr_id_detail[ZZZ]</td>
        <td><span class=hideit>KESIMPULAN</span>$hasil_at_db[hasil]</td>
        <td><span class=hideit>KONSULTASI</span>$hasil_at_db[konsultasi]</td>
        <td><span class=hideit>REKOMENDASI</span>$hasil_at_db[rekomendasi]</td>        
      </tr>
    ";
  } // end while
}

$arr_head = [
  'NO. MCU',
  'NAMA',
  'TANGGAL LAHIR',
  'KELUHAN',
  'TEKANAN DARAH',
  'NADI',
  'PERNA FASAN',
  'SUHU',
  'SATURASI OKSIGEN',
  'IMT',
  'LINGKAR PERUT',
  'TINGGI BADAN',
  'BERAT BADAN',
  'MATA KANAN',
  'MATA KIRI',
  'BUTA WARNA',
  'LAB-HEMA',
  'LAB-URINE',
  'RONTGEN',
  'KESIMPULAN',
  'KONSULTASI',
  'REKOMENDASI',
];

$th = '';
foreach ($arr_head as $key => $value) {
  $th .= "<th>$value</th>";
}


?>
<style>
  .kertas {
    width: 29.7cm;
    height: 21cm;
    padding: 1cm;
    font-size: 9px;
  }

  .kertas td,
  .kertas th {
    padding: 5px;
  }
</style>
<?php
# ============================================================
# FINAL ECHO REKAP
# ============================================================
echo "
  <div class='wadah gradasi-hijau'>
    <div class='kertas bg-white'>
      <table class='table table-bordered'>
        $th
        $tr
      </table>
    </div>
  </div>
";
