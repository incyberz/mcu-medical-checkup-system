<?php
// get id_pemeriksaan by id_pasien and jenis ZZZ

// $id_pemeriksaan = 3;
// $s = "SELECT a.id as id_pemeriksaan a"
set_title('Hasil Lab - ' . $nama_pemeriksaan);

$get_jenis = $_GET['jenis'] ?? '';
if ($get_jenis) $get_jenis = strtolower($get_jenis);

# ============================================================
# DETAIL PEMERIKSAAN
# ============================================================
$s2 = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=$id_pemeriksaan";
$q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
$detail_header = '
  <div class=detail_header>PEMERIKSAAN</div>
  <div class=detail_header>HASIL</div>
  <div class=detail_header>H/L</div>
  <div class=detail_header>SATUAN</div>
  <div class=detail_header>NILAI NORMAL</div>
  <div class=detail_header>CATATAN</div>
';

// fix for eritrosit bernilai 0
if (!isset($arr_id_detail[129])) $arr_id_detail[129] = 0; // eritrosit

$detail = '';

// khusus URI
$detail2 = '';
$arr_uri_mikro = [128, 129, 130, 131, 132, 133];
$arr_hem_hjl = [103, 104, 105]; // hitung jenis leukosit

while ($d2 = mysqli_fetch_assoc($q2)) {
  if (strtolower($d2['label']) == 'separator') continue;
  $id_detail = $d2['id'];
  if ($id_detail == 106) continue; // laju endap darah
  $id_pemeriksaan = $d2['id_pemeriksaan'];
  $hasil = $arr_id_detail[$id_detail] ?? 0;

  $hasil =  strtolower($hasil);
  $normal_value = strtolower($d2['normal_value']);
  $lo = $d2['normal_lo_l'];
  $hi = $d2['normal_hi_l'];

  $hl = '-';
  if ($normal_value) {
    $hl = $hasil == $normal_value ? '-' : '<b class=red>abnormal</b>';
    $nilai_normal = $normal_value;
  } else { // tidak ada normal value || based on batasan
    if ($lo and $hi and $lo < $hi) {
      if ($d2['normal_lo_p'] and $d2['normal_hi_p']) {
        $nilai_normal = "
          L (" . floatval($d2['normal_lo_l']) . " - " . floatval($d2['normal_hi_l']) . "), 
          P (" . floatval($d2['normal_lo_p']) . " - " . floatval($d2['normal_hi_p']) . ") 
        ";
        if ($gender == 'p') {
          $lo = $d2['normal_lo_p'];
          $hi = $d2['normal_hi_p'];
        }
      } else {
        $nilai_normal =  floatval($d2['normal_lo_l']) . " - " . floatval($d2['normal_hi_l']);
      }
      if ($hasil < $lo) $hl = "<span class='red bold'>L</span>";
      if ($hasil > $hi) $hl = "<span class='red bold'>H</span>";
    } else {
      $link_edit = "<a href='?manage_pemeriksaan_detail&id_detail=$id_detail&mode=batasan' target=_blank>Manage</a>";
      $nilai_normal = '<span class="red bold">invalid</span> ';
      $hl = '<span class="red bold">???</span>';
      if (!$lo) $nilai_normal .= "<br><i class='red f10'>[Normal value] null atau [Nilai minimum] batas normal masih kosong</i> | $link_edit";
      if (!$hi) $nilai_normal .= "<br><i class='red f10'>[Normal value] null atau [Nilai maximum] batas normal masih kosong</i> | $link_edit";
      if ($lo < $hi) $nilai_normal .= "<br><i class='red f10'>Nilai minimum > nilai maksimum</i> | $link_edit";
    }
  }




  $satuan = (!$d2['satuan'] || $d2['satuan'] == 'satuan') ? '-' : $d2['satuan'];
  if (strtoupper($satuan) == 'LBP') $hasil = "0-$hasil";

  $blok = "
      <div class=miring>$d2[label]</div>
      <div>$hasil</div>
      <div>$hl</div>
      <div class=miring>$satuan</div>
      <div>$nilai_normal</div>
      <div>-</div>
    ";

  if ($get_jenis == 'uri' and in_array($id_detail, $arr_uri_mikro)) {
    $detail2 .= $blok;
  } elseif ($get_jenis == 'hem' and in_array($id_detail, $arr_hem_hjl)) {
    $detail2 .= $blok;
  } else {
    $detail .= $blok;
  }
}

if ($get_jenis == 'uri') {
  $blok_detail = "
    <h5 class='kiri f14 mt2 mb2 bold' style='letter-spacing: 2px; color: #4cc'>Makroskopik</h5>
    <div 
      class='f12 left border-bottom pb2' 
      style='display: grid; grid-template-columns: 25% 10% 8% 17% 25% auto'
    >
      $detail_header
      $detail
    </div>

    <h5 class='kiri f14 mt2 mb2 bold' style='letter-spacing: 2px; color: #4cc'>Mikroskopik</h5>
    <div 
      class='f12 left border-bottom pb2' 
      style='display: grid; grid-template-columns: 25% 10% 8% 17% 25% auto'
    >
      $detail2
    </div>
  ";
} elseif ($get_jenis == 'hem' || $get_jenis == 'gin') {
  $blok_detail = "
    <div 
      class='f12 left border-bottom pb2' 
      style='display: grid; grid-template-columns: 25% 10% 8% 17% 25% auto'
    >
      $detail_header
      $detail
    </div>
  ";
  if ($get_jenis == 'hem') {
    $blok_detail .= "
    <h5 class='kiri f14 mt2 mb2 bold' style='letter-spacing: 2px; color: #4cc'>Hitung Jenis Leukosit</h5>
    <div 
      class='f12 left border-bottom pb2' 
      style='display: grid; grid-template-columns: 25% 10% 8% 17% 25% auto'
    >
      $detail2
    </div>    
    ";
  }
} else {
  die(div_alert('danger', "get_jenis [$get_jenis] belum terdefinisi."));
}

echo "
  <h4 class='kiri biru f14 mt4 mb2 bold' style='letter-spacing: 2px; color: #4cc'>$jenis_pemeriksaan</h4>

  $blok_detail

  <div class='f12 left mt2 border-bottom pb2'>
    <div>Catatan:</div>
    <div>Hasil Ini harus di interpretasikan oleh dokter yang menangani untuk disesuaikan dengan klinisnya.</div>
    <style>#tb_note td{padding-right:10px}</style>
    <table class=mt2 id=tb_note>
      <tr><td>Note:</td><td>- H</td><td> : High</td></tr>
      <tr><td>&nbsp;</td><td>- L</td><td> : Low</td></tr>
    </table>
  </div>
";
