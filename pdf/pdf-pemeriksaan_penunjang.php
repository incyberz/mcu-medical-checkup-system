<?php
# ============================================================
# PROCESSING pemeriksaan_penunjang
# ============================================================
$pasien['pemeriksaan_penunjang'] = [];

$arr_id_pemeriksaan_penunjang = [];
$get_jenis = ''; // without GET data
$is_mcu = 0;
include '../pages/hasil_pemeriksaan-sql_pemeriksaan_detail.php';

$kelainan = [];
$li = '';
foreach ($arr_id_pemeriksaan_penunjang as $id_pemeriksaan) {


  $s_detail = "SELECT
  b.id as id_detail, 
  a.nama as nama_pemeriksaan,
  b.* 
  FROM tb_pemeriksaan a 
  JOIN tb_pemeriksaan_detail b ON a.id=b.id_pemeriksaan 
  WHERE a.id=$id_pemeriksaan";
  $q_detail = mysqli_query($cn, $s_detail) or die(mysqli_error($cn));
  $sub_li = '';
  if (!mysqli_num_rows($q_detail)) {
    die(div_alert('danger', "Pemeriksaan $arr_pemeriksaan[$id_pemeriksaan] belum mempunyai detail pemeriksaan"));
  } else {
    $kelainan_detail = [];
    while ($detail = mysqli_fetch_assoc($q_detail)) {

      $id_detail = $detail['id_detail'];
      $option_default = $detail['option_default'];
      $hasil = $arr_id_detail[$id_detail] ?? 0;

      if ($id_pemeriksaan == $id_pemeriksaan_ron and strpos($hasil, 'kesan_tambahan:')) { // exception for rontgen
        $arr = explode('kesan_tambahan: ', $hasil);
        $hasil = $arr[1];

        array_push($kelainan_detail,  "KESAN: $hasil");
      } elseif ($id_pemeriksaan == $id_pemeriksaan_kd and ($hasil === 0 || $hasil === '0')) {
        // skip zero value for DK (tidak diperiksa)
      } else { // non zero values
        // jika punya option default
        if ($option_default) {
          if ($id_detail == 129) {
            // exception for eritrosit - URINE - boleh nol
          } elseif (strpos(strtolower("salt$hasil"), 'dalam batas normal')) {
            // exception for hasil dalam batas normal
          } else {
            if ($option_default != $hasil) {
              // echo "<br> $detail[nama_pemeriksaan] $detail[label] $hasil $detail[option_default]";
              array_push($kelainan_detail, strtoupper($detail['label']) . ": $hasil");
            }
          }
        } else { // by range

          $normal_hi = $detail['normal_hi_l'];
          $normal_lo = $detail['normal_lo_l'];
          if ($gender == 'p' and $detail['normal_hi_p']) {
            $normal_hi = $detail['normal_hi_p'];
            $normal_lo = $detail['normal_lo_p'];
          }

          // $hl = '';
          if ($hasil < $normal_lo) { // LOW
            // $hl = "<span class='red bold'>LOW</span>";
            if ($id_pemeriksaan == $id_pemeriksaan_kd and ($hasil === 0 || $hasil === '0')) {
              // skip zero value for DK (tidak diperiksa)
            } else {
              array_push($kelainan_detail, strtoupper($detail['label']) . ": $hasil (LOW)");
            }
          } elseif ($hasil > $normal_hi) {
            // $hl = "<span class='red bold'>HIGH</span>";
            array_push($kelainan_detail, strtoupper($detail['label']) . ": $hasil (HIGH)");
          }


          // $sub_li .= $hl ? "<li><span class=column>$detail[label]:</span> <a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$ARR_JENIS[$id_pemeriksaan]&id_pemeriksaan=$id_pemeriksaan'>$hl</a></li>" : '';
        }
      } // end else if non zero values
    }
  }


  // $sub_ul = $sub_li ? "<ul>$sub_li</ul>" : "<span class='consolas'>dalam batas normal</span>";
  if ($kelainan_detail) {
    $kelainan[$arr_pemeriksaan[$id_pemeriksaan]] = $kelainan_detail;
  } else {
    $kelainan[$arr_pemeriksaan[$id_pemeriksaan]] = 'dalam batas normal';
  }

  // $li .= "<li><span class=column>$arr_pemeriksaan[$id_pemeriksaan]:</span> $sub_ul</li>";
}

$pasien['pemeriksaan_penunjang'] = $kelainan;
