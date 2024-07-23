<?php
// echo '<pre>';
// var_dump($kesimpulan);
// echo '</pre>';


$li = '';
foreach ($kesimpulan as $ket => $kes) {
  if (is_array($kes)) {
    $kes2 = join('', $kes);
    $kes2 = "<ul class=pl2>$kes2</ul>";
  } else {
    $kes2 = $kes;
  }
  $li .= "<li><span class=column>$ket:</span> <span class=hasil>$kes2</span></li>";
}
$hasil = "<ul>$li</ul>";

blok_hasil('KESIMPULAN PEMERIKSAAN', $hasil);
