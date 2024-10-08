<?php
$judul = "Progress dan Request Fitur";
$nama_hari = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

include 'progres-styles.php';
$arr_mode = [
  'daily' => 'Project Daily Task',
  'hirarki' => 'Hirarki Fitur System',
  'sort' => 'Sort Penomoran Modul',
  'progres' => 'Task Progres Percentage',
];

# ============================================================
# GET PARAMS | POST HANDLER
# ============================================================
$mode = $_GET['mode'] ?? 'daily';
$status = $_GET['status'] ?? 'all';
$id_modul = $_GET['id_modul'] ?? null;
$tanggal = $_GET['tanggal'] ?? '';
$get_tanggal = $tanggal;
$post_task = $_POST['task'] ?? null;
$post_keterangan = $_POST['keterangan'] ?? null;
$post_today = $_POST['date_created'] ?? $today;
$get_params = '';
foreach ($_GET as $key => $value) {
  if ($value !== '') {
    $get_params .= "&$key=$value";
  }
}

set_title("Progres - $mode mode");
$nav_mode = '';
foreach ($arr_mode as $k => $v) {
  if ($v) {
    $slash = $nav_mode ? ' | ' : '';
    if ($k == $mode) {
      $nav_mode .= "$slash<span class='abu '>$k</span>";
      $judul = $v;
    } else {
      $nav_mode .= "$slash<a href='?progres&id_modul=$id_modul&mode=$k' class=''>$k</a>";
    }
  }
}

# ============================================================
# DEV USERNAMES 
# ============================================================
$dev_usernames = ['insho'];
$as_dev = in_array($username, $dev_usernames) ? 1 : 0;


# ============================================================
# TOTAL TASK 
# ============================================================
$s = "SELECT status FROM tb_progres_task";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$total_task = mysqli_num_rows($q);

# ============================================================
# STATUS & COUNT STATUS
# ============================================================
$arti_status = [];
$count_status = [];
$percent_task = [];

$sql_tanggal = $get_tanggal ? "(date_created >= '$get_tanggal' AND date_created <= '$get_tanggal 23:59:59')" : 1;

$s = "SELECT a.*,
  (
    SELECT count(1) FROM tb_progres_task 
    WHERE status=a.status
    AND $sql_tanggal
    ) count_status 
  FROM tb_progres_status a 
  ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $arti_status[$d['status']] = $d['arti'];
  $count_status[$d['status']] = $d['count_status'];
  $percent_task[$d['status']] = $d['count_status']
    ? round($d['count_status'] / $total_task * 100, 2)
    : 0;
}
$arti_status['all'] = 'All';
$count_status['all'] = $total_task;


# ============================================================
# ICON STATUS
# ============================================================
$icon_status = [];
$icon_status[0] = $img_warning;
$icon_status[1] = img_icon('search');
$icon_status[2] = img_icon('revision2');
$icon_status[3] = $img_check;
$icon_status[4] = img_icon('release2');
$icon_status[5] = img_icon('stable');


# ============================================================
# COLOR STATUS
# ============================================================
$color_status = [];
$color_status[0] = 'red';
$color_status[1] = 'red';
$color_status[2] = 'red';
$color_status[3] = 'blue';
$color_status[4] = 'green';
$color_status[5] = 'green';


# ============================================================
# NAV STATUS FOR DAILY ONLY
# ============================================================
$nav_status = '';
$sql_status = '1';
if ($mode == 'daily') {
  foreach ($count_status as $k => $v) {
    $slash = $nav_status ? ' | ' : '';
    if ($status === strval($k)) {
      $nav_status .= "$slash<span class='darkblue bold f14'>$arti_status[$k] ($v)</span>";
    } elseif (!$v) {
      // $nav_status .= "$slash<span class='abu miring f10'>$arti_status[$k]</span>";
    } else {
      $nav_status .= "$slash<a href='?progres&id_modul=$id_modul&mode=$mode&status=$k'>$arti_status[$k] ($v)</a>";
    }
  }

  if ($status != 'all') {
    $sql_status = "a.status = '$status'";
  }
}

# ============================================================
# CLEAR FILTER TANGGAL  
# ============================================================
$clear_filter_tanggal = '';
if ($get_tanggal !== '') {
  $get_tanggal_show = hari_tanggal($get_tanggal, 0, 0, 0);
  $clear_filter_tanggal = "<a href='?progres&id_modul=$id_modul&mode=$mode&status=$status'>Clear Filter Tanggal [ $get_tanggal_show ]</a>";
}


echo "
<h2 class='darkblue mb2 f20 tengah'>$judul</h2>
  <div class='f14 tengah mb2'>$nav_mode</div>
  <div class='f12 tengah mb2'>$nav_status</div>
  <div class=' tengah mb2'>$clear_filter_tanggal</div>
";




# ============================================================
# STATIC VARIABLES
# ============================================================
$img_arti[1] = '<img src="assets/img/icon/check_brown.png" height=20px>';
$img_arti[2] = '<img src="assets/img/icon/check_pink.png" height=20px>';
$img_arti[3] = '<img src="assets/img/icon/check_blue.png" height=20px>';
$img_arti[4] = "$img_check";
$img_arti[5] = "$img_check $img_check";

$img_gray = img_icon('gray');
$img_loading = "<img src='assets/img/gifs/loading.gif' height=25px>";

# ============================================================
# PROCESSORS
# ============================================================
include 'progres-processors.php';

# ============================================================
# DESCRIBING TABLE
# ============================================================
include 'progres-describing_tables.php';






















$tr_daily = '';
if ($mode == 'daily') {
  # ============================================================
  # DAILY MODE
  # ============================================================
  include 'progres-daily.php';
} elseif ($mode == 'progres') {
  # ============================================================
  # PROGRES PERCENTAGE
  # ============================================================
  include 'progres-percentage.php';
} elseif ($mode == 'hirarki') {
  # ============================================================
  # HIRARKI MODE
  # ============================================================
  include 'progres-hirarki.php';
} elseif ($mode == 'sort') {
  # ============================================================
  # SORTING MODUL
  # ============================================================
  include 'progres-sort_modul.php';
} else { // undefined mode
  die(div_alert('danger', "belum ada handler untuk mode [$mode]"));
}


?>
<?php
