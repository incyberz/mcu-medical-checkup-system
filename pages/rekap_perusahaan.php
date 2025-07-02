<style>
  .ada_error-1 td {
    background: yellow !important;
  }
</style>
<?php
$mode = $_GET['mode'] ?? 'detail';
$get_csv = $_GET['csv'] ?? null;
$get_tanggal_periksa = $_GET['tanggal_periksa'] ?? '';
$get_mode_kesimpulan = $_GET['mode_kesimpulan'] ?? '';
if ($mode == 'kirim_link') {
  include 'rekap_perusahaan-kirim_link.php';
  exit;
}
include 'pages/hasil_pemeriksaan-styles.php';
include 'include/arr_kesimpulan.php';
include 'include/arr_id_pemeriksaan.php';
include 'rekap_perusahaan-styles.php';
$judul = 'Rekap Pemeriksaan';
$belum_ada = '<span class="red bold miring">belum ada</span>';
$img_filter = img_icon('filter');
$batas_imt = [
  18.5 => 'Underweight',
  25 => 'Normal range',
  30 => 'Overweight',
  35 => 'Obese class 1',
  40 => 'Obese class 2',
  999 => 'Obese class 3',
];













# ============================================================
# PROCESSORS
# ============================================================
if (isset($_POST['btn_submit'])) {
  foreach ($_POST as $key => $value) {
    $t = explode('__', $key);
    if ($t[0] == 'hasil') {
      $s = "UPDATE tb_hasil_pemeriksaan SET 
      hasil = $value,
      approv_date = CURRENT_TIMESTAMP,
      approv_by = $id_user
      WHERE id_pasien = $t[1] ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
}









# ============================================================
# SELECT PERUSAHAAN
# ============================================================
$id_perusahaan = $_GET['id_perusahaan'] ?? '';
if (!$id_perusahaan) {
  include 'rekap_perusahaan-pilih_perusahaan.php';
  exit;
} else {
  $s = "SELECT * FROM tb_perusahaan WHERE id=$id_perusahaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $perusahaan = mysqli_fetch_assoc($q);
}




# ============================================================
# HANDLER FOR CSV
# ============================================================
if ($get_csv) {
  # =======================================================
  # CSV FILE HANDLER STARTED
  # =======================================================
  $NAMA_PERUSAHAAN = $perusahaan['nama'];
  $date = date('ymd');
  $src_csv = "csv/data-pasien-" . strtolower(str_replace(' ', '_', $NAMA_PERUSAHAAN)) . "-$date.csv";
  $file = fopen($src_csv, "w+");
  fputcsv($file, ['NAMA PERUSAHAAN ' . strtoupper($NAMA_PERUSAHAAN)]);
  fputcsv($file, ['Tanggal: ' . date('d-M-Y')]);
  fputcsv($file, ['Jam: ' . date('H:i')]);
  fputcsv($file, [' ']);
}


# ============================================================
# MODE CONTROLLER | ROUTING
# ============================================================
include 'rekap_perusahaan-mode_controller.php';
set_h2($judul, $sub_judul);


# ============================================================
# NAVIGASI TANGGAL
# ============================================================
include 'rekap_perusahaan-navigasi_tanggal.php';





























# ============================================================
# SELECT PASIEN PRE-SQL COMMAND
# ============================================================
// if (array_key_exists($id_perusahaan, $arr_mode_bayar_cor_man)) {
if ($perusahaan['cara_bayar'] == 'ci' || $perusahaan['cara_bayar'] == 'bi') { // Cor-Idv
  $tb_c = "tb_harga_perusahaan c ON a.id_harga_perusahaan=c.id";
} else {
  $tb_c = "tb_order c ON a.order_no=c.order_no";
}

$sql_mode_kesimpulan = 1;
if ($get_mode_kesimpulan == 1) {
  $sql_mode_kesimpulan = "d.hasil is not null";
} elseif ($get_mode_kesimpulan == -1) {
  $sql_mode_kesimpulan = "d.hasil is null";
}

// echo '<pre>';
// print_r('zzzzzzzzzzz' . $sql_mode_kesimpulan);
// echo '</pre>';
// exit;


$sql_tanggal_periksa = 1;
$tanggal_periksa_header = null;
if ($get_tanggal_periksa) {
  $sql_tanggal_periksa = '';
  $arr_tanggal_periksa = explode(',', $get_tanggal_periksa);
  foreach ($arr_tanggal_periksa as $tgl) {
    if (strtotime($tgl)) {
      $koma_spasi = $sql_tanggal_periksa ? ', ' : '';
      $Tgl = hari_tanggal($tgl, 0, 0, 0);
      $tanggal_periksa_header .= "$koma_spasi$Tgl";
      $OR = $sql_tanggal_periksa ? 'OR' : '';
      $sql_tanggal_periksa .= " $OR
        (
          awal_periksa >= '$tgl' 
          AND awal_periksa < '$tgl 23:59:59'
        ) 
      ";
    }
  }
  $sql_tanggal_periksa = "($sql_tanggal_periksa)";
}


# ============================================================
# MAIN SELECT PASIEN
# ============================================================
$s = "SELECT 
a.*,
a.id as id_pasien,
a.nama as nama_pasien,
b.nama as jenis_pasien,
(SELECT nama FROM tb_status_pasien WHERE status=a.status) status_pasien, 
(
  SELECT CONCAT(p.singkatan,' | ',q.perusahaan) FROM tb_paket p 
  JOIN tb_order q ON p.id=q.id_paket 
  JOIN tb_pasien r ON q.order_no=r.order_no 
  WHERE r.id=a.id) info_paket,
(
  SELECT p.status_bayar FROM tb_paket_custom p WHERE a.id_paket_custom=p.id
  ) status_bayar,
(
  SELECT p.status_bayar FROM tb_pembayaran p WHERE p.id_pasien=a.id
  ) status_bayar_corporate_mandiri, 
(
  SELECT arr_tanggal_by FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) arr_tanggal_by,
(
  SELECT approv_date FROM tb_hasil_pemeriksaan p WHERE p.id_pasien=a.id
  ) approv_date

FROM tb_pasien a 
JOIN tb_jenis_pasien b ON a.jenis=b.jenis 
JOIN $tb_c 
JOIN tb_hasil_pemeriksaan d ON d.id_pasien=a.id 
WHERE 1 -- (a.status = 10) -- SELESAI PEMERIKSAAN  
AND c.id_perusahaan=$id_perusahaan 
AND $sql_tanggal_periksa
AND $sql_mode_kesimpulan

ORDER BY a.urutan, a.nama 
";



$qpasien = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_rekap = mysqli_num_rows($qpasien);
$tr = '';
$tr_approv = '';
$i = 0;
$jumlah_verif = 0;
$awal_periksa = '';
$nav = div_alert('info', "Belum ada Pasien yang Selesai Pemeriksaan | <a href='?cari_pasien'>Lobby Pasien</a>");
if (mysqli_num_rows($qpasien)) {
  $no_urut = 0;
  while ($pasien = mysqli_fetch_assoc($qpasien)) {
    $i++;
    $no_urut++;
    $ada_error = 0;



    # ============================================================
    # AUTO FIX URUTAN
    # ============================================================
    if (!$pasien['urutan']) {
      $no_urut_perusahaan = $perusahaan['id'] * 1000 + $no_urut;
      $s = "UPDATE tb_pasien SET urutan=$no_urut_perusahaan WHERE id=$pasien[id]";
      mysqli_query($cn, $s) or die(mysqli_error($cn));
    }



    # ============================================================
    # LOOP CSV
    # ============================================================
    if ($get_csv) {
      $dcsv = [];
      # ============================================================
      # CSV HEADER HANDLER
      # ============================================================
      if ($i == 1) {
        $rheader_csv = [];
        foreach ($pasien as $nama_kolom => $array_data) {
          array_push($rheader_csv, strtoupper(str_replace('_', ' ', $nama_kolom)));
        }
        fputcsv($file, $rheader_csv);
      }

      # ============================================================
      # CSV KONTEN HANDLER
      # ============================================================
      fputcsv($file, $pasien);
    }

    if ($pasien['approv_date']) $jumlah_verif++;
    $jenis = strtolower($pasien['jenis']);
    $status = $pasien['status'];
    $id_pasien = $pasien['id_pasien'];
    $gender = strtolower($pasien['gender']);

    $link_null = "<a href='?pasien_detail&id_pasien=$id_pasien'>$null</a>";

    include 'pages/pemeriksaan-hasil_at_db.php';
    $awal_periksa = $hasil_at_db['awal_periksa'];

    # ============================================================
    # PEMERIKSAAN PENUNJANG
    # ============================================================
    $id_labs = [
      'URINE' => 20,
      'HEMA' => 3,
      'RONTGEN' => 9
    ];

    echo '<pre>';
    print_r(zzz);
    echo '<b style=color:red>Developer SEDANG DEBUGING: exit(true)</b></pre>';
    exit;
    # ============================================================
    # PENAMBAHAN CUSTOM PEMERIKSAAN LAB UNTUK PERUSAHAAN TERTENTU
    # ============================================================
    if ($id_perusahaan == 27) { // SMK TARUNA
      $id_labs = [
        'URINE' => 20,
        'HEMA' => 3,
        'RONTGEN' => 9,
        'KMD' => 2
      ];
    } elseif ($id_perusahaan == 41 || $id_perusahaan == 42) { // BEN MAKMUR
      $id_labs = [
        'URINE' => 20,
        'HEMA' => 3,
        'RONTGEN' => 9,
        'ASAM_URAT' => 43,
        'GLUKOSA_SEWAKTU' => 46,
        'CHOLESTEROL_TOTAL' => 35
      ];
    }

    $hasil_lab = []; // hasil lab untuk setiap pasien
    foreach ($id_labs as $key => $id_pemeriksaan) {

      $s = "SELECT
      b.id as id_detail, 
      a.nama as nama_pemeriksaan,
      b.* 
      FROM tb_pemeriksaan a 
      JOIN tb_pemeriksaan_detail b ON a.id=b.id_pemeriksaan 
      WHERE a.id=$id_pemeriksaan";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      $sub_li = '';
      if (!mysqli_num_rows($q)) {
        die(div_alert('danger', "Pemeriksaan $arr_pemeriksaan[$id_pemeriksaan] belum mempunyai detail pemeriksaan"));
      } else {
        while ($d = mysqli_fetch_assoc($q)) {
          $id_detail = $d['id_detail'];
          $option_default = $d['option_default'];
          $normal_value = $d['normal_value'];

          if (isset($arr_id_detail[$id_detail])) {
            $hasil = $arr_id_detail[$id_detail];
            if ($normal_value) {
              if ($normal_value != $hasil) {
                $sub_li .= "<li><span class=column>$d[label]:</span> <span class='consolas red'>$hasil</span></li>";
              }
            } elseif ($option_default) {
              if ($option_default != $hasil and $id_detail != 129) { // exception eritrosit urine id = 129
                $sub_li .= "<li><span class=column>$d[label]:</span> <span class='consolas red'>$hasil</span></li>";
              }
            } else { // by batasan
              $lo = $d['normal_lo_l'];
              $hi = $d['normal_hi_l'];

              if ($lo and $hi and $lo < $hi) {
                if ($d['normal_lo_p'] and $d['normal_hi_p']) {
                  // $nilai_normal = "
                  //   L (" . floatval($d['normal_lo_l']) . " - " . floatval($d['normal_hi_l']) . "), 
                  //   P (" . floatval($d['normal_lo_p']) . " - " . floatval($d['normal_hi_p']) . ") 
                  // ";
                  if ($gender == 'p') {
                    $lo = $d['normal_lo_p'];
                    $hi = $d['normal_hi_p'];
                  }
                } else {
                  // $nilai_normal =  floatval($d['normal_lo_l']) . " - " . floatval($d['normal_hi_l']);
                }
                if ($hasil < $lo) {
                  $sub_li .= "<li><span class=column>$d[label]:</span> <span class='consolas red'>$hasil LOW</span></li>";
                } elseif ($hasil > $hi) {
                  $sub_li .= "<li><span class=column>$d[label]:</span> <span class='consolas red'>$hasil HIGH</span></li>";
                }
              } else {
                echo '<pre>';
                print_r($d);
                echo '</pre>';
                echo div_alert('danger', "Invalid batasan nilai pada detail pemeriksaan. id_detail: $id_detail");

                echo "
                  <form method=post>
                    <h4>Set Batasan Nilai Normal $d[label] (On Coding ZZZ)</h4>
                    

                    <div class='row'>
                      <div class='col-6'>
                        <div class='card gradasi-kuning'>
                          <div class='card-body'>
                            <div class=mb-2>Batasan untuk Laki-laki</div>
                            <div class='d-flex gap-2'>
                              <div class=flex-fill>
                                <input type=number step=0.01 class=form-control name=normal_lo_l>
                              </div>
                              <div class=mx-2>
                                s.d
                              </div>
                              <div class=flex-fill>
                                <input type=number step=0.01 class=form-control name=normal_hi_l>
                              </div>
                            </div>
                            <div class='f14 abu mt-2'>
                              <label>
                                <input type=checkbox checked>
                                Batasan untuk Perempuan adalah sama
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                ";
                exit;
              }
            }
          }
        }
      }

      $hasil_lab[$key] = $sub_li ? "<ul class='m0 p0 pl2'>$sub_li</ul>" : 'normal';
    }

    # ============================================================
    # dilarang ada index null (belum diperiksa)
    # ============================================================
    if (!isset($arr_id_detail[1])) $arr_id_detail[1] = 0; // tinggi badan
    if (!isset($arr_id_detail[2])) $arr_id_detail[2] = 0; // berat badan
    if (!isset($arr_id_detail[6])) $arr_id_detail[6] = 0; // lingkar perut
    if (!isset($arr_id_detail[7])) $arr_id_detail[7] = 0; // tekanan sistolik
    if (!isset($arr_id_detail[8])) $arr_id_detail[8] = 0; // diastol
    if (!isset($arr_id_detail[9])) $arr_id_detail[9] = 0; // pernafasan
    if (!isset($arr_id_detail[10])) $arr_id_detail[10] = 0; // satur oksigen
    if (!isset($arr_id_detail[14])) $arr_id_detail[14] = 0; // visus kanan
    if (!isset($arr_id_detail[140])) $arr_id_detail[140] = 0; // nadi
    if (!isset($arr_id_detail[142])) $arr_id_detail[142] = 0; // visus kiri
    if (!isset($arr_id_detail[148])) $arr_id_detail[148] = 0; // suhu

    // $bg_red = 'bg-danger text-white';

    $tinggi_badan = $arr_id_detail[2];
    $berat_badan = $arr_id_detail[1];
    if ($tinggi_badan and $berat_badan) {
      $imt = round($berat_badan * 10000 / ($tinggi_badan * $tinggi_badan), 2);
    } else {
      $imt = "<b class='red'>0</b>";
      $ada_error = 1;
    }

    $keluhan = $pasien['keluhan'] ?? 'tidak ada';

    if ($hasil_at_db['hasil'] === '' || $hasil_at_db['hasil'] === null) {
      $kesimpulan = $belum_ada;
      $ada_error = 1;
    } else {
      $kesimpulan =  $arr_kesimpulan[$hasil_at_db['hasil']];
      $kesimpulan = $hasil_at_db['hasil'] ? $kesimpulan : "<b class='red'>$kesimpulan</b>";
    }

    $kesimpulan_fisik = $hasil_at_db['kesimpulan_fisik'] ?? $belum_ada;
    $kesimpulan_fisik = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$pasien[id_pasien]&jenis=mcu'><span class=black>$kesimpulan_fisik</span></a>";

    # ============================================================
    # KONSULTASI DAN REKOMENDASI
    # ============================================================
    include 'rekap_perusahaan-konsultasi.php';



    # ============================================================
    # CUSTOM TD TAMBAHAN UNTUK PERUSAHAAN TERTENTU
    # ============================================================
    $td_tambahan = '';
    $td_tambahan = '';
    if ($id_perusahaan == 27) {
      // sementara untuk SMK-TB
      $h = strpos(strtolower("salt$hasil_lab[KMD]"), 'normal') ? '<span class=black>normal</span>' : "$hasil_lab[KMD]";
      $h = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=KMD&id_pemeriksaan=$id_labs[KMD]'>$h</a>";
      $td_tambahan = "<td><span class=hideit>KIMIA DARAH</span>$h</td>";
    } elseif ($id_perusahaan == 41 || $id_perusahaan == 42) {
      $h = strpos(strtolower("salt$hasil_lab[ASAM_URAT]"), 'normal') ? '<span class=black>normal</span>' : "$hasil_lab[ASAM_URAT]";
      $h = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=ASAM_URAT&id_pemeriksaan=$id_labs[ASAM_URAT]'>$h</a>";
      $ASAMU = "<div><span class='bold'>ASAM.U</span>: $h</div>";

      $h = strpos(strtolower("salt$hasil_lab[GLUKOSA_SEWAKTU]"), 'normal') ? '<span class=black>normal</span>' : "$hasil_lab[GLUKOSA_SEWAKTU]";
      $h = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=GLUKOSA_SEWAKTU&id_pemeriksaan=$id_labs[GLUKOSA_SEWAKTU]'>$h</a>";
      $GLUKOSA = "<div><span class='bold'>GLUKO</span>: $h</div>";

      $h = strpos(strtolower("salt$hasil_lab[CHOLESTEROL_TOTAL]"), 'normal') ? '<span class=black>normal</span>' : "$hasil_lab[CHOLESTEROL_TOTAL]";
      $h = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=CHOLESTEROL_TOTAL&id_pemeriksaan=$id_labs[CHOLESTEROL_TOTAL]'>$h</a>";
      $CHOLES = "<div><span class='bold'>CHOLES</span>: $h</div>";
      $td_tambahan .= "
        <td>
          $ASAMU
          $GLUKOSA
          $CHOLES
        </td>
      ";
    }


    # ============================================================
    # APPROVE KOMPONEN
    # ============================================================
    if ($mode == 'approv') {
      # ============================================================
      # RADIO FIT | UNFIT KESIMPULAN | PRINT PERORANGAN
      # ============================================================
      include 'rekap_perusahaan-mode_approv.php';
    } else { // end if mode approv

      if (isset($arr_id_detail[11])) {
        $buta_show = 'tidak buta warna';
        if ($arr_id_detail[11] < 7) $buta_show = "<span class=red>parsial</span>";
        if ($arr_id_detail[11] < 4) $buta_show = "<span class=red>buta warna</span>";
      } else {
        $buta_show = '<i>--no data--</i>';
      }

      $status_gizi = '';
      foreach ($batas_imt as $batas => $v) {
        $status_gizi = $v;
        if ($batas > $imt) break;
      }

      $hasil_rontgen = strpos(strtolower("salt$hasil_lab[RONTGEN]"), 'normal') ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=RON'><b class=red>$hasil_lab[RONTGEN]</b></a>";


      # ============================================================
      # FINAL TR PREVIEW UNTUK PERUSAHAAN
      # ============================================================
      if ($pasien['gender']) {
        $gender = $pasien['gender'] == 'l' ? 'Laki-laki' : 'Perempuan';
        $gender = "<a href='?pasien_detail&id_pasien=$id_pasien'>$gender</a>";
      } else {
        $gender = $link_null;
        $ada_error = 1;
      }

      if (strtotime($pasien['tanggal_lahir']) < strtotime('1940-1-1')) {
        $tanggal_lahir = $link_null;
      } else {
        $tanggal_lahir = date('d-m-Y', strtotime($pasien['tanggal_lahir']));
      }

      # ============================================================
      # FINAL TR
      # ============================================================
      $tr .= " 
        <tr class='ada_error-$ada_error'>
          <td><span class='no-urut hover' id=no-urut--$pasien[id]--$pasien[urutan]--$perusahaan[id]>$no_urut</span></td>
          <td>
            <div>MCU-$pasien[id_pasien]</div>
            <div>$pasien[nama_pasien]</div>
            <div>$gender</div>
            <div>$tanggal_lahir</div>
          </td>


          <td>
            <div><span class='lowerZZ bold'>TENSI</span>: $arr_id_detail[7]/$arr_id_detail[8]</div>
            <div><span class='lowerZZ bold'>NADI</span>: $arr_id_detail[140]</div>
            <div><span class='lowerZZ bold'>NAFAS</span>: $arr_id_detail[9]</div>
            <div><span class='lowerZZ bold'>SUHU</span>: $arr_id_detail[148]</div>
            <div><span class='lowerZZ bold'>S.O</span>: $arr_id_detail[10]</div>
          </td>
          <td>
            <div><span class='lowerZZ bold'>LP</span>: $arr_id_detail[6]</div>
            <div><span class='lowerZZ bold'>TB</span>: $arr_id_detail[2]</div>
            <div><span class='lowerZZ bold'>BB</span>: $arr_id_detail[1]</div>
            <div><span class='lowerZZ bold'>S.GIZI</span>: $status_gizi</div>
          </td>


          <td>
            <div><span class='lowerZZ bold'>KANAN</span>: $arr_id_detail[14]/20</div>
            <div><span class='lowerZZ bold'>KIRI</span>: $arr_id_detail[142]/20</div>
            <div><span class='lowerZZ bold'>BW</span>: $buta_show</div>
          </td>

          <td><span class=hideit>KELUHAN</span>$keluhan</td>

          <td><span class=hideit>DARAH LENGKAP</span>$hasil_lab[HEMA]</td>
          <td><span class=hideit>URINE</span>$hasil_lab[URINE]</td>
          <td><span class=hideit>RONTGEN</span>$hasil_rontgen</td>

          $td_tambahan

          <td><span class=hideit>KESIMPULAN</span>$kesimpulan</td>
          <td><span class=hideit>KONSULTASI</span>$konsultasi</td>
          <td><span class=hideit>REKOMENDASI</span>$rekomendasi</td>        
        </tr>
      ";
    }
  } // end while
  if (isset($_POST['btn_submit'])) jsurl(); // refresh if POST Processing
}





# ============================================================
# CLOSING CSV HANDLER
# ============================================================
if ($get_csv) {
  # ============================================================
  # CSV CLOSING HANDLER
  # ============================================================
  fputcsv($file, [
    'DATA FROM: Mutiara Medical System, PRINTED AT: ' .
      date('F d, Y, H:i:s')
  ]);
  fclose($file);
  $link_download_export_csv = " 
    <a href='$src_csv' target=_blank class='btn btn-primary my-3'>Download Excel</a>
  ";
} else {
  $link_download_export_csv = "<a href='?nilai_akhir&csv=1' class='btn btn-success ' onclick='alert(`Export Data?`)'>Export Data</a>";
}




$arr_head = [ // header preview untuk perusahaan
  'NO',
  'PASIEN MCU',
  'PEMFIS-1',
  'PEMFIS-2',
  'PEMFIS MATA',
  'KELUHAN',
];

array_push($arr_head, 'DARAH LENGKAP');
array_push($arr_head,   'URINE');
array_push($arr_head,   'RONTGEN');

# ============================================================
# HEADER TAMBAHAN
# ============================================================
if ($id_perusahaan == 27) { // smk tb
  array_push($arr_head, 'KIMIA DARAH');
} elseif ($id_perusahaan == 41 || $id_perusahaan == 42) { // ben makmur
  array_push($arr_head, 'KIMIA DARAH');
  // array_push($arr_head, 'GLUKOSA_SEWAKTU');
  // array_push($arr_head, 'CHOLESTEROL_TOTAL');
}

array_push($arr_head,   'KESIMPULAN');
array_push($arr_head,   'KONSULTASI');
array_push($arr_head,   'REKOMENDASI');

# ============================================================
# HITUNG WIDTH
# ============================================================
$width = intval(96 / count($arr_head));
$width_first = 3;
$width_last = 100 - $width_first - ($width * count($arr_head));




$th = '';
foreach ($arr_head as $key => $value) {
  if ($key == 0) {
    $w = $width_first;
  } elseif ($key == count($arr_head) - 1) {
    $w = $width_last;
  } else {
    $w = $width;
  }
  $th .= "<th style='width:$w%'>$value</th>";
}




# ============================================================
# DESAIN HEADER DOKUMEN
# ============================================================
$NAMA_PERUSAHAAN = strtoupper($perusahaan['nama']);
$tanggal_periksa = date('Y-m-d', strtotime($awal_periksa));
$tanggal_periksa_header = $tanggal_periksa_header ?? hari_tanggal($awal_periksa, 1, 0, 0);
$landscape = 'landscape';
if ($mode == 'detail') {
  $h3 = "REKAPITULASI HASIL MCU $NAMA_PERUSAHAAN";
} elseif ($mode == 'invoice' || $mode == 'kwitansi') {
  $landscape = 'portrait';
  $MODE = strtoupper($mode);
  $h3 = "<span class='f30' style='font-weight: normal; color:blue; letter-spacing: 5px'>$MODE</span>";
  $tanggal_periksa_header = '';
} elseif ($mode == 'approv') {
  $h3 = "<div class='f18 biru'>APPROV KESIMPULAN $NAMA_PERUSAHAAN</div>";
  $tr = $tr_approv;

  $arr_head = [
    'PASIEN MCU',
    'KELUHAN',
    'KESIMPULAN FISIK',
    'DARAH LENGKAP',
    'URINE',
    'RONTGEN',
    'KESIMPULAN',
    'KONSULTASI',
    'REKOMENDASI',
  ];

  // sementara untuk SMK-TB
  if ($id_perusahaan == 27 || $id_perusahaan == 41 || $id_perusahaan == 42) { // SMK-TB || BEN-MAKMUR
    $arr_head = [
      'PASIEN MCU',
      'KELUHAN',
      'MCU FISIK',
      'DARAH LENGKAP',
      'URINE',
      'RONTGEN',
      'KIMIA DARAH',
      'KESIMPULAN',
      'KONSULTASI',
      'REKOMENDASI',
    ];
  }

  # ============================================================
  # HITUNG WIDTH HEADER MODE APPROV
  # ============================================================
  $width = intval(100 / count($arr_head));
  $width_first = 100 - ($width * (count($arr_head) - 1));


  $th = '';
  foreach ($arr_head as $key => $value) {
    $w = $key ? $width : $width_first;
    $th .= "<th style='width:$w%'>$value</th>";
  }
} else {
  $h3 = 'BELUM ADA DESAIN JUDUL';
  $NAMA_PERUSAHAAN = '';
}




$tag_form = '';
$end_form = '';
$btn_print = "<button class='btn btn-primary' onclick=window.print() id=btn_print>Print</button>";
$btn_pdf = "<a target=_blank href='pdf/?id_perusahaan=$id_perusahaan&tanggal_periksa=$tanggal_periksa' class='btn btn-success' onclick='return confirm(`Download PDF`)'>Download PDF Semua Pasien</a>";
$btn_excel = "<a target=_blank href='?rekap_perusahaan&id_perusahaan=41&mode=detail&tanggal_periksa=$get_tanggal_periksa&csv=1' class='btn btn-success' onclick='return confirm(`Download CSV`)'>Download Excel</a> $link_download_export_csv";
$btn_excel  = ''; // aborted
$btn_submit  = '';
$sub_h = "<div class='tengah m2 abu f14'>Preview Rekap per Perusahaan</div>";
if ($mode == 'approv') {
  $sub_h = '';
  $tag_form = '<form method=post>';
  $end_form = '</form>';
  $btn_print = '';
  $btn_pdf = '';
  $btn_excel = '';
  $btn_submit = "<button class='btn btn-primary w-100' type=submit name=btn_submit>Submit Kesimpulan</button>";
}

# ============================================================
# FINAL ECHO
# ============================================================

echo "
  $sub_h
  <div class='text-center mb-3'><span class=f30>$jumlah_rekap</span> rows data</div>
  <div class='flex flex-center'>
    <div class='kertas $landscape bg-white'>
      <div class='tengah mb2'>
        <div>$img_header_logo</div>
        <div class='border-bottom mb2 pb2 f12 mt1'>Tambun Business Park Blok C12 Tambun - Bekasi<br>Telp.(021) 29487893</div>

        <h3 class='f14 bold'>$h3</h3>
        <div class='f10 bold'>$tanggal_periksa_header</div>
      </div>";

if ($mode == 'detail' || $mode == 'approv') {
  # ============================================================
  # KONTEN INTI : FORM APPROV OR PREVIEW CORPORATE
  # ============================================================      
  echo "
    $tag_form
    <table class='table table-bordered f8'>
      $th
      $tr
    </table>
  ";

  if ($mode != 'approv') {
    echo "
          <div style='margin-left: 20cm'>
            <div class=mb1>Penanggung Jawab Klinik Mutiara 1</div>
            <div class=mb1>";

    include 'include/enkrip14.php';
    $z = enkrip14($id_perusahaan);

    require_once 'include/qrcode.php';
    $qr = QRCode::getMinimumQRCode("https://mmc-clinic.com/qr?$z", QR_ERROR_CORRECT_LEVEL_L);
    $qr->printHTML('3px');
    echo "
            </div>
            <div>dr. Mutiara Putri Camelia</div>
          </div>";
  }
  echo "
          $btn_submit
        $end_form";
  # ============================================================
  # END KONTEN INTI : FORM APPROV OR PREVIEW CORPORATE
  # ============================================================

} elseif ($mode == 'kwitansi') {
  include 'rekap_perusahaan-kwitansi.php';
} elseif ($mode == 'invoice') {
  include 'rekap_perusahaan-invoice.php';
} else {
  echo div_alert('danger', "KONTEN INTI UNTUK MODE [$mode] BELUM ADA");
}

echo "
    </div> <!-- end kertas -->
  </div> <!-- end div flex center -->

  <div class='tengah m2'>
    <div class='flex flex-center'>
      <div class=ml4>
        $btn_print
      </div>
      <div class=ml4>
        $btn_pdf
      </div>
      <div class=ml4>
        $btn_excel
      </div>
    </div>
  </div>
";


































?>
<script>
  $(function() {
    $('.no-urut').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id_pasien = rid[1];
      let no_urut_db = rid[2];
      let id_perusahaan = rid[3];
      let no_urut_awal = $(this).text();
      // console.log(aksi, id_pasien, no_urut_awal);
      let no_urut_baru = prompt(`No. Urut: ${no_urut_awal} \nMasukkan No. Urut Baru`);
      if (!no_urut_baru) return;
      if (no_urut_baru == no_urut_awal) return;
      let link_ajax = `ajax/ajax_update_no_urut.php?id_pasien=${id_pasien}&no_urut_awal=${no_urut_awal}&no_urut_baru=${no_urut_baru}&id_perusahaan=${id_perusahaan}`;
      // console.log(tid, id_perusahaan, link_ajax);

      $.ajax({
        url: link_ajax,
        success: function(a) {
          if (a.trim() == 'sukses') {
            // console.log(a);
            location.reload();
          } else {
            alert(a);
          }
        }
      });
    })
  })
</script>