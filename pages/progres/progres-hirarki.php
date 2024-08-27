<?php
$get_id_fitur = $_GET['id_modul'] ?? '';
$get_no = $_GET['no'] ?? '';
$sql_id_fitur = $get_id_fitur ? "a.id=$get_id_fitur" : '1';

# ======================================================
# NAV BY SUB PROGRES
# ======================================================
$s = "SELECT a.id, a.modul
FROM tb_progres_modul a ORDER BY a.nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$nav = "<a class='btn btn-sm btn-info f12' href='?progres&mode=$mode' >All Modul</a> ";
$i = 0;
$no_next_fitur = mysqli_num_rows($q) + 1;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $btn = $d['id'] == $get_id_fitur ? "<span class='btn btn-sm btn-primary' style='display:inline-block;margin:0 10px 0 5px'>$i</span>" : "<a class='btn btn-sm btn-info f10 miring' href='?progres&id_modul=$d[id]&no=$i&mode=$mode' >$i</a> ";
  $nav .= $btn;
}
$total_task_show = "";



echo "<div class='mb2 tengah'>$nav</div>";

# ======================================================
# MAIN SELECT FITUR
# ======================================================
$s = "SELECT a.id as id_modul, a.* 
FROM tb_progres_modul a 
WHERE $sql_id_fitur 
ORDER BY nomor";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
$i = $get_no ? ($get_no - 1) : 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $id_modul = $d['id_modul'];
  $id_toggle = "fitur$id_modul" . '__toggle';

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
  # SELECT FITUR
  # ======================================================
  $s2 = "SELECT a.*,
  a.id as id_fitur 
  FROM tb_progres_fitur a 
  WHERE a.id_modul=$id_modul 
  ";

  $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
  $tr_sub = '<tr><td colspan=100% class="consolas f12 miring red">Belum ada fitur</td></tr>';
  $j = 0;


  if (mysqli_num_rows($q2)) {
    $tr_sub = '';
    while ($d2 = mysqli_fetch_assoc($q2)) {
      $j++;
      $id_fitur = $d2['id_fitur'];
      $last_update = $d2['last_update'];

      $form_delete_fitur = $role != 'admin' ? '' :  "
        <form method=post class='mt1' target=_blank>
          <button onclick='return confirm(`Delete fitur ini?`)' class='btn btn-danger btn-sm' name=btn_delete_fitur value=$id_fitur >Delete</button>
        </form>
      ";

      $tr_sub .= "
        <tr>
          <td class='sub_number'>$i.$j</td>
          <td>
            $d2[nama]
            <div class='abu f12 mt1'>$d2[keterangan]</div>
            $form_delete_fitur

          </td>
          <td class='td_status_fitur'>
            EMPTY TD
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
        <span class='consolas green f12 bold btn_aksi pointer' id=form_fitur$id_modul" . "__toggle>+ Add Subfitur</span>
        <form method=post id=form_fitur$id_modul class='hideit mt1'>
          <table width=100%>
            <tr>
              <td>
                <input class='form-control form-control-sm' name=new_fitur required minlength=5 maxlength=30 placeholder='Subfitur Baru...'/>
              </td>
              <td>
                <button class='btn btn-success btn-sm ml1' name=btn_add_fitur value=$id_modul>Add</button>
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
      <button onclick='return confirm(`Delete fitur ini?`)' class='btn btn-danger btn-sm' name=btn_delete_fitur value=$id_modul >Delete</button>
    </form>
  ";



  # ======================================================
  # FINAL LOOP OUTPUT
  # ======================================================
  $tr .= "
    <tr>
      <td>$i</td>
      <td>
        $d[modul] <span class=btn_aksi id=$id_toggle>$img_detail</span>
        <form method=post class='hideit wadah gradasi-kuning mt2' id=fitur$id_modul>
          <div class='f10 abu consolas mb2'>FORM EDIT FITUR</div>
          <table>
            $tr_form
            <tr><td>&nbsp;</td><td colspan=100%><button class='btn btn-info btn-sm' name=btn_update_fitur value=$id_modul>Update</button></td></tr>
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
<div style='position:relative;max-height:65vh;overflow-y:scroll' class='gradasi-hijau br10 pl1 pr1'>
  <table class='table th_toska td_trans'>
    <thead style='position:sticky;top:0'>
      <th>No</th>
      <th>Modul System</th>
      <th>Fitur dan Task</th>
    </thead>
    $tr
    $tr_add
  </table>  
</div>
";
