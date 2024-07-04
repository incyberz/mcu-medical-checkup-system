<?php
set_h2('Pendaftaran', 'Berikut adalah Data Pendaftaran Pasien');
only(['admin', 'nakes']);


?>

<h3>Dashboard Pendaftaran</h3>
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
</table>