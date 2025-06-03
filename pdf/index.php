<?php
session_start();
# ============================================================
# SESSION HANDLER
# ============================================================


$id_klinik = 1;
include "../conn.php";
include '../include/alert.php';
include '../include/insho_functions.php';
include '../include/arr_pemeriksaan.php';
include '../include/arr_pemeriksaan_detail.php';
include '../include/arr_penanda_gigi.php';
include '../include/arr_kesimpulan.php';
include '../include/arr_id_pemeriksaan.php';

$tidak_ada = '--tidak ada--';
$no_data = '--no data--';
$total_page = 4;
$border_debug = 0;
$ln1 = 1;
$ln0 = 0;

# ============================================================
# DEBUG 
# ============================================================
$get_id_pasien = $_GET['id_pasien'] ?? '';
$get_id_perusahaan = $_GET['id_perusahaan'] ?? '';
$get_tanggal_periksa = $_GET['tanggal_periksa'] ?? '';
$get_tanggal_periksa_akhir = $_GET['tanggal_periksa_akhir'] ?? '';

if (!$get_id_pasien and !$get_id_perusahaan) {
  die(div_alert('danger', 'Page ini tidak bisa diakses secara langsung. <hr>Silahkan hubungi developer!'));
}

if ($get_id_pasien) {
  $s = "SELECT nama FROM tb_pasien WHERE id=$get_id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data pasien tidak ditemukan'));
  $d = mysqli_fetch_assoc($q);
  $nama = strtolower(str_replace('-', '_', str_replace(' ', '_', $d['nama'])));
  $where_id_pasien = "id_pasien = $get_id_pasien";
  $nama_file = "hasil-mcu$get_id_pasien-$nama.pdf";
  $sql_tanggal_periksa = '1';
} elseif ($get_id_perusahaan) {
  if ($get_id_perusahaan == 1 || $get_id_perusahaan == 28 || $get_id_perusahaan == 29) { // yasunli or PT GI or LJK
    $s = "SELECT a.id as id_pasien,
    c.nama as nama_perusahaan 
    FROM tb_pasien a 
    JOIN tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id 
    JOIN tb_perusahaan c ON b.id_perusahaan=c.id 
    WHERE b.id_perusahaan=$get_id_perusahaan";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data List-Pasien-Corporate tidak ditemukan'));

    $where_id_pasien = '';
    $nama_perusahaan = '';
    while ($d = mysqli_fetch_assoc($q)) {
      $id_pasien = $d['id_pasien'];
      $nama_perusahaan = $d['nama_perusahaan'];
      $where_id_pasien .= $where_id_pasien ? " OR a.id=$id_pasien" : "a.id=$id_pasien";
    }
    $where_id_pasien = "( $where_id_pasien )";
    $nama_perusahaan = strtolower(str_replace('-', '_', $nama_perusahaan));
    $nama_perusahaan = strtolower(str_replace(' ', '_', $nama_perusahaan));
    $nama_perusahaan = strtolower(str_replace('.', '_', $nama_perusahaan));
    $nama_file = "rekap-mcu-$get_id_perusahaan-$get_tanggal_periksa-$nama_perusahaan.pdf";
  } else {
    echo div_alert('danger', 'Belum ada handler selain perusahaan yasunli || PT GI');
  }

  if (strtotime($get_tanggal_periksa)) {
    if (strtotime($get_tanggal_periksa_akhir)) {
      $tanggal_akhir = $get_tanggal_periksa_akhir;
    } else {
      $tanggal_akhir = "$get_tanggal_periksa 23:59:59";
    }
    $sql_tanggal_periksa = "b.awal_periksa >= '$get_tanggal_periksa' AND b.awal_periksa <= '$tanggal_akhir' ";
  }
}


$dokter_radiologi = 'dr. Yuliawati H, Sp.Rad';

