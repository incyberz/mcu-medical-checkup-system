<?php
// $s = "SELECT a.id, a.h1
// FROM tb_progres_h1 a ORDER BY a.nomor";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// $i = 0;
// $no_next_fitur = mysqli_num_rows($q) + 1;
// while ($d = mysqli_fetch_assoc($q)) {
//   $i++;
//   $btn = "<span class='btn btn-sm btn-primary' style='display:inline-block;margin:0 10px 0 5px'>$i</span>"; // : "<a class='btn btn-sm btn-info f10 miring' href='?progres&id_fitur=$d[id]&no=$i' >$i</a> ";
//   $nav .= $btn;
// }
$tr_subfitur_count = '';
$bg = ['red', '#cc5', '#caf', '#aaf', '#5f5', '#0f0'];
foreach ($count_status as $key => $value) {
  if ($key == 'all') continue;
  $persen = $percent_subfitur[$key];
  $grafik = "<div class='p1 f10 br5' style='width:$persen%;background:$bg[$key]'>$persen%</div>";
  $tr_subfitur_count .= "
    <tr>
      <td>$key</td>
      <td>$arti_status[$key]</td>
      <td>$value</td>
      <td>$grafik</td>
    </tr>
  ";
}

$nama_kerjaan = $d_sedang_dikerjakan['h1'] ?? '';

$sedang_dikerjakan = !$nama_kerjaan ? '' : "
  <div>Subfitur sedang dikerjakan: <b>$d_sedang_dikerjakan[h1]</b> $img_loading</div>
  <div class='f12 abu'>$d_sedang_dikerjakan[keterangan]</div>
  <div class='f12 abu'>Fitur : $d_sedang_dikerjakan[nama_fitur]</div>
";

echo "
  <div class='mb2 tengah'>$total_subfitur Subfitur</div>
  <div id=subfitur_info class='hideito wadah gradasi-kuning'>
    <table class='table td_trans th_toska'>
      <thead>
        <th>Status</th>
        <th>Arti Status</th>
        <th>Count</th>
        <th>Percent</th>
      </thead>
      $tr_subfitur_count
    </table>
    $sedang_dikerjakan
  </div>
";
