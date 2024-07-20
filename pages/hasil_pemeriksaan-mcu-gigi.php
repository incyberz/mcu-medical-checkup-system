<?php
$arr_penanda_gigi = [];
$tb_penanda_gigi = '';
include 'include/arr_penanda_gigi.php';

$tmp = explode(',', $arr_id_detail[94]);
$rg = [];
for ($i = 0; $i < 32; $i++) {
  if ($i < 16) {
    $rg['atas'][$i] = $tmp[$i];
  } else {
    $rg['bawah'][$i] = $tmp[$i];
  }
}

$arr = ['atas', 'bawah'];
$i = 0;
$j = 1;
foreach ($arr as $posisi) {
  $td[$posisi] = '';
  $nn[$posisi] = '';
  $pos = 8;
  $increment = -1;
  foreach ($rg[$posisi] as $key => $value) {
    $i++;
    $NN = 1;
    if ($i > 8) $NN = 2;
    if ($i > 16) $NN = 4;
    if ($i > 24) $NN = 3;
    $td_space = '';
    if ($i == 8 || $i == 24) $td_space = '<td style="background:#ccc !important">&nbsp;</td>';

    $simbol = simbol_gigi(abs($value));
    $yellow = $simbol == '.' ? ' ' : 'style="background: yellow !important"';
    $simbol = $simbol == '.' ? ' ' : $simbol;
    $nn[$posisi] .= "<td width=6% style='background: #ddd !important'>$NN<br>$pos</td>$td_space";
    $td[$posisi] .= "<td $yellow>$simbol</td>$td_space";

    if ($i > 24) {
      $pos++;
    } elseif ($i > 16) {
      if ($pos > 1) $pos--;
    } elseif ($i > 8) {
      $pos++;
    } elseif ($i > 0) {
      if ($pos > 1) $pos--;
    }
  }
}

$str_hasil = "
  <div class=row>
    <div class=col-8>
      <table class='table table-bordered tengah' style='border: solid 3px black'>
        <tr>$nn[atas]</tr>
        <tr>$td[atas]</tr>
        <tr style='height:20px'><td colspan=100%  style='background:#ccc !important; padding:0'>&nbsp;</td></tr>
        <tr>$td[bawah]</tr>
        <tr>$nn[bawah]</tr>
      </table>
    </div>
    <div class=col-4>
      Tanda:
      $tb_penanda_gigi
    </div>
  </div>
";

blok_hasil('PEMERIKSAAN GIGI', $str_hasil);
