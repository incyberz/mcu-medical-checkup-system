<?php
$mode = $_GET['mode'] ?? 'detail';
$get_tanggal_periksa = $_GET['tanggal_periksa'] ?? '';
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
WHERE (a.status = 10) -- SELESAI PEMERIKSAAN  
AND c.id_perusahaan=$id_perusahaan 
AND $sql_tanggal_periksa

ORDER BY a.nama 
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

    if ($pasien['approv_date']) $jumlah_verif++;
    $jenis = strtolower($pasien['jenis']);
    $status = $pasien['status'];
    $id_pasien = $pasien['id_pasien'];
    $gender = strtolower($pasien['gender']);

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

    if ($id_perusahaan == 27) {
      $id_labs = [
        'URINE' => 20,
        'HEMA' => 3,
        'RONTGEN' => 9,
        'KMD' => 2
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
                die(div_alert('danger', "Invalid batasan nilai pada detail pemeriksaan. id_detail: $id_detail"));
              }
            }
          }
        }
      }

      $hasil_lab[$key] = $sub_li ? "<ul class='m0 p0 pl2'>$sub_li</ul>" : 'normal';
    }

    $tinggi_badan = $arr_id_detail[2];
    $berat_badan = $arr_id_detail[1];
    $imt = round($berat_badan * 10000 / ($tinggi_badan * $tinggi_badan), 2);

    $keluhan = $pasien['keluhan'] ?? 'tidak ada';

    if ($hasil_at_db['hasil'] === '' || $hasil_at_db['hasil'] === null) {
      $kesimpulan = $belum_ada;
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


    // sementara untuk SMK-TB
    $td_kimia_darah = '';
    if ($id_perusahaan == 27) {
      $h = strpos(strtolower("salt$hasil_lab[KMD]"), 'normal') ? '<span class=black>normal</span>' : "$hasil_lab[KMD]";
      $h = "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=KMD&id_pemeriksaan=2'>$h</a>";
      $td_kimia_darah = "<td><span class=hideit>KIMIA DARAH</span>$h</td>";
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

      $buta_show = 'tidak buta warna';
      if ($arr_id_detail[11] < 7) $buta_show = "<span class=red>parsial</span>";
      if ($arr_id_detail[11] < 4) $buta_show = "<span class=red>buta warna</span>";

      $status_gizi = '';
      foreach ($batas_imt as $batas => $v) {
        $status_gizi = $v;
        if ($batas > $imt) break;
      }

      $hasil_rontgen = strpos(strtolower("salt$hasil_lab[RONTGEN]"), 'normal') ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=RON'><b class=red>$hasil_lab[RONTGEN]</b></a>";

      # ============================================================
      # FINAL TR PREVIEW UNTUK PERUSAHAAN
      # ============================================================
      $tr .= " 
        <tr>
          <td>$no_urut</td>
          <td>MCU-$pasien[id_pasien]</td>
          <td>$pasien[nama_pasien]</td>
          <td>$pasien[tanggal_lahir]</td>
          <td><span class=hideit>KELUHAN</span>$keluhan</td>
          <td><span class=hideit>TENSI</span>$arr_id_detail[7]/$arr_id_detail[8]</td>
          <td><span class=hideit>NADI</span>$arr_id_detail[140]</td>
          <td><span class=hideit>PERNAFASAN</span>$arr_id_detail[9]</td>
          <td><span class=hideit>SUHU</span>$arr_id_detail[148]</td>
          <td><span class=hideit>SATURASI OKSIGEN</span>$arr_id_detail[10]</td>
          <td><span class=hideit>STATUS GIZI</span>$status_gizi</td>
          <td><span class=hideit>LINGKAR PERUT</span>$arr_id_detail[6]</td>
          <td><span class=hideit>TINGGI BADAN</span>$arr_id_detail[2]</td>
          <td><span class=hideit>BERAT BADAN</span>$arr_id_detail[1]</td>
          <td><span class=hideit>MATA KANAN</span>$arr_id_detail[14]/20</td>
          <td><span class=hideit>MATA KIRI</span>$arr_id_detail[142]/20</td>
          <td><span class=hideit>BUTA WARNA</span>$buta_show</td>
          $td_kimia_darah
          <td><span class=hideit>DARAH LENGKAP</span>$hasil_lab[HEMA]</td>
          <td><span class=hideit>URINE</span>$hasil_lab[URINE]</td>
          <td><span class=hideit>RONTGEN</span>$hasil_rontgen</td>
          <td><span class=hideit>KESIMPULAN</span>$kesimpulan</td>
          <td><span class=hideit>KONSULTASI</span>$konsultasi</td>
          <td><span class=hideit>REKOMENDASI</span>$rekomendasi</td>        
        </tr>
      ";
    }
  } // end while
  if (isset($_POST['btn_submit'])) jsurl(); // refresh if POST Processing
}

