<?php
$mode = $_GET['mode'] ?? 'detail';
if ($mode == 'kirim_link') {
  include 'rekap_perusahaan-kirim_link.php';
  exit;
}
include 'pages/hasil_pemeriksaan-styles.php';
include 'include/arr_kesimpulan.php';
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






// ZZZ VISUS MATA > 20/20 KONSUL KE DOKTER MATA
// ZZZ URINE ERROR


# ============================================================
# PROCESSORS
# ============================================================
if (isset($_POST['btn_submit'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';
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
  set_h2('Pilih Perusahaan');
  $s = "SELECT 
  a.id,
  a.nama,
  (
    SELECT p.awal_periksa FROM tb_hasil_pemeriksaan p
    JOIN tb_pasien q ON p.id_pasien = q.id 
    JOIN tb_harga_perusahaan r ON q.id_harga_perusahaan=r.id 
    WHERE r.id_perusahaan=a.id 
    ORDER BY p.awal_periksa DESC LIMIT 1
    ) last_active
  FROM tb_perusahaan a 
  ORDER BY last_active DESC, a.nama";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  $i = 0;
  while ($d9 = mysqli_fetch_assoc($q)) {
    $i++;
    if (strtotime($d9['last_active']) < strtotime('-1 years')) continue;

    $last_active_show = hari_tanggal($d9['last_active']) . ' | ' . eta2($d9['last_active']);
    $tr .= "
      <tr>
        <td>$i</td>
        <td>
          <a href='?rekap_perusahaan&id_perusahaan=$d9[id]'>
            $d9[nama] $img_next
          </a>  
        </td>
        <td>$last_active_show</td>
      </tr>
    ";
  }
  echo "
    <table class='table th_toska'>
      <thead>
        <th>No</th>
        <th>Perusahaan</th>
        <th>Last Active $img_filter</th>
      </thead>
      $tr
    </table>
  ";
  exit;
} else {
  $s = "SELECT * FROM tb_perusahaan WHERE id=$id_perusahaan";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $perusahaan = mysqli_fetch_assoc($q);
}
$link_approv = "<a href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=approv'>Mode Approv Kesimpulan</a>";
$link_preview = "<a href='?rekap_perusahaan&id_perusahaan=$id_perusahaan'>Preview untuk Perusahaan</a>";
if ($mode == 'approv') {
  $link = $link_preview;
  $sub_h = 'Mode Approv Kesimpulan';
} else {
  $link = $link_approv;
  $sub_h = 'Preview untuk Perusahaan';
}
set_h2('Rekap Pemeriksaan', "
  $sub_h | $link 
  <div class='mt2'>
    <a class='btn btn-sm btn-success' href='?rekap_perusahaan&id_perusahaan=1&mode=kirim_link'>Kirim Link Pasien ke HRD</a>
  </div>
  <a href='https://youtu.be/AkDSnkaBMFc' target=_blank >Lihat Tutorial </a>
");












# ============================================================
# MAIN SELECT
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
JOIN tb_harga_perusahaan c ON a.id_harga_perusahaan=c.id 
WHERE (a.status = 10) -- SELESAI PEMERIKSAAN  
AND c.id_perusahaan=$id_perusahaan 
ORDER BY a.nama 
";

$q9 = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_rekap = mysqli_num_rows($q9);
$tr = '';
$tr_approv = '';
$i = 0;
$jumlah_verif = 0;
$awal_periksa = '';
$nav = div_alert('info', "Belum ada Pasien yang Selesai Pemeriksaan | <a href='?cari_pasien'>Lobby Pasien</a>");
if (mysqli_num_rows($q9)) {
  while ($d9 = mysqli_fetch_assoc($q9)) {
    $i++;
    if ($d9['approv_date']) $jumlah_verif++;
    $jenis = strtolower($d9['jenis']);
    $status = $d9['status'];
    $id_pasien = $d9['id_pasien'];
    $gender = strtolower($d9['gender']);

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

    $hasil_lab = [];
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
              if ($option_default != $hasil) {
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
                // $hl = '<span class="red bold">H</span>';
                // $hl = '<span class="red bold">L</span>';
              } else {
                die(div_alert('danger', "Invalid batasan nilai pada detail pemeriksaan. id_detail: $id_detail"));
                // $link_edit = "<a href='?manage_pemeriksaan_detail&id_detail=$id_detail&mode=batasan' target=_blank>Manage</a>";
                // $nilai_normal = '<span class="red bold">invalid</span> ';
                // $hl = '<span class="red bold">???</span>';
                // if (!$lo) $nilai_normal .= "<br><i class='red f10'>[Normal value] null atau [Nilai minimum] batas normal masih kosong</i> | $link_edit";
                // if (!$hi) $nilai_normal .= "<br><i class='red f10'>[Normal value] null atau [Nilai maximum] batas normal masih kosong</i> | $link_edit";
                // if ($lo < $hi) $nilai_normal .= "<br><i class='red f10'>Nilai minimum > nilai maksimum</i> | $link_edit";
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

    $keluhan = $d9['keluhan'] ?? 'tidak ada';
    $kesimpulan = $hasil_at_db['hasil'] ? $arr_kesimpulan[$hasil_at_db['hasil']] : $belum_ada;
    $konsultasi = $hasil_at_db['konsultasi'] ?? '-';

    if ($hasil_at_db['rekomendasi']) {
      $rekomendasi = $hasil_at_db['rekomendasi'];
    } elseif (!$hasil_at_db['rekomendasi']) {
      if ($hasil_at_db['hasil'] == 1 || $hasil_at_db['hasil'] == 2) {
        $rekomendasi = 'Dapat bekerja sesuai bidangnya';
      } else {
        $rekomendasi = ($hasil_at_db['hasil'] === '0' || $hasil_at_db['hasil'] === 0) ? 'lakukan pemeriksaan kesehatan lanjutan' :
          $hasil_at_db['rekomendasi'];
      }
    }


    $kesimpulan_fisik = $hasil_at_db['kesimpulan_fisik'] ?? "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$d9[id_pasien]&jenis=mcu'>$belum_ada</a>";

    $arr_konsultasi = [];
    if (strpos("salt$kesimpulan_fisik", 'obese') || strpos("salt$kesimpulan_fisik", 'underweight')) array_push($arr_konsultasi, 'dokter ahli gizi');
    if (strpos("salt$kesimpulan_fisik", 'gigi')) array_push($arr_konsultasi, 'dokter gigi');
    if ($hasil_lab['HEMA'] != 'normal' || $hasil_lab['URINE'] != 'normal') array_push($arr_konsultasi, 'dokter umum');
    if ($hasil_lab['RONTGEN'] != 'normal') array_push($arr_konsultasi, 'dokter paru');
    if ($arr_id_detail[14] > 20 || $arr_id_detail[142] > 20) array_push($arr_konsultasi, 'dokter mata');


    if (!$arr_konsultasi) {
      $konsultasi = '-';
    } elseif (count($arr_konsultasi) == 1) {
      $konsultasi = 'konsultasi ke ' . $arr_konsultasi[0];
    } elseif (count($arr_konsultasi) == 2) {
      $konsultasi = 'konsultasi ke ' . $arr_konsultasi[0] . ' dan ' . $arr_konsultasi[1];
    } else {
      $konsultasi = 'konsultasi ke ' . implode(', ', $arr_konsultasi);
    }



    if ($mode == 'approv') {


      // auto checked kesimpulan
      $blok_radio = '';
      foreach ($arr_kesimpulan as $key => $value) {
        $checked = ($key == 1 and !$arr_konsultasi and $kesimpulan_fisik == '-') ? 'checked' : '';
        $hide_radio = ($key == 1 and  $arr_konsultasi) ? 'hideit' : '';
        $blok_radio .= "<div class='$hide_radio'><label><input type=radio name=hasil__$id_pasien value=$key $checked> $value</label></div>";
      }
      $gradasi_merah = $kesimpulan == $belum_ada ? 'gradasi-merah' : '';
      $hideit = $kesimpulan == $belum_ada ? '' : 'hideit';
      $blok_radio = "<div class='$hideit' id=blok_radio$id_pasien>$blok_radio</div>";
      $kesimpulan = "<div class='mb1 bold '><span class=btn_aksi id=blok_radio$id_pasien" . "__toggle>$kesimpulan</span></div>$blok_radio";


      // $konsultasi = "<textarea name=konsultasi__$id_pasien rows=5>$konsultasi</textarea>";
      // $rekomendasi = "<textarea name=rekomendasi__$id_pasien rows=5>$rekomendasi</textarea>";

      $gender = strtoupper($d9['gender']);
      if ($gender == 'L') {
        $is_haid_show = '';
      } else {
        $is_haid_show = $d9['is_haid'] === null ? 'haidh: no-data' : 'sedang haidh';
        $is_haid_show = "<div class=red>$is_haid_show</div>";
      }

      $hasil_hema = $hasil_lab['HEMA'] == 'normal' ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=HEM'>$hasil_lab[HEMA]</a>";
      $hasil_urine = $hasil_lab['URINE'] == 'normal' ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=URI'>$hasil_lab[URINE]</a>";
      $hasil_rontgen = $hasil_lab['RONTGEN'] == 'normal' ? 'normal' : "<a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=RON'>$hasil_lab[RONTGEN]</a>";

      $tr_approv .= "
        <tr>
          <td>MCU-$d9[id_pasien]</td>
          <td>$d9[nama_pasien]</td>
          <td>$gender$is_haid_show</td>
          <td><span class=hideit>KELUHAN</span>$keluhan</td>
          <td id='kesFis'><span class=hideit>KESIMPULAN FISIK</span>$kesimpulan_fisik</td>
          <td><span class=hideit>LAB-HEMA</span>$hasil_hema</td>
          <td><span class=hideit>LAB-URINE</span>$hasil_urine</td>
          <td><span class=hideit>RONTGEN</span>$hasil_rontgen</td>
          <td class='$gradasi_merah'><span class=hideit>KESIMPULAN</span>$kesimpulan</td>
          <td><span class=hideit>KONSULTASI</span>$konsultasi</td>
          <td><span class=hideit>REKOMENDASI</span>$rekomendasi</td>        
        </tr>
      ";
    } else {

      $buta_show = 'tidak buta warna';
      if ($arr_id_detail[11] < 7) $buta_show = "<span class=red>parsial</span>";
      if ($arr_id_detail[11] < 4) $buta_show = "<span class=red>buta warna</span>";

      $status_gizi = '';
      foreach ($batas_imt as $batas => $v) {
        $status_gizi = $v;
        if ($batas > $imt) break;
      }

      $tr .= "
        <tr>
          <td>MCU-$d9[id_pasien]</td>
          <td>$d9[nama_pasien]</td>
          <td>$d9[tanggal_lahir]</td>
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
          <td><span class=hideit>LAB-HEMA</span>$hasil_lab[HEMA]</td>
          <td><span class=hideit>LAB-URINE</span>$hasil_lab[URINE]</td>
          <td><span class=hideit>RONTGEN</span>$hasil_lab[RONTGEN]</td>
          <td><span class=hideit>KESIMPULAN</span>$kesimpulan</td>
          <td><span class=hideit>KONSULTASI</span>$konsultasi</td>
          <td><span class=hideit>REKOMENDASI</span>$rekomendasi</td>        
        </tr>
      ";
    }
  } // end while
}

$arr_head = [
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
  'LAB-HEMA',
  'LAB-URINE',
  'RONTGEN',
  'KESIMPULAN',
  'KONSULTASI',
  'REKOMENDASI',
];

$th = '';
foreach ($arr_head as $key => $value) {
  $th .= "<th>$value</th>";
}


?>
<style>
  .kertas {
    width: 29.7cm;
    /* height: 21cm; */
    padding: 1cm;
    font-size: 9px;
    box-shadow: 0 0 5px gray;
  }

  .kertas td,
  .kertas th {
    padding: 5px;
  }

  th {
    text-align: center;
    background: #cff !important;
    vertical-align: middle;
  }
</style>
<?php
# ============================================================
# FINAL ECHO REKAP
# ============================================================
$NAMA = strtoupper($perusahaan['nama']);
$tanggal = hari_tanggal($awal_periksa, 1, 0, 0);
$h3 = "REKAPITULASI HASIL MEDICAL CHECKUP";
if ($mode == 'approv') {
  $h3 = "<div class='f18 biru'>APPROV KESIMPULAN</div>";

  $tr = $tr_approv;

  $arr_head = [
    'NO. MCU',
    'NAMA',
    'GENDER',
    'KELUHAN',
    'KESIMPULAN FISIK',
    'LAB-HEMA',
    'LAB-URINE',
    'RONTGEN',
    'KESIMPULAN',
    'KONSULTASI',
    'REKOMENDASI',
  ];

  $th = '';
  foreach ($arr_head as $key => $value) {
    $th .= "<th>$value</th>";
  }
}




$form = '';
$end_form = '';
$btn_print = "<button class='btn btn-primary' onclick=window.print()>Print</button>";
$btn_submit  = '';
$sub_h = "<div class='tengah m2 abu f14'>Preview Rekap per Perusahaan</div>";
if ($mode == 'approv') {
  $sub_h = '';
  $form = '<form method=post>';
  $end_form = '</form>';
  $btn_print = '';
  $btn_submit = "<button class='btn btn-primary w-100' type=submit name=btn_submit>Submit Kesimpulan</button>";
}

# ============================================================
# FINAL ECHO
# ============================================================

echo "
  <style>
    #kesFis ul{
      padding-left:10px;
      margin: 0;
    }
  </style>
  $sub_h
  <div class='flex flex-center'>
    <div class='kertas bg-white'>
      <div class='tengah mb2'>
        <div>$img_header_logo</div>
        <div class='border-bottom mb2 pb2 f12 mt1'>Tambun Business Park Blok C12 Tambun - Bekasi<br>Telp.(021) 29487893</div>

        <h3 class='f14 bold'>$h3 $NAMA</h3>
        <div class='f10 bold'>$tanggal</div>
      </div>
      $form
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
      $end_form
    </div>
  </div>
  <div class='tengah m2'>
    $btn_print
  </div>
";
