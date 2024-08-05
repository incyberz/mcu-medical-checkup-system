<?php
session_start();
$id_klinik = 1;
include "../conn.php";
include '../include/insho_functions.php';
include '../include/arr_pemeriksaan.php';
include '../include/arr_pemeriksaan_detail.php';
include '../include/arr_penanda_gigi.php';

$tidak_ada = '--tidak ada--';
$total_page = 4;
$border_debug = 0;

# ===========================================
# CONSTANT SETTINGS
# ===========================================
define('LH', 4.3); //LINE HEIGHT
define('LHB', 6); //LINE HEIGHT BOX | TITLE







# ============================================================
# REQUIRE FPDF
# ============================================================
ob_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
  public function Header()
  {
    $this->SetFont('Arial', 'B', 10);
    $this->Cell(
      0,
      15,
      $this->Image('../assets/img/header_logo.png', 73, 8, 64, 15),
      0,
      1,
      'C'
    );
    $this->SetFont('Arial', '', 8);
    $this->Cell(0, 4, 'Tambun Business Park Blok C12 Tambun - Bekasi, Telp.(021) 29487893', 0, 1, 'C');
    // $this->Cell(0, 4, '', 0, 1, 'C');
    $this->Cell(0, 2, ' ', 'B', 1, 'C');
    $this->Ln(4);
  }

  public function Footer()
  {
    // $this->SetY(-15);
    // $this->SetFont('Arial', 'I', 8);
    // $this->Cell(0, 10, "Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    // $this->SetY(-10);
    // $this->SetFont('Arial', 'I', 8);
    // $this->Cell(0, 5, "Page " . $this->PageNo() . '/{nb}', 1, 0, 'R');
  }
}






































# ============================================================
# NEW PAGE #1
# ============================================================
$pdf = new PDF();
$pdf->AliasNbPages();



# ============================================================
# GET HASIL AND PASIEN DATA
# ============================================================
$arr_id_detail = [];
$arr_id_pemeriksaan_tanggal = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];