$arr_head = [ // header preview untuk perusahaan
  'NO',
  'NO. MCU',
  'NAMA',
  'TANGGAL LAHIR',
  'KELUHAN',
  'TENSI',
  'NADI',
  'NAFAS',
  'SUHU',
  'SAT. O2',
  'STATUS GIZI',
  'LINGKAR PERUT',
  'TINGGI BADAN',
  'BERAT BADAN',
  'MATA KANAN',
  'MATA KIRI',
  'BUTA WARNA',
];

if ($id_perusahaan == 27) {
  array_push($arr_head, 'KIMIA DARAH');
}
array_push($arr_head, 'DARAH LENGKAP');
array_push($arr_head,   'URINE');
array_push($arr_head,   'RONTGEN THORAX');

array_push($arr_head,   'KESIMPULAN');
array_push($arr_head,   'KONSULTASI');
array_push($arr_head,   'REKOMENDASI');



$th = '';
foreach ($arr_head as $key => $value) {
  $th .= "<th>$value</th>";
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
} elseif ($mode == 'invoice') {
  $landscape = 'portrait';
  $h3 = "<span class='f30' style='font-weight: normal; color:blue; letter-spacing: 5px'>INVOICE</span>";
  $tanggal_periksa_header = '';
} elseif ($mode == 'approv') {
  $h3 = "<div class='f18 biru'>APPROV KESIMPULAN $NAMA_PERUSAHAAN</div>";
  $tr = $tr_approv;

  $arr_head = [
    'NO',
    'NO. MCU',
    'NAMA',
    'GENDER',
    'KELUHAN',
    'KESIMPULAN FISIK',
    'DARAH LENGKAP',
    'URINE',
    'RONTGEN',
    'KESIMPULAN',
    'KONSULTASI',
    'REKOMENDASI',
    'PUBLISH',
  ];

  // sementara untuk SMK-TB
  if ($id_perusahaan == 27) {
    $arr_head = [
      'NO',
      'NO. MCU',
      'NAMA',
      'GENDER',
      'KELUHAN',
      'MCU FISIK',
      'KIMIA DARAH',
      'DARAH LENGKAP',
      'URINE',
      'RONTGEN',
      'KESIMPULAN',
      'KONSULTASI',
      'REKOMENDASI',
      'PUBLISH',
    ];
  }

  $th = '';
  foreach ($arr_head as $key => $value) {
    $th .= "<th>$value</th>";
  }
} else {
  $h3 = 'BELUM ADA DESAIN JUDUL';
  $NAMA_PERUSAHAAN = '';
}




$tag_form = '';
$end_form = '';
$btn_print = "<button class='btn btn-primary' onclick=window.print()>Print</button>";
$btn_pdf = "<a target=_blank href='pdf/?id_perusahaan=$id_perusahaan&tanggal_periksa=$tanggal_periksa' class='btn btn-success' onclick='return confirm(`Download PDF`)'>Download PDF Semua Pasien</a>";
$btn_submit  = '';
$sub_h = "<div class='tengah m2 abu f14'>Preview Rekap per Perusahaan</div>";
if ($mode == 'approv') {
  $sub_h = '';
  $tag_form = '<form method=post>';
  $end_form = '</form>';
  $btn_print = '';
  $btn_pdf = '';
  $btn_submit = "<button class='btn btn-primary w-100' type=submit name=btn_submit>Submit Kesimpulan</button>";
}

# ============================================================
# FINAL ECHO
# ============================================================

echo "
  $sub_h
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
          </table>";
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
    </div>
  </div>
";
