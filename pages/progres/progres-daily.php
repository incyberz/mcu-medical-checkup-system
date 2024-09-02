<?php
$img_take = img_icon('take');
$img_drop = img_icon('drop');

# ============================================================
# FILTER SQL STATUS  
# ============================================================
$sql_status = 1;
if ($status !== 'all') {
  $sql_status = "a.status = $status";
}











# ============================================================
# SELECT FITUR
# ============================================================
$opt = '<option value="0">--Pilih Fitur--</option>';
$s = "SELECT a.fitur,a.id,b.modul FROM tb_progres_fitur a 
JOIN tb_progres_modul b ON a.id_modul=b.id 
ORDER BY b.nomor,a.fitur ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$i = 0;
$last_modul = '';
while ($d = mysqli_fetch_assoc($q)) {
  if ($last_modul != $d['modul']) $i++;
  $sep = $last_modul != $d['modul'] ? '====================' : '';
  $last_modul = $d['modul'];
  $opt .= "<option value='$d[id]'>[$i] $d[modul] - $d[fitur] $sep</option>";
}
$select_fitur = "<select name=id_fitur class='form-control form-control-sm mb2'>$opt</select>";



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
$tr = '';
for ($i = $durasi_hari; $i > 0; $i--) {
  if ($get_tanggal and $kemarin != $get_tanggal) {
    $kemarin = date('Y-m-d', strtotime("-1 day", strtotime($kemarin)));
    continue;
  }
  $s = "SELECT a.*,
  b.arti as arti_status,
  c.fitur,
  d.modul,
  (SELECT nama FROM tb_user WHERE id=a.request_by) requester,
  (SELECT nama FROM tb_user WHERE id=a.assign_by) assigner,
  1

  FROM tb_progres_task a 
  JOIN tb_progres_status b ON a.status=b.status 
  JOIN tb_progres_fitur c ON a.id_fitur=c.id 
  JOIN tb_progres_modul d ON c.id_modul=d.id 
  WHERE a.date_created >= '$kemarin' 
  AND a.date_created <= '$kemarin 23:59:59' 
  AND $sql_status
  ORDER BY a.date_created
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $jumlah_task = mysqli_num_rows($q);
  if ($i == $durasi_hari || $jumlah_task) {
    $no++;

    # ============================================================
    # FORM ADD TASK PADA ROW PALING ATAS
    # ============================================================
    $form_add_task = '';
    if ($i == $durasi_hari) {
      if ($as_dev || $role == 'admin') {
        # ============================================================
        # SELECT USERS FOR ADD TASK
        # ============================================================
        $opt = '<option value=0>--assign by no one--</option>';
        $s2 = "SELECT id,username FROM tb_user ORDER BY username";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        while ($d2 = mysqli_fetch_assoc($q2)) {
          $selected = '';
          if ($as_dev && $d2['id'] == $id_user) {
            $selected = 'selected';
            $d2['username'] = '--by myself--';
          }
          $opt .= "<option value=$d2[id] $selected>$d2[username]</option>";
        }
        $select_assigned_by = "<select name=assign_by class='form-control mb2'>$opt</select>";

        # ============================================================
        # SELECT STATUS FOR ADD TASK
        # ============================================================
        $opt = '';
        $s2 = "SELECT status,arti FROM tb_progres_status";
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        while ($d2 = mysqli_fetch_assoc($q2)) {
          $opt .= "<option value=$d2[status] >Status $d2[status] : $d2[arti]</option>";
        }
        $select_status = "<select name=status class='form-control mb2'>$opt</select>";


        $form_add_task = "
          <div class=mb2><span class=btn_aksi id=form_add_task__toggle>$img_add Add Task</span></div>
          <form method=post class='hideit wadah gradasi-kuning' id=form_add_task>
            $select_fitur
            <div class=row>
              <div class=col-lg-6>
                <input class='form-control mb2' required minlength=3 name=task value='$post_task' placeholder='Task...'>
              </div>
              <div class=col-lg-6>
                $select_assigned_by
              </div>
              <div class=col-lg-6>
                $select_status
              </div>
              <div class=col-lg-6>
                <input type=date class='form-control mb2' required name=date_created value='$post_today'>
              </div>
            </div>
            <textarea class='form-control mb2' required minlength=10 name=keterangan placeholder='Keterangan...'>$post_keterangan</textarea>
            <button class='btn btn-primary btn-sm' name=btn_add_task>Add Task</button>
          </form>
        ";
      } else {
        $form_add_task = div_alert('info', "Hanya developer atau admin yang berhak Add Task");
      }
    }


    # ============================================================
    # ALL ROWS
    # ============================================================
    $tb_tasks = div_alert('danger', 'Hari ini belum ada task.');


    if ($jumlah_task) {
      $tasks = '';
      $j = 0;
      while ($d = mysqli_fetch_assoc($q)) {
        $j++;
        $id_task = $d['id'];
        $status = $d['status'];
        $is_mine = $d['assign_by'] == $id_user ? 1 : 0;

        # ============================================================
        # FORM DELETE TASK
        # ============================================================
        $form_hapus_task = $status >= 3 ? "<span style='display:inline-block; margin: 0 5px' onclick='alert(`Tidak dapat menghapus atau Dropping Task yang sudah selesai.`)'>$img_delete_disabled</span>" : "<form method=post class='inline m0 p0'><button onclick='return confirm(`Delete Task ini?`)' class='btn-transparan' name=btn_delete_task value=$id_task>$img_delete</button></form>";

        if ($d['assign_by']) {
          if ($is_mine) {
            # ============================================================
            # DROP TASK
            # ============================================================
            $form_drop_task = $status >= 3 ? '' :  "<form method=post class='inline m0 p0'><button onclick='return confirm(`Batalkan Task ini?`)' class='btn-transparan' name=btn_drop_task value=$id_task>$img_drop</button></form>";
            $assign_by_show =   "(me) $form_drop_task";
          } else {
            $assign_by_show = $d['assigner'];
          }


          # ============================================================
          # ASSIGN_BY 
          # ============================================================
        } else {
          # ============================================================
          # FORM TAKE TASK
          # ============================================================
          $form_take_task = "<form method=post class='inline m0 p0'><button onclick='return confirm(`Ambil Task ini?`)' class='btn btn-sm btn-warning' name=btn_take_task value=$id_task>$img_take Take</button></form>";
          $assign_by_show = $form_take_task;
        }

        # ============================================================
        # STATUS TASK
        # ============================================================
        $status_task_show = "<span style='color: $color_status[$status]'>$d[arti_status]</span>";
        if ($is_mine) {
          # ============================================================
          # FORM SET STATUS TASK
          # ============================================================
          $icon = $icon_status[$d['status']];
          $status_task_show .= "
            <span class='btn_aksi pointer' id=keterangan_task_$id_task" . "__toggle>$icon</span> 
            <div class='hideit f10 mt2' id=keterangan_task_$id_task>
              <form method=post class='mt2 f10'>
                <div class=mb1>Set status:</div>
                <button class='btn btn-danger btn_sm w-100 mb1' name=btn_set_status_task value=0__$id_task>[0] $arti_status[0]</button>
                <button class='btn btn-warning btn_sm w-100 mb1' name=btn_set_status_task value=1__$id_task>[1] $arti_status[1]</button>
                <button class='btn btn-warning btn_sm w-100 mb1' name=btn_set_status_task value=2__$id_task>[2] $arti_status[2]</button>
                <button class='btn btn-info btn_sm w-100 mb1' name=btn_set_status_task value=3__$id_task>[3] $arti_status[3]</button>
                <button class='btn btn-success btn_sm w-100 mb1' name=btn_set_status_task value=4__$id_task>[4] $arti_status[4]</button>
                <button class='btn btn-success btn_sm w-100 mb1' name=btn_set_status_task value=5__$id_task>[5] $arti_status[5]</button>
              </form>
            </div>            
          ";
        }

        $color_class = $status < 3 ? 'darkred' : '';
        $color_class = $status > 3 ? 'green' : $color_class;

        $tasks .= "
          <tr>
            <td>$j</td>
            <td>
              $form_hapus_task <span class='$color_class'>$d[task]</span>
              <div class='f10  abu' style='margin-left:35px'>$d[modul] > $d[fitur]</div>
            </td>
            <td>
              $assign_by_show
            </td>
            <td >
              $status_task_show
            </td>
          </tr>
        ";
      }

      $tb_tasks = "
        <table class='table td_trans th_toska table-hover'>
          <thead class='f12'>
            <th width=50px>No</th>
            <th>Tasks</th>
            <th width=20%>Assign by</th>
            <th width=20%>Status</th>
          </thead>
          $tasks
        </table>
        <hr>
      ";
    }

    $kemarin_show = hari_tanggal($kemarin, 0, 0, 0);
    $hari = $nama_hari[date('w', strtotime($kemarin))];

    $tr .= "
      <tr>
        <td>$no</td>
        <td>$hari<br><a href='?progres$get_params&tanggal=$kemarin'>$kemarin_show</td>
        <td>
          $tb_tasks
          $form_add_task
        </td>
      </tr>
    ";
  }

  $kemarin = date('Y-m-d', strtotime("-1 day", strtotime($kemarin)));
}

echo "
  <div class='gradasi-hijau pl1 pr1 br5'>
    <table class='table td_trans table-striped'>$tr</table>
  </div>
";