$s = "SELECT 
* 
FROM tb_hasil_pemeriksaan a 
JOIN tb_pasien b ON a.id_pasien=b.id 
WHERE id_pasien < 3";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pasien = [];
if (mysqli_num_rows($q)) {
  while ($pasien = mysqli_fetch_assoc($q)) {
    # ============================================================
    # EXTRACT HASIL
    # ============================================================
    $arr_hasil = explode('||', $pasien['arr_hasil']);
    $arr_tanggal_by = explode('||', $pasien['arr_tanggal_by']);

    foreach ($arr_hasil as $pair) {
      $pair = trim($pair);
      if ($pair) {
        $arr_pair = explode('=', $pair, 2);
        $arr_id_detail[$arr_pair[0]] = $arr_pair[1];
      }
    }

    foreach ($arr_tanggal_by as $pair) {
      if (!$pair) continue;
      $arr_pair = explode('=', $pair, 2);
      $arr_id_pemeriksaan_tanggal[$arr_pair[0]] = $arr_pair[1];


      $arr_tmp = explode(',', $arr_pair[1]);
      $arr_pemeriksaan_tanggal[$arr_pair[0]] = $arr_tmp[0];
      $arr_pemeriksaan_by[$arr_pair[0]] = $arr_tmp[1];
    }

    # ============================================================
    # FIXED TMP DATA
    # ============================================================
    $dokter_pj = 'dr. Mutiara Putri Camelia';
    $dokter_pengirim = 'Dokter MCU';
    $no_rm = '-';

    # ============================================================
    # EXTRACT DATA PASIEN
    # ============================================================
    $id_pasien = $pasien['id_pasien'];
    $awal_periksa = $pasien['awal_periksa'];
    $id_harga_perusahaan = $pasien['id_harga_perusahaan'];
    $id_paket_custom = $pasien['id_paket_custom'];
    $order_no = $pasien['order_no'];
    $JENIS = strtoupper($pasien['jenis']);
    $gender = $pasien['gender'];
    $no_mcu = "MCU-$id_pasien";

















    # ===========================================
    # HEADER MCU
    # ===========================================
    include 'pdf-header-mcu.php';

    # ============================================================
    # PROCESSING RIWAYAT PENYAKIT
    # ============================================================
    $pasien['riwayat_penyakit_pasien'] = null;
    $pasien['riwayat_pengobatan'] = null;
    $pasien['riwayat_penyakit_ayah'] = null;
    $pasien['riwayat_penyakit_ibu'] = null;
    include 'pdf-riwayat_penyakit.php';

    # ============================================================
    # PROCESSING pemeriksaan_fisik_awal
    # ============================================================
    $pasien['pemeriksaan_fisik_awal'] = [];
    include 'pdf-pemeriksaan_fisik_awal.php';

    # ============================================================
    # PROCESSING visus_mata
    # ============================================================
    $pasien['visus_mata'] = [];
    include 'pdf-visus_mata.php';

    # ============================================================
    # PROCESSING array_gigi
    # ============================================================
    $array_gigi_default = '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,';
    $pasien['array_gigi'] = $arr_id_detail[94] ?? $array_gigi_default;

    # ============================================================
    # PROCESSING pemeriksaan_penunjang
    # ============================================================
    $pasien['pemeriksaan_penunjang'] = [];
    include 'pdf-pemeriksaan_penunjang.php';




























    # ============================================================
    # MAIN LOOPING
    # ============================================================
    $arr = [
      // 'riwayat_penyakit_pasien' => $pasien['riwayat_penyakit_pasien'],
      // 'riwayat_pengobatan' => $pasien['riwayat_pengobatan'],
      // 'riwayat_penyakit_ayah' => $pasien['riwayat_penyakit_ayah'],
      // 'riwayat_penyakit_ibu' => $pasien['riwayat_penyakit_ibu'],
      // 'gejala_penyakit' => $pasien['gejala_penyakit'],
      // 'gaya_hidup' => $pasien['gaya_hidup'],
      // 'keluhan' => $pasien['keluhan'],
      // 'pemeriksaan_fisik_awal' => $pasien['pemeriksaan_fisik_awal'],
      // 'visus_mata' => $pasien['visus_mata'],
      // 'pemeriksaan_gigi' => $pasien['array_gigi'],
      'pemeriksaan_penunjang' => $pasien['pemeriksaan_penunjang'],
    ];

    $shv = 4; // vertival spacer height
    foreach ($arr as $k => $v) {
      $KOLOM = strtoupper(str_replace('_', ' ', $k));

      // add page
      if ($k == 'pemeriksaan_fisik_awal') {
        $pdf->AddPage();
      }

      // exclusion pemeriksaan gigi
      if ($k == 'pemeriksaan_gigi') {
        include 'pdf-pemeriksaan_gigi.php';
        continue;
      }

      if ($pasien[$k]) {
        $pdf->Cell(0, LHB, "$KOLOM: ", 1, 1);
        $pdf->Cell(0, 1, ' ', 'LR', 1);

        if (is_array($pasien[$k])) {
          // list item array harus diproses dahulu
          $arr2 = $pasien[$k];
          if (isset($arr2['ki']) and isset($arr2['ka'])) {
            // data kiri/kanan
            if (count($arr2['ka']) > count($arr2['ki'])) die('Jumlah item kanan > item kiri');
            $baris = [];
            $i = 0;
            foreach ($arr2 as $kika => $arr3) {
              $j = 0;
              foreach ($arr3 as $k2 => $v2) {
                $baris[$j][$i] = strtoupper($k2) . ": $v2";
                $j++;
              }
              $i++;
            }

            foreach ($baris as $k2 => $v2) {
              if ($v2) {
                $pdf->Cell(95, LH, " - " . trim($v2[0]), 'L', 0);
                $pdf->Cell(95, LH, " - " . trim($v2[1]), 'R', 1);
              }
            }
          } else {
            // echo '<pre>DATA ARRAY TIDAK KIRI KANAN<hr>';
            // var_dump($arr2);
            // echo '</pre>';
            // array data tidak kiri kanan
            foreach ($arr2 as $k2 => $v2) {
              // echo '<pre>';
              // var_dump($v2);
              // echo '</pre>';
              if (!is_array($v2)) {
                $pdf->Cell(0, LHB, " - $k2: $v2", 'LR', 1);
              } else {
                $pdf->Cell(0, LHB, " - $k2:", 'LR', 1);
                foreach ($v2 as $k3 => $v3) {
                  $pdf->Cell(0, LH, "    -- $v3", 'LR', 1);
                }
              }
            }
          }
        } else {
          // list item normal
          $dt = explode(',', $pasien[$k]);
          $str = '';
          foreach ($dt as $v2) {
            if ($v2) {
              $pdf->Cell(0, LH, " - " . trim($v2), 'LR', 1);
            }
          }
        }
        $pdf->Cell(0, 1, ' ', 'LRB', 1);
      } else {
        $pdf->Cell(0, LHB, "$KOLOM: $tidak_ada", 1, 1);
      }
      $pdf->Cell(0, $shv, ' ', '', 1, ''); // spacer

      // footer after keluhan
      if ($k == 'keluhan') {
        $pdf->SetY(273);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 3, "MCU-$id_pasien --- Page 1 of $total_page", $border_debug, 0, 'C');
      }
    } // end foreach main looping
  } // end while
} else {
  die(div_alert('danger', "Data pasien tidak ditemukan"));
}
























$pdf->Output('D', "kurikulum.pdf");
ob_end_flush();