# ===========================================
# CONSTANT SETTINGS
# ===========================================
define('FF', 'Arial'); //FONT FAMILY
define('FS', 8); //FONT SIZE
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
    $this->SetFont(FF, 'B', 10);
    $this->Cell(
      0,
      15,
      $this->Image('../assets/img/header_logo.png', 73, 8, 64, 15),
      0,
      1,
      'C'
    );
    $this->SetFont(FF, '', FS);
    $this->Cell(0, 4, 'Tambun Business Park Blok C12 Tambun - Bekasi, Telp.(021) 29487893', 0, 1, 'C');
    // $this->Cell(0, 4, '', 0, 1, 'C');
    $this->Cell(0, 2, ' ', 'B', 1, 'C');
    $this->Ln(4);
  }

  public function Footer()
  {
    // $this->SetY(-15);
    // $this->SetFont(FF, 'I', FS);
    // $this->Cell(0, 10, "Page " . $this->PageNo() . '/{nb}', 0, 0, 'C');
    // $this->SetY(-10);
    // $this->SetFont(FF, 'I', FS);
    // $this->Cell(0, 5, "Page " . $this->PageNo() . '/{nb}', 1, 0, 'R');
  }
}






































# ============================================================
# NEW PAGE #1
# ============================================================
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetFont(FF, '', FS);




# ============================================================
# GET HASIL AND PASIEN DATA
# ============================================================
$arr_id_detail = [];
$arr_id_pemeriksaan_tanggal = [];
$arr_pemeriksaan_tanggal = [];
$arr_pemeriksaan_by = [];



$s = "SELECT 
*,
(
  SELECT concat('Kec ',p.nama_kec, ', ', q.nama_kab) 
  FROM tb_kec p
  JOIN tb_kab q ON p.id_kab=q.id_kab  
  WHERE p.id_kec=a.id_kec ) alamat_trim

