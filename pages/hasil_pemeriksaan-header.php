<?php
$div_header = '';
$tgl_pemeriksaan =  $arr_pemeriksaan_tanggal[$id_pemeriksaan] ?? $hasil['awal_periksa'];

if ($pasien['nikepeg']) $pasien['no_ktp'] = '';

$rh = [
  'Penanggung Jawab' => 'dr. Mutiara Putri Camelia',
  'No. Lab' => 'MCU' . date('y', strtotime($pasien['date_created'])) . "-$id_pasien",
  'Tgl Pemeriksaan' => hari_tanggal($tgl_pemeriksaan, 0, 0, 1, 0, '-'),
  'Instansi' => $pasien['perusahaan'],
  'No. KTP' => $pasien['no_ktp'],
  'N.I.K' => $pasien['nikepeg'],
  'Alamat' => $pasien['alamat'] ?? '-',
  'Dokter Pengirim' => 'Dokter MCU',
  'No. RM' => '-',
  'Nama Pasien' => ucwords(strtolower($pasien['nama'])),
  'Tanggal Lahir' => hari_tanggal($pasien['tanggal_lahir'], 1, 0, 0),
  'Jenis Kelamin' => ucwords(gender($pasien['gender'])),
];

$div_header = [];
$div_header['ki'] = [];
$div_header['ka'] = [];
$i = 0;
foreach ($rh as $col => $isi) {
  $i++;
  if ($isi) {
    $kika = $i > ((count($rh) / 2) + 1) ? 'ka' : 'ki';
    array_push($div_header[$kika], "
      <div>$col</div>
      <div>:</div>
      <div>$isi</div>
    ");
  }
}

$str_header['ki'] = '';
$str_header['ka'] = '';
$r = ['ki', 'ka'];
foreach ($r as  $kika) {
  foreach ($div_header[$kika] as $col => $isi) {
    $str_header[$kika] .= $isi;
  }
}

$div_header = "
  <div class='border-top border-bottom pt1 pb1 kiri f12'>
    <div class=row>
      <div class=col-7>
        <div style='display: grid; grid-template-columns: 30% 15px auto'>
          $str_header[ki]
        </div>
      </div>
      <div class=col-5>
        <div style='display: grid; grid-template-columns: 36% 15px auto'>
          $str_header[ka]
        </div>
      </div>
    </div>
  </div>
";

// $arr = [];
// $arr[1] = ['Penanggung Jawab', 'dr. Mutiara Putri Camelia', 'Dokter Pengirim', 'Dokter MCU'];
// $arr[2] = ['No. Lab', 'MCU' . date('y', strtotime($pasien['date_created'])) . "-$id_pasien", 'No. RM', '-'];
// $arr[3] = ['Tgl Pemeriksaan', hari_tanggal($tgl_pemeriksaan, 0, 0, 1, 0, '-'), 'Nama Pasien', ucwords(strtolower($pasien['nama']))];
// $arr[4] = ['Instansi', 'ZZZ', 'Tanggal Lahir', hari_tanggal($pasien['tanggal_lahir'], 1, 0, 0)];
// $arr[5] = ['No. KTP', $pasien['no_ktp'] ?? '-', 'Jenis Kelamin', ucwords(gender($pasien['gender']))];
// $arr[6] = ['Alamat', $pasien['alamat'] ?? '-', null, null];

// foreach ($arr as $key => $v) {
//   $titik_dua = (!$v[2] || $v[2] == 'nbsp;') ? '' : ':';
//   $div_header .= "
//     <div class=kolom_header>$v[0]</div>
//     <div>:</div>
//     <div>$v[1]</div>
//     <div>&nbsp;</div>
//     <div class=kolom_header>$v[2]</div>
//     <div>$titik_dua</div>
//     <div>$v[3]</div>
//   ";
// }
// $div_header = "
//   <div class='border-top border-bottom pt1 pb1 kiri f12'>
//     <div style='display: grid; grid-template-columns: 20% 2% 40% 1% 20% 2% 20%'>
//       $div_header
//     </div>
//   </div>
// ";
