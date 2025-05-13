<?php
# ============================================================
# PEMERIKSAAN
# ============================================================
$kelainan = [];
// $ARR_JENIS = [
//   2 => 'DK',
//   3 => 'HEM',
//   20 => 'URI',
//   9 => 'RON',
// ];

$arr_lab = [
  20 => 'URINE',
  2 => 'DK',
  3 => 'HEMA',
  9 => 'RONTGEN'
];


$ARR_JENIS = [];

$s = "SELECT id,singkatan FROM tb_pemeriksaan";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $ARR_JENIS[$d['id']] = $d['singkatan']; // replace semua id pemeriksaan 
  $arr_lab[$d['id']] = $arr_lab[$d['id']] ?? $d['singkatan']; // add id yang belum
}



$hasil_lab = [];


$li = '';
foreach ($arr_id_pemeriksaan_penunjang as $id_pemeriksaan) {

  $kelainan[$id_pemeriksaan] = [];

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
      if ($id_detail == 106) continue; // laju endap darah
      if (!isset($arr_id_detail[$id_detail]) || $arr_id_detail[$id_detail] === null) continue;
      $option_default = $d['option_default'];
      $hasil = $arr_id_detail[$id_detail] ?? 0;

      // jika punya option default
      if ($option_default) {
        if ($id_detail == 129) {
          // exception for eritrosit - URINE - boleh nol
        } elseif (strpos(strtolower("salt$hasil"), 'dalam batas normal')) {
          // exception for hasil dalam batas normal
        } else {
          if ($option_default != $hasil) {
            // echo "<br> $d[nama_pemeriksaan] $d[label] $hasil $d[option_default]";
            $sub_li .= "
              <li>
                <span class=column>$d[label]:</span>
                <a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$ARR_JENIS[$id_pemeriksaan]&id_pemeriksaan=$id_pemeriksaan'>
                  <span class='consolas red'>$hasil</span>
                </a>
              </li>
            ";
          }
        }
      } else { // by range

        $normal_hi = $d['normal_hi_l'];
        $normal_lo = $d['normal_lo_l'];
        if ($gender == 'p' and $d['normal_hi_p']) {
          $normal_hi = $d['normal_hi_p'];
          $normal_lo = $d['normal_lo_p'];
        }

        $hl = '';
        if ($hasil < $normal_lo) { // LOW
          $hl = "<span class='red bold'>LOW</span>";
        } elseif ($hasil > $normal_hi) {
          $hl = "<span class='red bold'>HIGH</span>";
        }

        $sub_li .= $hl ? "<li><span class=column>$d[label]:</span> <a target=_blank href='?hasil_pemeriksaan&id_pasien=$id_pasien&jenis=$ARR_JENIS[$id_pemeriksaan]&id_pemeriksaan=$id_pemeriksaan'>$hl</a></li>" : '';
      }
    }
  }

  $sub_ul = $sub_li ? "<ul>$sub_li</ul>" : "<span class='consolas'>dalam batas normal</span>";

  $li .= "<li><span class=column>$arr_pemeriksaan[$id_pemeriksaan]:</span> $sub_ul</li>";
  $hasil_lab[$arr_lab[$id_pemeriksaan]] = $sub_li ? "<ul>$sub_li</ul>" : 'normal';
}


blok_hasil('PEMERIKSAAN PENUNJANG', "<ul>$li</ul>");
