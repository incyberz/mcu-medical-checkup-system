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
      }
    }
  }

  $sub_ul = $sub_li ? "<ul>$sub_li</ul>" : "<span class='consolas'>dalam batas normal</span>";
  if ($kelainan_detail) {
    $kelainan[$arr_pemeriksaan[$id_pemeriksaan]] = $kelainan_detail;
  } else {
    $kelainan[$arr_pemeriksaan[$id_pemeriksaan]] = 'dalam batas normal';
  }

  $li .= "<li><span class=column>$arr_pemeriksaan[$id_pemeriksaan]:</span> $sub_ul</li>";
}

$pasien['pemeriksaan_penunjang'] = $kelainan;
