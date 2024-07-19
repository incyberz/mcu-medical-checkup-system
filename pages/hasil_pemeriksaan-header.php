<?php
$div_header = '';
$tgl_pemeriksaan =  $arr_pemeriksaan_tanggal[$id_pemeriksaan] ?? $hasil['awal_periksa'];

$arr = [];
$arr[1] = ['Penanggung Jawab', 'dr. Mutiara Putri Camelia', 'Dokter', 'Dokter MCU'];
$arr[2] = ['No. Lab', 'MCU' . date('y', strtotime($pasien['date_created'])) . "-$id_pasien", 'No. RM', '-'];
$arr[3] = ['Tgl Pemeriksaan', hari_tanggal($tgl_pemeriksaan, 0, 0, 1, 0, '-'), 'Nama Pasien', ucwords(strtolower($pasien['nama']))];
$arr[4] = ['Ruang / Poli', '-', 'Tanggal Lahir', hari_tanggal($pasien['tanggal_lahir'], 1, 0, 0)];
$arr[5] = ['No. KTP', $pasien['no_ktp'] ?? '-', 'Jenis Kelamin', ucwords(gender($pasien['gender']))];
$arr[6] = ['Alamat', $pasien['alamat'] ?? '-', null, null];

foreach ($arr as $key => $v) {
  $titik_dua = (!$v[2] || $v[2] == 'nbsp;') ? '' : ':';
  $div_header .= "
    <div class=kolom_header>$v[0]</div>
    <div>:</div>
    <div>$v[1]</div>
    <div>&nbsp;</div>
    <div class=kolom_header>$v[2]</div>
    <div>$titik_dua</div>
    <div>$v[3]</div>
  ";
}
$div_header = "
  <div class='border-top border-bottom pt1 pb1 kiri f12'>
    <div style='display: grid; grid-template-columns: 20% 2% 40% 1% 20% 2% 20%'>
      $div_header
    </div>
  </div>
";