FROM tb_pasien a 
JOIN tb_hasil_pemeriksaan b ON b.id_pasien=a.id 
WHERE $where_id_pasien 
AND $sql_tanggal_periksa
";
// echo $s;
$q_pasien_pdf = mysqli_query($cn, $s) or die(mysqli_error($cn));
$pasien = [];
if (mysqli_num_rows($q_pasien_pdf)) {
  while ($pasien = mysqli_fetch_assoc($q_pasien_pdf)) {
    # ============================================================
    # EXTRACT HASIL
    # ============================================================
    $nama_pasien = $pasien['nama'];
    $arr_hasil = explode('||', $pasien['arr_hasil']);
    $arr_tanggal_by = explode('||', $pasien['arr_tanggal_by']);

    # ============================================================
    # ALAMAT TRIM
    # ============================================================
    $alamat_trim = $pasien['alamat_trim'] ? ucwords(strtolower($pasien['alamat_trim'])) : '-';



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
    $gender = strtolower($pasien['gender']);
    $no_mcu = "MCU-$id_pasien";


    # ============================================================
    # DATA PERUSAHAAN 
    # ============================================================
    $order_no = $pasien['order_no'];
    $id_harga_perusahaan = $pasien['id_harga_perusahaan'];
    if ($order_no) {
      $s = "SELECT a.id_perusahaan , b.* 
      FROM tb_order a 
      JOIN tb_perusahaan b ON a.id_perusahaan=b.id 
      WHERE a.order_no='$order_no'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data perusahaan tidak ditemukan'));
      $perusahaan = mysqli_fetch_assoc($q);
      $id_perusahaan = $perusahaan['id_perusahaan'];
    } elseif ($id_harga_perusahaan) {
      $s = "SELECT a.id_perusahaan, b.*  
      FROM tb_harga_perusahaan a
      JOIN tb_perusahaan b ON a.id_perusahaan=b.id 
      WHERE a.id='$id_harga_perusahaan'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (!mysqli_num_rows($q)) die(div_alert('danger', 'Data perusahaan tidak ditemukan'));
      $perusahaan = mysqli_fetch_assoc($q);
      $id_perusahaan = $perusahaan['id_perusahaan'];
    } else {
      $perusahaan = [];
      $id_perusahaan = null;
    }
    $pasien['perusahaan'] = $perusahaan['nama'] ?? '(pribadi)';

    # ============================================================
    # INISIALISASI KESIMPULAN PEMERIKSAAN FISIK
    # ============================================================
    $pasien['kesimpulan_pemeriksaan_fisik'] = [];














    # ===========================================
    # HEADER MCU
    # ===========================================
    include 'pdf-cover.php';

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
    # PROCESSING pemeriksaan_fisik_dokter
    # ============================================================
    $pasien['pemeriksaan_fisik_dokter'] = []; // setting in new file

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
    # PAGE NUMBER AT
    # ============================================================
    $arr_page_at = ['keluhan', 'pemeriksaan_fisik_dokter', 'kesimpulan', 'urine_lengkap', 'darah_lengkap', 'rontgen'];

    if ($id_perusahaan == 27 || $id_perusahaan == 41) { // SMK-TB || BEN MAKMUR
      // $arr_page_at = ['keluhan', 'pemeriksaan_fisik_dokter', 'kesimpulan', 'kimia_darah', 'urine_lengkap', 'darah_lengkap', 'rontgen'];
      array_push($arr_page_at, 'kimia_darah');
    }

    # ============================================================
    # CEK APAKAH PADA PAKET YANG DIAMBIL ADA PEMERIKSAAN LAB
    # ============================================================
    if ($id_paket_custom) { // jika pasien individu paket custom
      $s = "SELECT a.*,b.nama as nama_pemeriksaan FROM tb_paket_custom_detail a 
      JOIN tb_pemeriksaan b ON a.id_pemeriksaan=b.id
      WHERE a.id_paket_custom='$id_paket_custom'";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (!mysqli_num_rows($q)) {
        stop("Data paket dengan id_paket_custom: $id_paket_custom tidak ditemukan");
      } else {
        while ($d = mysqli_fetch_assoc($q)) {
          # ============================================================
          # TAMBAH KIMIA DARAH, EKG, DAN PEMERIKSAAN SPESIAL LAINNYA
          # ============================================================
          if ($d['id_pemeriksaan'] == 2) array_push($arr_page_at, 'kimia_darah');
          if ($d['id_pemeriksaan'] == 5) array_push($arr_page_at, 'ekg');
          if ($d['id_pemeriksaan'] == 47) array_push($arr_page_at, 'hepatitis');
        }
      }
    }
    $total_page = count($arr_page_at);



























    // fix keluhan (strip)
    if (strlen($pasien['keluhan']) < 5) $pasien['keluhan'] = null;

    # ============================================================
    # MAIN LOOPING
    # ============================================================
    $arr = [
      'riwayat_penyakit_pasien' => $pasien['riwayat_penyakit_pasien'],
      'riwayat_pengobatan' => $pasien['riwayat_pengobatan'],
      'riwayat_penyakit_ayah' => $pasien['riwayat_penyakit_ayah'],
      'riwayat_penyakit_ibu' => $pasien['riwayat_penyakit_ibu'],
      'gejala_penyakit' => $pasien['gejala_penyakit'],
      'gaya_hidup' => $pasien['gaya_hidup'],
      'keluhan' => $pasien['keluhan'],
      'pemeriksaan_fisik_awal' => $pasien['pemeriksaan_fisik_awal'],
      'pemeriksaan_fisik_dokter' => $pasien['pemeriksaan_fisik_dokter'],
      'visus_mata' => $pasien['visus_mata'],
      'pemeriksaan_gigi' => $pasien['array_gigi'],
      'kesimpulan_pemeriksaan_fisik' => $pasien['kesimpulan_pemeriksaan_fisik'],
      'pemeriksaan_penunjang' => $pasien['pemeriksaan_penunjang'],
    ];

    $shv = 4; // vertival spacer height
    foreach ($arr as $k => $v) {
      $KOLOM = strtoupper(str_replace('_', ' ', $k));

      // add page
      if ($k == 'pemeriksaan_fisik_awal' || $k == 'visus_mata') {
        $pdf->AddPage();
      }

      // exclusion pemeriksaan gigi
      if ($k == 'pemeriksaan_gigi') {
        include 'pdf-pemeriksaan_gigi.php';
        // $pdf->Cell(0, $shv, ' ', '', 1, ''); // spacer
        continue;
      } elseif ($k == 'pemeriksaan_fisik_dokter') {
        include 'pdf-pemeriksaan_fisik_dokter.php';
        $pdf->Cell(0, $shv, ' ', '', 1, ''); // spacer
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
          $pdf->Cell(
            0,
            3,
            "MCU-$id_pasien --- Page $page of $total_page",
            $border_debug,
            0,
            'C'
          );
        }
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
          } else { // DATA ARRAY TIDAK KIRI KANAN
            foreach ($arr2 as $k2 => $v2) {
              if (!is_array($v2)) {
                if (strlen($v2) > 143) {
                  // $pdf->MultiCell(0, LHB, " - $k2: $v2", 'LR', 1);
                  $pdf->Cell(0, LH, " - $k2: ", 'LR', 1);
                  $pdf->Cell(3, LH, ' ', 'L', 0); // spacer kolom at left no break
                  $pdf->MultiCell(0, LH, "$v2", 'R', 'L');
                  // MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])

                } else {
                  $pdf->Cell(0, LHB, " - $k2: $v2", 'LR', 1);
                }
                $len = strlen($v2);
                // echo "<hr>$len $k";
              } else {
                $pdf->Cell(0, LHB, " - $k2:", 'LR', 1);
                foreach ($v2 as $k3 => $v3) {
                  $pdf->Cell(0, LH, "    -- $v3", 'LR', 1);
                }
              }
            }
          }
        } else { // list item normal
          $dt = explode(',', $pasien[$k]);
          $str = '';
          foreach ($dt as $v2) {
            if ($v2) {
              $pdf->Cell(0, LH, " - " . trim($v2), 'LR', 1);
            }
          }
        }
        $pdf->Cell(0, 1, ' ', 'LRB', 1);
      } else { // tidak ada abnormal or no-data
        $tidak_ada_or_nodata = $tidak_ada;
        if ((
          $k == 'riwayat_penyakit_pasien'
          || $k == 'riwayat_pengobatan'
          || $k == 'riwayat_penyakit_ayah'
          || $k == 'riwayat_penyakit_ibu'
        ) and !$pasien['tanggal_mengisi_riwayat_penyakit']) {
          $tidak_ada_or_nodata = $no_data;
        } elseif ($k == 'gejala_penyakit' and !$pasien['tanggal_mengisi_gejala_penyakit']) {
          $tidak_ada_or_nodata = $no_data;
        } elseif ($k == 'gaya_hidup' and !$pasien['tanggal_mengisi_gaya_hidup']) {
          $tidak_ada_or_nodata = $no_data;
        } elseif ($k == 'keluhan' and !$pasien['tanggal_mengisi_keluhan']) {
          $tidak_ada_or_nodata = $no_data;
        }
        $pdf->Cell(0, LHB, "$KOLOM: $tidak_ada_or_nodata", 1, 1);
      }
      $pdf->Cell(0, $shv, ' ', '', 1, ''); // spacer

      // footer after keluhan
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
    } // end foreach main looping


    # ============================================================
    # INCLUDES SUB PDF
    # ============================================================
    include 'pdf-kesimpulan_mcu.php';
    if (in_array('kimia_darah', $arr_page_at)) include 'pdf-kimia_darah.php';
    include 'pdf-urine_lengkap.php';
    include 'pdf-darah_lengkap.php';
    if (in_array('hepatitis', $arr_page_at)) include 'pdf-hepatitis.php';
    include 'pdf-rontgen.php';
    if (in_array('ekg', $arr_page_at)) include 'pdf-ekg.php';
  } // end while data pasien

  # ============================================================
} else {
  die(div_alert('danger', "Data pasien tidak ditemukan"));
}























$nama = str_replace(' ', '-', strtolower($nama_pasien));
// $pdf->Output('D', "hasil-mcu$id_pasien-$nama.pdf");
$pdf->Output('D', "$nama_file");
ob_end_flush();
