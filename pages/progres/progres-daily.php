<?php
# ============================================================
# SELECT MODUL
# ============================================================
$opt = '';
$s = "SELECT * FROM tb_progres_modul ORDER BY nomor,date_created ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $opt .= "<option value='$d[id]'>$d[modul]</option>";
}
$select_modul = "<select name=id_modul class='form-control form-control-sm'>$opt</select>";


# ============================================================
# MINIMUM DATE OF REVISIONS
# ============================================================
// $s = "SELECT a.*,
// (SELECT COUNT(1) FROM tb_progres_task WHERE id_fitur=a.id) count_rev  
// FROM tb_progres_fitur a ORDER BY count_rev";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// while ($d = mysqli_fetch_assoc($q)) {
//   // $id=$d['id'];
//   echo "<br>$d[id] - $d[count_rev]";
//   if (!$d['count_rev']) {
//     $s2 = "INSERT INTO tb_progres_task (
//       id_fitur,
//       nama,
//       request_by,
//       keterangan,
//       last_update,
//       date_created,
//       href
//     ) VALUES (
//       '$d[id]',
//       '$d[nama] - FirstRev',
//       '$d[request_by]',
//       '$d[keterangan]',
//       '$d[last_update]',
//       '$d[date_created]',
//       '$d[href]'

//     )";
//     $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
//     // die($s2);
//   }
// }
// exit;

# ============================================================
# MINIMUM DATE OF REVISIONS
# ============================================================
$s = "SELECT
  (SELECT DATE(last_update) FROM tb_progres_task ORDER BY last_update LIMIT 1) awal_rev,
  (SELECT DATE(last_update) FROM tb_progres_task ORDER BY last_update DESC LIMIT 1) akhir_rev
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$awal_rev = $d['awal_rev'];
$akhir_rev = $d['akhir_rev'];



$d = intval(date('d'));
$m = intval(date('m'));
$y = date('Y');

# ============================================================
# MAIN LOOP FROM TODAY DATE
# ============================================================
$durasi_hari = durasi_hari($awal_rev, $akhir_rev);
// die("$durasi_hari, $awal_rev, $akhir_rev");
$kemarin = $today;
$no = 0;
for ($i = $durasi_hari; $i > 0; $i--) {
  $s = "SELECT * FROM tb_progres_task WHERE last_update >= '$kemarin' AND last_update <= '$kemarin 23:59:59' ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if ($i == $durasi_hari || mysqli_num_rows($q)) {
    $no++;
    echo "<br>$no $kemarin ";
  }

  $kemarin = date('Y-m-d', strtotime("-1 day", strtotime($kemarin)));
}
