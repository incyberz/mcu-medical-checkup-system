<?php
$s = "SELECT MIN(DATE(date_created)) as tanggal_awal FROM tb_pasien  ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$tanggal_awal = $d['tanggal_awal'];
$bulan_awal = intval(date('m', strtotime($tanggal_awal)));
$nama_bulan_awal = $arr_nama_bulan[intval(date('m', strtotime($tanggal_awal))) - 1];
$tahun_awal = date('Y', strtotime($tanggal_awal));

$bulan_skg = intval(date('m', strtotime($today)));
$get_bulan = $_GET['bulan'] ?? $bulan_skg;
$get_tahun = $_GET['tahun'] ?? $tahun;
$nama_bulan = $arr_nama_bulan[$bulan_skg - 1];
$nama_bulan_filtered = $arr_nama_bulan[$get_bulan - 1];
$img_filter = img_icon('filter');
set_h2('Pendaftaran', "
  Data Pendaftaran Pasien <b class=darkblue>$nama_bulan_filtered $get_tahun</b> 
  <span class=btn_aksi id=form_filter__toggle>$img_filter</span>

  <form method=post id=form_filter class='wadah gradasi-kuning mt4 hideit'>
    <div class='wadah tengah f14'>
      2024
      <div class='flexy flex-center mt2'>
        <div><a class='btn btn-sm btn-info' href='?pendaftaran&bulan=7&tahun=2024'>Jul</a></div>
        <div><a class='btn btn-sm btn-info' href='?pendaftaran&bulan=8&tahun=2024'>Ags</a></div>
        <div><a class='btn btn-sm btn-info' href='?pendaftaran&bulan=9&tahun=2024'>Sep</a></div>
      </div>
    </div>
  </form>
");
only(['admin', 'nakes', 'marketing', 'dokter', 'dokter-pj']);



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
// require_once 'include/radio_jenis_pasien.php';
// require_once 'tambah_pasien.php';





# ============================================================
# DASHBOARD
# ============================================================
$jenis['BPJ'] = 0;
$jenis['IDV'] = 0;
$jenis['COR'] = 0;
require_once 'data_pasien.php';

$dashboard = "
<div class='row tengah'>
  <div class='col-4'>
    <div class='wadah gradasi-toska'>
      <div class='f20 darkblue'>BPJS</div>
      <div class='f40'>$jenis[BPJ]</div>
    </div>
  </div>
  <div class='col-4'>
    <div class='wadah gradasi-toska'>
      <div class='f20 darkblue'>Individu</div>
      <div class='f40'>$jenis[IDV]</div>
    </div>
  </div>
  <div class='col-4'>
    <div class='wadah gradasi-toska'>
      <div class='f20 darkblue'>Corporate</div>
      <div class='f40'>$jenis[COR]</div>
    </div>
  </div>
</div>
";

echo "
  $dashboard
  <div class='tengah'>
    <a class='btn btn-success' href='?tambah_pasien'> $img_add Pasien Individu</a>
    <a class='btn btn-success' href='?tambah_pasien_cor'> $img_add Pasien Corporate</a>
  </div>
  <div class='tengah mt4 mb2'><span class=btn_aksi id=tb_pasien__toggle>$img_detail</span></div>
  <div class=hideita id=tb_pasien>$data_pasien</div>
";
