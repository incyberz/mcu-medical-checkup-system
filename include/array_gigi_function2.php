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

$arr_posisi = ['atas', 'bawah'];
$i = 0;
$j = 1;
foreach ($arr_posisi as $posisi) {
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
    $gigi_bermasalah = $simbol == '.' ? '' : 'gigi_bermasalah';
    $simbol = $simbol == '.' ? ' ' : $simbol;
    $tmp_value = "<div class='tmp_value bg-red hideit' id=tmp_value__$i>$value</div>";
    $nn[$posisi] .= "<td width=6% style='background: #ddd !important'>$NN<br>$pos</td>$td_space";
    $td[$posisi] .= "
      <td class='item_gigi pointer $gigi_bermasalah' id=item_gigi__$i >
        <div class='simbol' id=simbol__$i>$simbol</div>
        $tmp_value
      </td>
      $td_space
    ";

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

$tr = '';
foreach ($arr_penanda_gigi as $kode => $rv) {
  $tr .= "<tr class='penanda pointer' id=penanda__$rv[0]__$kode><td>$rv[0]</td><td>$rv[1]</td></tr>";
}
$tb_penanda_gigi = "<table>$tr</table>";

$blok_sub_input = "
  <style>
    .penanda_aktif {font-weight:bold; color:blue; margin: 5px; border: solid 1px #ccccff !important; background: linear-gradient(white,yellow)}
    .penanda td {padding: 2px 5px}
    .item_gigi:hover{background: #fcf}
    .penanda:hover{background: #fcf}
    .gigi_bermasalah{background:yellow !important}
  </style>
  <div class=row>
    <div class=col-lg-8>
      <table class='table table-bordered tengah' style='border: solid 3px black'>
        <tr>$nn[atas]</tr>
        <tr>$td[atas]</tr>
        <tr style='height:20px'><td colspan=100%  style='background:#ccc !important; padding:0'>&nbsp;</td></tr>
        <tr>$td[bawah]</tr>
        <tr>$nn[bawah]</tr>
      </table>
    </div>
    <div class='col-lg-4 f12 kiri'>
      <div class='biru mb1 tebal'>Pilih penanda lalu klik pada gigi:</div>
      $tb_penanda_gigi
      <div class='hideit bg-red f30'><span id=penanda>.</span></div>
      <div class='hideit bg-red f30'><span id=penanda_kode>1</span></div>
    </div>
  </div>

  <input id=array_gigi name=$id_detail class='form-control mb4 consolas f14 abu tengah hideit' value='$v[value]' readonly>

";

?>
<script>
  $(function() {
    $('.item_gigi').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let no = rid[1];
      console.log(aksi, no);
      let simbol = $('#penanda').text();
      let kode = $('#penanda_kode').text();

      if (simbol == '.') {
        simbol = '';
        $(this).removeClass('gigi_bermasalah');
      } else {
        $(this).addClass('gigi_bermasalah');
      }
      $('#tmp_value__' + no).text(kode);
      $('#simbol__' + no).text(simbol);

      // update input array
      let z = document.getElementsByClassName('tmp_value');
      let array_gigi = '';
      for (let i = 0; i < z.length; i++) {
        array_gigi += z[i].innerHTML + ',';
      }
      $('#array_gigi').val(array_gigi);
    });

    $('.penanda').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let simbol = rid[1];
      let kode = rid[2];
      $('#penanda').text(simbol);
      $('#penanda_kode').text(kode);
      $('.penanda').removeClass('penanda_aktif');
      $(this).addClass('penanda_aktif');
    });
  })
</script>