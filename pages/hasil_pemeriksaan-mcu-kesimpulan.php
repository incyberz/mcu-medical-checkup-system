<?php
$li = '';
$li2 = '';
foreach ($kesimpulan as $ket => $kes) {
  if (is_array($kes)) {
    $kes2 = join('', $kes);
    $kes2 = "<ul class=pl2>$kes2</ul>";
  } else {
    $kes2 = $kes;
  }
  $li .= "<li><span class=column>$ket:</span> <span class=hasil>$kes2</span></li>";

  $kes2 = strtolower($kes2);
  if ($kes2 == 'dalam batas normal' || $kes2 == 'dalam batas aman' || $kes2 == 'normal range' || strpos("salt$kes2", 'batas normal')) {
  } else {
    $li2 .= "<li><span class=column>$ket:</span> <span class=hasil>$kes2</span></li>";
  }
}
$hasil = "<ul>$li</ul>";
$hasil2 = $li2 ? "<ul>$li2</ul>" : "<i>-</i>";

blok_hasil('KESIMPULAN PEMERIKSAAN', $hasil);

# ============================================================
# UPDATE KESIMPULAN FISIK
# ============================================================
$hasil2 = str_replace("'", "\'", $hasil2);
$s = "UPDATE tb_hasil_pemeriksaan SET kesimpulan_fisik = '$hasil2' WHERE id_pasien=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
