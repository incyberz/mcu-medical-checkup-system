<?php
$judul = "Progress dan Request Fitur";
include 'progres-styles.php';
$arr_mode = [
  'daily' => 'Project Daily',
  'hirarki' => 'Hirarki Fitur System',
  'progres' => 'Progres Percentage',
];
$mode = $_GET['mode'] ?? 'daily';
$status = $_GET['status'] ?? 'all';
$id_fitur = $_GET['id_fitur'] ?? null;
set_title("Progres - $mode mode");
$nav_mode = '';
foreach ($arr_mode as $k => $v) {
  if ($v) {
    $slash = $nav_mode ? ' | ' : '';
    if ($k == $mode) {
      $nav_mode .= "$slash<span class='abu '>$k</span>";
      $judul = $v;
    } else {
      $nav_mode .= "$slash<a href='?progres&id_fitur=$id_fitur&mode=$k' class=''>$k</a>";
    }
  }
}

# ============================================================
# TOTAL SUBFITUR 
# ============================================================
$s = "SELECT status FROM tb_progres_sub";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$total_subfitur = mysqli_num_rows($q);

# ============================================================
# STATUS & COUNT STATUS
# ============================================================
$arti_status = [];
$count_status = [];
$percent_subfitur = [];
$s = "SELECT a.*,
  (SELECT count(1) FROM tb_progres_sub WHERE status=a.status) count_status 
  FROM tb_progres_sub_status a";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $arti_status[$d['status']] = $d['arti'];
  $count_status[$d['status']] = $d['count_status'];
  $percent_subfitur[$d['status']] = $d['count_status']
    ? round($d['count_status'] / $total_subfitur * 100, 2)
    : 0;
}
$arti_status['all'] = 'All';
$count_status['all'] = $total_subfitur;


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
      $nav_status .= "$slash<a href='?progres&id_fitur=$id_fitur&mode=$mode&status=$k'>$arti_status[$k] ($v)</a>";
    }
  }

  if ($status != 'all') {
    $sql_status = "a.status = '$status'";
  }
}

