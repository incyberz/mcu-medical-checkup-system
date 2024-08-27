<?php
# ============================================================
# TOTAL TASK 
# ============================================================
// $s = "SELECT status FROM tb_progres_task";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// $total_task = mysqli_num_rows($q);

// # ============================================================
// # STATUS & COUNT STATUS
// # ============================================================
// $arti_status = [];
// $count_status = [];
// $percent_task = [];
// $s = "SELECT a.*,
//   (SELECT count(1) FROM tb_progres_task WHERE status=a.status) count_status 
//   FROM tb_progres_status a";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// while ($d = mysqli_fetch_assoc($q)) {
//   $arti_status[$d['status']] = $d['arti'];
//   $count_status[$d['status']] = $d['count_status'];
//   $percent_task[$d['status']] = $d['count_status']
//     ? round($d['count_status'] / $total_task * 100, 2)
//     : 0;
// }
// $arti_status['all'] = 'All';
// $count_status['all'] = $total_task;


$tr_task_count = '';
$bg = ['red', '#cc5', '#caf', '#aaf', '#5f5', '#0f0'];
foreach ($count_status as $key => $value) {
  if ($key == 'all') continue;
  $persen = $percent_task[$key];
  $grafik = "<div class='p1 f10 br5' style='width:$persen%;background:$bg[$key]'>$persen%</div>";
  $tr_task_count .= "
    <tr>
      <td>$key</td>
      <td>$arti_status[$key]</td>
      <td>$value</td>
      <td>$grafik</td>
    </tr>
  ";
}

echo "
  <div class='mb2 tengah'>$total_task Task</div>
  <div id=fitur_info class='hideito wadah gradasi-kuning'>
    <table class='table td_trans th_toska'>
      <thead>
        <th>Status Task</th>
        <th>Arti Status</th>
        <th>Count</th>
        <th>Percent</th>
      </thead>
      $tr_task_count
    </table>
  </div>
";
