<?php
$tanggal_awal = '2024-7-1';
$bln = $arr_nama_bulan[intval(date('m', strtotime($tanggal_awal))) - 1];
$thn = date('Y', strtotime($tanggal_awal));
$img_filter = img_icon('filter');
set_h2('Pendaftaran', "Data Pendaftaran Pasien <b class=darkblue>$bln $thn</b> $img_filter");
only(['admin', 'nakes', 'marketing']);


?>

<!-- <h3>Dashboard Pendaftaran</h3>
<ul>
  <li>KUNJUNGAN HARIAN</li>
  <li>KUNJUNGAN BULANAN</li>
  <li>PER PAKET</li>
  <li>PER PROGRAM</li>
  <li>PER JAMINAN</li>
  <li>TOP 10 ITEM</li>
  <li>TOP 10 PERUSAHAAN</li>
</ul>

<h3>Data Pasien</h3>
<div class="wadah">
  <div>Pencarian</div>

  <input type="text" class="form-control" placeholder="keyword...">

</div>

<h4>List Table Pasien</h4>
<table class="table">
  <thead>
    <th>No</th>
    <th>No-MCU</th>
    <th>Pasien</th>
    <th>Pemeriksaan</th>
    <th>Aksi</th>
  </thead>
  <tr>
    <td>No</td>
    <td>No-MCU</td>
    <td>Pasien</td>
    <td>Pemeriksaan</td>
    <td>Aksi</td>
  </tr>
  <tr>
    <td>No</td>
    <td>No-MCU</td>
    <td>Pasien</td>
    <td>Pemeriksaan</td>
    <td>Aksi</td>
  </tr>
</table> -->


<?php

require_once 'include/mcu_functions.php';
require_once 'include/radio_jenis_pasien.php';
// require_once 'tambah_pasien.php';

$s = "SELECT
a.nama ,
a.jenis ,
b.nama as jenis_pasien,
a.date_created as tanggal_daftar,
(SELECT nama FROM tb_status_pasien WHERE status=a.status) status_pasien

FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
WHERE a.date_created > '$tanggal_awal'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '<th>No</th>';
  $jenis['bpj'] = 0;
  $jenis['idv'] = 0;
  $jenis['cor'] = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $jenis[$d['jenis']]++;

    $td = "<td>$i</td>";
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'jenis'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }

      if ($key == 'status_pasien') {
        $value = $value ?? '<i class="f14 abu">baru didaftarkan</i>';
      } elseif ($key == 'tanggal_daftar') {
        $value = hari_tanggal($value);
      }

      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        $td
        <td>
          $img_edit 
          $img_delete 
        </td>
      </tr>
    ";
  }
}

$tb_pasien = $tr ? "
  <table class=table>
    <thead>$th<th>Aksi</th></thead>
    $tr
  </table>
" : div_alert('danger', "Data pasien tidak ditemukan.");



# ============================================================
# DASHBOARD
# ============================================================
$dashboard = "
<div class='row tengah'>
  <div class='col-4'>
    <div class='wadah gradasi-toska'>
      <div class='f20 darkblue'>BPJS</div>
      <div class='f40'>$jenis[bpj]</div>
    </div>
  </div>
  <div class='col-4'>
    <div class='wadah gradasi-toska'>
      <div class='f20 darkblue'>Individu</div>
      <div class='f40'>$jenis[idv]</div>
    </div>
  </div>
  <div class='col-4'>
    <div class='wadah gradasi-toska'>
      <div class='f20 darkblue'>Corporate</div>
      <div class='f40'>$jenis[cor]</div>
    </div>
  </div>
</div>
";

echo "
  $dashboard
  <div class='tengah'><a class='btn btn-success' href='?tambah_pasien'>$img_add Tambah Pasien</a></div>
  <div class='tengah mt4 mb2'><span class=btn_aksi id=tb_pasien__toggle>$img_detail</span></div>
  <div class=hideit id=tb_pasien>$tb_pasien</div>
";