echo "
<h2 class='darkblue mb2 f20 tengah'>$judul</h2>
  <div class='f14 tengah mb2'>$nav_mode</div>
  <div class='f12 tengah mb2'>$nav_status</div>
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
  $get_id_fitur = $_GET['id_fitur'] ?? '';
  $get_no = $_GET['no'] ?? '';
  $sql_id_fitur = $get_id_fitur ? "a.id=$get_id_fitur" : '1';

  # ======================================================
  # NAV BY SUB PROGRES
  # ======================================================
  $s = "SELECT a.id, a.h1
  FROM tb_progres_h1 a ORDER BY a.nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $nav = "<a class='btn btn-sm btn-info f12' href='?progres' >All Fitur</a> ";
  $i = 0;
  $no_next_fitur = mysqli_num_rows($q) + 1;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $btn = $d['id'] == $get_id_fitur ? "<span class='btn btn-sm btn-primary' style='display:inline-block;margin:0 10px 0 5px'>$i</span>" : "<a class='btn btn-sm btn-info f10 miring' href='?progres&id_fitur=$d[id]&no=$i' >$i</a> ";
    $nav .= $btn;
  }
  $total_subfitur_show = "";



  echo "<div class='mb2 tengah'>$nav</div>";

  # ======================================================
  # MAIN SELECT FITUR
  # ======================================================
  $s = "SELECT a.id as id_fitur, a.* 
  FROM tb_progres_h1 a 
  WHERE $sql_id_fitur 
  ORDER BY nomor";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $tr = '';
  $i = $get_no ? ($get_no - 1) : 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_fitur = $d['id_fitur'];
    $id_toggle = "fitur$id_fitur" . '__toggle';

    # ======================================================
    # FOREACH FITUR KOLOM FOR EDITING FORM
    # ======================================================
    $tr_form = '';
    foreach ($colField as $key => $kolom) {
      if ($colNull[$key] == 'NO') {
        $sty_kolom = 'darkblue consolas f12 kanan';
        $required = 'required';
      } else {
        $required = '';
        $sty_kolom = 'gray consolas f12 kanan';
      }

      $sty_isi = '';
      $kolom_show = str_replace('_', ' ', $kolom);

      $tr_form .= "
        <tr>
          <td class='$sty_kolom' style='padding-right:10px'>$kolom_show</td>
          <td class='$sty_isi'>
            <input $required name=$kolom value='$d[$kolom]' class='form-control form-control-sm'>
          </td>
        </tr>
      ";
    }

    # ======================================================
    # SELECT SUB FITUR
    # ======================================================
    $s2 = "SELECT a.*,
    a.id as id_subfitur,
    (
      SELECT arti FROM tb_progres_sub_status 
      WHERE status=a.status) arti_subfitur, 
    (
      SELECT keterangan FROM tb_progres_sub_status 
      WHERE status=a.status) ket_status_subfitur 
    FROM tb_progres_sub a 
    WHERE a.id_fitur=$id_fitur 
    ";

    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $tr_sub = '<tr><td colspan=100% class="consolas f12 miring red">Belum ada subfitur</td></tr>';
    $j = 0;


    if (mysqli_num_rows($q2)) {
      $tr_sub = '';
      while ($d2 = mysqli_fetch_assoc($q2)) {
        $j++;
        $id_subfitur = $d2['id_subfitur'];
        $status_show = $d2['status'] ? $img_arti[$d2['status']] : $img_warning;

        $arti_subfitur = $d2['arti_subfitur'] ? $d2['arti_subfitur'] : 'Belum dikerjakan';
        $last_update = $d2['last_update'];

        for ($k = 0; $k <= 5; $k++) $dis[$k] = '';
        $dis[$d2['status']] = 'disabled';

        $form_delete_subfitur = $role != 'admin' ? '' :  "
          <form method=post class='mt1' target=_blank>
            <button onclick='return confirm(`Delete subfitur ini?`)' class='btn btn-danger btn-sm' name=btn_delete_subfitur value=$id_subfitur >Delete</button>
          </form>
        ";

        $tr_sub .= "
          <tr>
            <td class='sub_number'>$i.$j</td>
            <td>
              $d2[nama]
              <div class='abu f12 mt1'>$d2[keterangan]</div>
              $form_delete_subfitur
  
            </td>
            <td class='td_status_subfitur'>
              <div class='btn_aksi pointer' id=keterangan_subfitur_$id_subfitur" . "__toggle>
                $status_show
                <div class='abu f10 miring mt1'>$arti_subfitur</div>
                <div class='abu f10 miring mt1'>$last_update</div>
              </div>
              <div class='hideit f10 mt2' id=keterangan_subfitur_$id_subfitur>
                $d2[ket_status_subfitur]
                <form method=post class='mt2 f10'>
                  <div class=mb1>Set status:</div>
                  <button class='btn btn-danger btn_sm' name=btn_set_status value=0__$id_subfitur $dis[0]>0</button>
                  <button class='btn btn-warning btn_sm' name=btn_set_status value=1__$id_subfitur $dis[1]>1</button>
                  <button class='btn btn-warning btn_sm' name=btn_set_status value=2__$id_subfitur $dis[2]>2</button>
                  <button class='btn btn-info btn_sm' name=btn_set_status value=3__$id_subfitur $dis[3]>3</button>
                  <button class='btn btn-success btn_sm' name=btn_set_status value=4__$id_subfitur $dis[4]>4</button>
                  <button class='btn btn-success btn_sm' name=btn_set_status value=5__$id_subfitur $dis[5]>5</button>
                </form>
                <form method=post class='mt2 f10' target=_blank>
                  <div class=mb1>Set:</div>
                  <button class='btn btn-success btn_sm btn_sedang_dikerjakan' name=btn_sedang_dikerjakan value=$id_subfitur>Sedang dikerjakan</button>
                </form>
              </div>
            </td>
          </tr>
        ";
      }
    }

    # ======================================================
    # TR ADD SUB FITUR
    # ======================================================
    $j++;
    $tr_add_sub = "
      <tr>
        <td class='abu miring consolas f12 sub_number'>*$i.$j</td>
        <td colspan=100%>
          <span class='consolas green f12 bold btn_aksi pointer' id=form_subfitur$id_fitur" . "__toggle>+ Add Subfitur</span>
          <form method=post id=form_subfitur$id_fitur class='hideit mt1'>
            <table width=100%>
              <tr>
                <td>
                  <input class='form-control form-control-sm' name=new_subfitur required minlength=5 maxlength=30 placeholder='Subfitur Baru...'/>
                </td>
                <td>
                  <button class='btn btn-success btn-sm ml1' name=btn_add_subfitur value=$id_fitur>Add</button>
                </td>
              </tr>
              <tr>
                <td>
                  <textarea class='form-control form-control-sm' name=keterangan required minlength=20 maxlength=1000 placeholder='Keterangan...'></textarea>
                </td>
              </tr>
              <tr>
                <td>
                  <input class='form-control form-control-sm' name=link_akses minlength=2 maxlength=100 placeholder='Link akses...'/>
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    ";

    # ======================================================
    # FINAL TB-SUB OF LOOP OUTPUT
    # ======================================================
    $tb_sub = "
      <table class='table table-bordered gradasi-kuning'>
        $tr_sub 
        $tr_add_sub
      </table>
    ";

    $form_delete_fitur = $role != 'admin' ? '' :  "
      <form method=post class='mt1'>
        <button onclick='return confirm(`Delete fitur ini?`)' class='btn btn-danger btn-sm' name=btn_delete_fitur value=$id_fitur >Delete</button>
      </form>
    ";



    # ======================================================
    # FINAL LOOP OUTPUT
    # ======================================================
    $tr .= "
      <tr>
        <td>$i</td>
        <td>
          $d[h1] <span class=btn_aksi id=$id_toggle>$img_detail</span>
          <form method=post class='hideit wadah gradasi-kuning mt2' id=fitur$id_fitur>
            <div class='f10 abu consolas mb2'>FORM EDIT FITUR</div>
            <table>
              $tr_form
              <tr><td>&nbsp;</td><td colspan=100%><button class='btn btn-info btn-sm' name=btn_update_fitur value=$id_fitur>Update</button></td></tr>
            </table>
          </form>
          $form_delete_fitur 
        </td>
        <td>
          $tb_sub
        </td>
      </tr>
    ";
  }

  # ======================================================
  # TR ADD FITUR
  # ======================================================
  $tr_add = "
    <tr>
      <td class='abu f12 miring tengah'>*$no_next_fitur</td>
      <td colspan=100%>
        <form method=post>
          <div class=flexy>
            <div>
              <input class='form-control' name=new_fitur required minlength=5 maxlength=30/>
            </div>
            <div>
              <button class='btn btn-success' name=btn_add_fitur>Add Fitur</button>
            </div>
          </div>
        </form>
      </td>
    </tr>
  ";

  # ======================================================
  # FINAL TABLE OUTPUT
  # ======================================================
  echo "
    <table class='table'>
      <thead>
        <th>No</th>
        <th>Sub Divisi / Fitur</th>
        <th>Subfitur dan Status</th>
      </thead>
      $tr
      $tr_add
    </table>  
  ";
} else { // undefined mode
  die(div_alert('danger', "Invalid mode [$mode]"));
}


?>
<?php
