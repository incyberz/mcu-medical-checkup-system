<?php
# ===========================================
# HEADER ALL
# ===========================================
$pdf->Cell(0, 2, ' ', '', 1, ''); // spacer top
$pdf->SetFont(FF, '', FS);

$awal_periksa_show = hari_tanggal($awal_periksa, 1, 0, 0);
$tanggal_lahir_show = hari_tanggal($pasien['tanggal_lahir'], 1, 0, 0);
$gender = $pasien['gender'] ?  ucwords(gender($pasien['gender'])) : '-';


$arr_header = [
  1 => ['Penanggung Jawab', $dokter_pj,         'Dokter Pengirim', $dokter_pengirim],
  2 => ['No. MCU',          $no_mcu,            'No. RM', $no_rm],
  3 => ['Tgl Pemeriksaan',  $awal_periksa_show, 'Nama Pasien', $pasien['nama']],
  4 => ['N.I.K',            $pasien['nikepeg'], 'Tanggal Lahir', $tanggal_lahir_show],
  5 => ['Alamat',           $alamat_trim,       'Jenis Kelamin', $gender],
];
$header_width = [27, 61, 27, 61]; // table column width
foreach ($arr_header as $k => $v) {
  $pdf->Cell($header_width[0], LH, $v[0], 0, 0, '');
  $pdf->Cell(3, LH, ':', 0, 0, '');
  $pdf->Cell($header_width[1], LH, $v[1], 0, 0, '');
  $pdf->Cell(6, LH, ' ', 0, 0, '');
  $pdf->Cell($header_width[2], LH, $v[2], 0, 0, '');
  $pdf->Cell(3, LH, ':', 0, 0, '');
  $pdf->Cell($header_width[3], LH, $v[3], '', 1, '');
}

$pdf->Cell(0, 2, ' ', 'B', 1, ''); // border bottom
$pdf->Cell(0, 6, ' ', '', 1, ''); // spacer
