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
  <div class='tengah'><a class='btn btn-success' href='?tambah_pasien'> Tambah Pasien</a></div>
  <div class='tengah mt4 mb2'><span class=btn_aksi id=tb_pasien__toggle>$img_detail</span></div>
  <div class=hideita id=tb_pasien>$data_pasien</div>
";
