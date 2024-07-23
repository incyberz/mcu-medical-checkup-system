<?php
# ============================================================
# PEMERIKSAAN
# ============================================================

$kelainan = [];
foreach ($arr_id_pemeriksaan_penunjang as $id) {

  $kelainan[$id] = [];

  $s = "SELECT 
  a.nama as nama_pemeriksaan,
  b.label 
  FROM tb_pemeriksaan a 
  JOIN tb_pemeriksaan_detail b ON a.id=b.id_pemeriksaan 
  WHERE a.id=$id";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    die(div_alert('danger', "Pemeriksaan $arr_pemeriksaan[$id] belum mempunyai detail pemeriksaan"));
  } else {
    while ($d = mysqli_fetch_assoc($q)) {
      echo "<br> $d[nama_pemeriksaan] $d[label] ";
    }
  }
}


blok_hasil('PEMERIKSAAN PENUNJANG', "
  <ul>
    <li>
      <span class=column>LABORATORIUM</span> 
      <span class=hasil>Dalam batas normal ZZZ</span>
    </li>
    <li>
      <span class=column>RADIOLOGI</span> 
      <span class=hasil>Dalam batas normal ZZZ</span>
    </li>
    <li>
      <span class=column>SPIROMETRI</span> 
      <span class=hasil>Dalam batas normal ZZZ</span>
    </li>
    <li>
      <span class=column>AUDIOMETRI</span> 
      <span class=hasil>Dalam batas normal ZZZ</span>
    </li>
    <li>
      <span class=column>EKG</span> 
      <span class=hasil>Dalam batas normal ZZZ</span>
    </li>
  </ul>
");
