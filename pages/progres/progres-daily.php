<?php
$img_take = img_icon('take');
$img_drop = img_icon('drop');










# ============================================================
# ZZZ 
# ============================================================












# ============================================================
# SELECT MODUL
# ============================================================
// $opt = '';
// $s = "SELECT * FROM tb_progres_modul ORDER BY nomor,date_created ";
// $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
// while ($d = mysqli_fetch_assoc($q)) {
//   $opt .= "<option value='$d[id]'>$d[modul]</option>";
// }
// $select_modul = "<select name=id_modul class='form-control form-control-sm mb2'>$opt</select>";

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
  $s = "SELECT a.*,
  b.keterangan as nama_status,
  (SELECT nama FROM tb_user WHERE id=a.request_by) requester,
  (SELECT nama FROM tb_user WHERE id=a.assign_by) assigner,
1
  FROM tb_progres_task a 
  JOIN tb_progres_status b ON a.status=b.status 
  WHERE a.last_update >= '$kemarin' 
  AND a.last_update <= '$kemarin 23:59:59' 
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
      $form_add_task = $role != 'admin' ? '' :  "
        <span class=btn_aksi id=form_add_task__toggle>$img_add Task</span>
        <form method=post class='hideita mt1 wadah gradasi-kuning' id=form_add_task>
          $select_fitur
          <input class='form-control mb2' required minlength=3 name=task value='$post_task' placeholder='Task...'>
          <textarea class='form-control mb2' required minlength=10 name=keterangan placeholder='Keterangan...'>$post_keterangan</textarea>
          <button class='btn btn-primary btn-sm' name=btn_add_task>Add Task</button>
        </form>
      ";
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

        # ============================================================
        # FORM DELETE TASK
        # ============================================================
        $form_hapus_task = "<form method=post class='inline m0 p0'><button onclick='return confirm(`Delete Task ini?`)' class='btn-transparan' name=btn_delete_task value=$id_task>$img_delete</button></form>";

        if ($d['assign_by']) {
          # ============================================================
          # DROP TASK
          # ============================================================
          $form_drop_task = "<form method=post class='inline m0 p0'><button onclick='return confirm(`Batalkan Task ini?`)' class='btn-transparan' name=btn_drop_task value=$id_task>$img_drop</button></form>";

          # ============================================================
          # ASSIGN_BY 
          # ============================================================
          $assign_by_show =  $d['assigner'];
          if ($d['assign_by'] == $id_user) { // mine task
            $assign_by_show = "(me) $form_drop_task";
          }
        } else {
          # ============================================================
          # FORM TAKE TASK
          # ============================================================
          $form_take_task = "<form method=post class='inline m0 p0'><button onclick='return confirm(`Ambil Task ini?`)' class='btn btn-sm btn-warning' name=btn_take_task value=$id_task>$img_take Take</button></form>";
          $assign_by_show = $form_take_task;
        }

        $tasks .= "
          <tr>
            <td>$j</td>
            <td>
              $form_hapus_task $d[task]
              <div class='f12 abu'></div>
            </td>
            <td>
              $assign_by_show
            </td>
            <td >
              $d[nama_status]
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

    $tr .= "
      <tr>
        <td>$no</td>
        <td>$kemarin</td>
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
