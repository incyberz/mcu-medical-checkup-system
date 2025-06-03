<?php
$id_pemeriksaan = $id_pemeriksaan_kd;
$nama_pemeriksaan = 'KIMIA DARAH';
# ===========================================
# HEADER
# ===========================================
$pdf->AddPage(); // header always in new page
$pdf->SetFont(FF, 'B', 12);
$pdf->Cell(0, 10, "HASIL $nama_pemeriksaan", 0, 1, 'C');
$pdf->Cell(0, 2, ' ', 'B', 1, 'C'); //border bottom

include 'pdf-header.php';

$pdf->SetFont(FF, 'B', 10);
$pdf->SetTextColor(0, 200, 200);
$pdf->Cell(0, LHB, 'K I M I A   D A R A H ', '-', 1, 'L');

$pdf->SetFont(FF, '', 8);
$s = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=$id_pemeriksaan";

# ============================================================
# KHUSUS BEN MAKMUR
# ============================================================
if ($id_perusahaan == 41) {
  $s = "SELECT * FROM tb_pemeriksaan_detail 
  WHERE id_pemeriksaan=43 -- ASAM_URAT
  OR id_pemeriksaan=46 -- GLUKOSA_SEWAKTU
  OR id_pemeriksaan=35 -- CHOLESTEROL_TOTAL
  ";
}




// echo '<pre>';
// print_r($id_perusahaan);
// print_r($s);
// echo '</pre>';
// exit;
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  $pdf->SetTextColor(255, 0, 0);
  $pdf->Cell(0, LHB, "Belum ada detail pemeriksaan pada pemeriksaan [$nama_pemeriksaan]", 'TLRB', 1, 'L');
} else {

  $koloms = [];
  include 'pdf-lab-table_header.php';

  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(230, 230, 230);
  $pdf->SetFont(FF, '', FS);
  $i = 0;
  $j = 0;
  $last_fungsi = '';
  while ($d = mysqli_fetch_assoc($q)) {
    # ============================================================
    # SHOW FUNGSI PEMERIKSAAN IF EXISTS
    # ============================================================
    if ($d['fungsi_pemeriksaan']) {
      // $pdf->SetTextColor(0, 0, 255);
      // $pdf->Cell($d['fungsi_pemeriksaan'], LH, '', '-', $ln, 'L', 0);
      // $pdf->SetTextColor(0, 0, 0);
      // sudah di handle
    }

    $i++;
    $fill = $i % 2 == 0 ? 1 : 0;
    $id_detail = $d['id'];
    $label = strtolower($d['label']);
    $hasil = $arr_id_detail[$id_detail] ?? null;
    if ($hasil === null || $hasil === '') continue;

    $nilai_normal = $d['normal_value'] ?? 'RANGE';
    $beda_by_gender = 0;
    $hl = '-';
    if ($nilai_normal != 'RANGE') {
      // H/L non RANGE
      $hl = $nilai_normal == $hasil ? $hl : 'ABNOR';
    } else {
      $normal_lo_l = floatval($d['normal_lo_l']);
      $normal_hi_l = floatval($d['normal_hi_l']);
      $normal_lo_p = floatval($d['normal_lo_p']);
      $normal_hi_p = floatval($d['normal_hi_p']);
      $nilai_normal = "$normal_lo_l-$normal_hi_l";
      if ($normal_hi_p and $normal_lo_p) {
        $beda_by_gender = 1;
        $nilai_normal = "L($nilai_normal), P($normal_hi_p-$normal_lo_p)";
      }
      // kalkulasi H/L RANGE
      $normal_hi = $normal_hi_l;
      $normal_lo = $normal_lo_l;
      if ($beda_by_gender and $gender == 'p') {
        $normal_hi = $normal_hi_p;
        $normal_lo = $normal_lo_p;
      }

      // exception
      if ($label == 'leukosit') $hasil = str_replace('0-', '', $hasil);

      if ($hasil < $normal_lo) $hl = 'LOW';
      if ($hasil > $normal_hi) $hl = 'HIGH';
    }
    $satuan = $d['satuan'] ?? '-';

    if ($label == 'leukosit' || $label == 'eritrosit') $hasil = "0-$hasil";

    # ============================================================
    # SUB HEADER
    # ============================================================
    if ($last_fungsi != $d['fungsi_pemeriksaan']) {
      // $pdf->SetFont(FF, '', 10);
      // $pdf->Cell(0, LHB, $d['fungsi_pemeriksaan'], '-', 1, 'L');
      $pdf->SetFont(FF, '', 10);
      $pdf->SetTextColor(0, 200, 200);
      $pdf->Cell(0, 4, '  ', '-', 1, 'L'); // spacer
      $pdf->Cell(0, LHB, $d['fungsi_pemeriksaan'], '-', 1, 'L');

      // include 'pdf-lab-table_header.php';

      $pdf->SetFont(FF, '', FS);
      $pdf->SetTextColor(0, 0, 0);
      $last_fungsi = $d['fungsi_pemeriksaan'];
    }


    $arr = [
      'PEMERIKSAAN' => $d['label'],
      'HASIL' => $hasil,
      'H/L' => $hl,
      'SATUAN' => $satuan,
      'NILAI NORMAL' => $nilai_normal,
    ];

    # ============================================================
    # INTI OUTPUT 
    # ============================================================
    foreach ($arr as $k => $v) {
      $fill = 1;
      $ln = $k == 'NILAI NORMAL' ? $ln1 : $ln0;
      $pdf->Cell($koloms[$k], LH, $v, '-', $ln, 'L', $fill);
    }
  }
}

$pdf->Cell(0, 2, ' ', 'B', $ln1, 'L');
$pdf->Cell(0, 2, ' ', '-', $ln1, 'L');
$pdf->Cell(0, LH, 'Catatan: ', '-', $ln1, 'L');
$pdf->Cell(0, LH, 'Hasil Ini harus di interpretasikan oleh dokter yang menangani untuk disesuaikan dengan klinisnya. ', '-', $ln1, 'L');
$pdf->Cell(0, 2, ' ', 'B', $ln1, 'L'); // border bottom

$pdf->Cell(0, 2, ' ', '-', $ln1, 'L'); // spacer
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');
$pdf->Cell(0, LH, 'Printed at: Bekasi, ' . hari_tanggal('', 1, 0), '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');
$pdf->Cell(0, LH, 'Dokter Pemeriksa: dr. Mutiara Putri Camelia ', '-', $ln1, 'L');
$pdf->Cell(120, LH, ' ', '-', $ln0, 'L');


include 'pdf-qr_show.php';



// footer after keluhan
$k = 'kimia_darah';
if (in_array($k, $arr_page_at)) {
  // echo "<br>$k ----------------";
  $pdf->SetY(273);
  $pdf->SetFont(FF, '', FS);
  foreach ($arr_page_at as $k3 => $v3) {
    if ($v3 == $k) {
      $page = $k3 + 1;
      break;
    }
  }
  $pdf->Cell(0, 3, "MCU-$id_pasien --- Page $page of $total_page", $border_debug, 0, 'C');
}
