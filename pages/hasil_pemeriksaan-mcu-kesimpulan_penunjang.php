<?php
# ============================================================
# PEMERIKSAAN
# ============================================================
$kelainan = [];
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
      $option_default = $d['option_default'];

      // jika punya option default
      if ($option_default) {
        if ($option_default != $arr_id_detail[$id_detail]) {
          // echo "<br> $d[nama_pemeriksaan] $d[label] $arr_id_detail[$id_detail] $d[option_default]";
          $sub_li .= "<li><span class=column>$d[label]:</span> <span class='consolas red'>$arr_id_detail[$id_detail]</span></li>";
        }
      }
    }
  }

  $sub_ul = $sub_li ? "<ul>$sub_li</ul>" : "<span class='consolas'>dalam batas normal</span>";

  $li .= "<li><span class=column>$arr_pemeriksaan[$id_pemeriksaan]:</span> $sub_ul</li>";
}


blok_hasil('PEMERIKSAAN PENUNJANG', "<ul>$li</ul>");
