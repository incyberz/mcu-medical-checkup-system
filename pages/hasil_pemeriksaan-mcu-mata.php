<?php
$s = "SELECT * FROM tb_pemeriksaan_detail WHERE id_pemeriksaan=7 ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_mata = [];
while ($d = mysqli_fetch_assoc($q)) {
  $kika = $d['ki_ka'] ?? 'no_kika';
  $arr_mata[$kika][$d['id']] = [
    'label' => $d['label'],
    'satuan' => $d['satuan'],
  ];
}

$arr = ['no_kika', 'ki', 'ka'];
$arr_hasil_kika = [];
foreach ($arr as $kika) {
  $li = '';
  foreach ($arr_mata[$kika] as $key => $value) {
    $hasil = $arr_id_detail[$key];
    $li .= "<li><span class=column>$value[label]:</span> <span class=hasil>$hasil $value[satuan]</span></li>";
  }
  $arr_hasil_kika[$kika] = "<ul class='m0'>$li</ul>";
}

$str_hasil = "
  $arr_hasil_kika[no_kika]
  <div class='row mt1'>
    <div class=col-6>
      <div class='bordered p2'>
        <b>MATA KIRI</b>
        $arr_hasil_kika[ki]
      </div>
    </div>
    <div class=col-6>
      <div class='bordered p2'>
        <b>MATA KANAN</b>
        $arr_hasil_kika[ka]
      </div>
    </div>
  </div>
";

blok_hasil('PEMERIKSAAN MATA', $str_hasil);
