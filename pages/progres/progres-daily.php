<?php
# ============================================================
# SELECT H1
# ============================================================
$opt = '';
$s = "SELECT * FROM tb_progres_h1 ORDER BY nomor,date_created ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d = mysqli_fetch_assoc($q)) {
  $opt .= "<option value='$d[id]'>$d[h1]</option>";
}
$select_h1 = "<select name=id_fitur class='form-control form-control-sm'>$opt</select>";


# ============================================================
# MAIN SELECT DAILY
# ============================================================
$s = "SELECT 
a.*,
b.arti as arti_status, 
date(a.last_update) as tanggal_update 
FROM tb_progres_sub a 
JOIN tb_progres_status b ON a.status=b.status 
WHERE $sql_status
ORDER BY a.last_update DESC";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_row = mysqli_num_rows($q);
$tr_daily = '';
$i = 0;
$j = 0;
$k = 0;
$last_tanggal_update = '';
$sub_tr = '';
$rows = [];
while ($d = mysqli_fetch_assoc($q)) {
  if (isset($rows[$d['tanggal_update']])) {
    array_push($rows[$d['tanggal_update']], $d);
  } else {
    $rows[$d['tanggal_update']][0] = $d;
  }
}

if (!key_exists($today, $rows)) $rows[$today] = []; // add today if not exists

krsort($rows);

$tr_daily = '';
$i = 0;
$dis = [];
foreach ($rows as $k => $v) {
  $i++;
  $hari = hari_tanggal($k, 1, 1, 0);

  $j = 0;
  $sub_tr = '';
  foreach ($v as $k2 => $v2) {
    $j++;
    $id_subfitur = $v2['id'];
    $eta = eta2($v2['last_update']);
    $href_icon = !$v2['href'] ? '<span onclick="alert(`Tidak ada link akses untuk job ini.`)">' . $img_gray . '</span>' : "<a target=_blank href='$v2[href]'>$img_next</a>";

    $icon = $img_arti[$v2['status']] ?? $img_gray;
    $form_delete_subfitur = $role != 'admin' ? '' :  "
      <form method=post class='mt1' target=_blank>
        <button onclick='return confirm(`Delete subfitur ini?`)' class='btn btn-danger btn-sm' name=btn_delete_subfitur value=$id_subfitur >Delete</button>
      </form>
    ";

    for ($k = 0; $k <= 5; $k++) $dis[$k] = '';
    $dis[$v2['status']] = 'disabled';

    $sub_tr .= "
      <tr>
        <td width=50px>$j</td>
        <td>
          $v2[nama]
          <div class='f12 abu mt1'>$v2[keterangan]</div>
          $form_delete_subfitur
        </td>
        <td width=30%>
          <span class='btn_aksi pointer' id=keterangan_subfitur_$id_subfitur" . "__toggle>$icon</span> 
          $href_icon
          <div class='f10 abu mt1'>$v2[arti_status]</div>
          <div class='f10 abu mt1'>$eta</div>

          <div class='hideit f10 mt2' id=keterangan_subfitur_$id_subfitur>
            <form method=post class='mt2 f10'>
              <div class=mb1>Set status:</div>
              <button class='btn btn-danger btn_sm' name=btn_set_status value=0__$id_subfitur $dis[0]>0</button>
              <button class='btn btn-warning btn_sm' name=btn_set_status value=1__$id_subfitur $dis[1]>1</button>
              <button class='btn btn-warning btn_sm' name=btn_set_status value=2__$id_subfitur $dis[2]>2</button>
              <button class='btn btn-info btn_sm' name=btn_set_status value=3__$id_subfitur $dis[3]>3</button>
              <button class='btn btn-success btn_sm' name=btn_set_status value=4__$id_subfitur $dis[4]>4</button>
              <button class='btn btn-success btn_sm' name=btn_set_status value=5__$id_subfitur $dis[5]>5</button>
            </form>
            <form method=post class='mt2 f10 ' target=_blank>
              <div class=mb1>Set:</div>
              <button class='btn btn-success btn_sm btn_sedang_dikerjakan' name=btn_sedang_dikerjakan value=$id_subfitur>Sedang dikerjakan</button>
            </form>
          </div>

        </td>
      </tr>
    ";
  }

  # ======================================================
  # TR ADD SUB FITUR AT TOP ROWS ONLY
  # ======================================================
  $tr_add_sub = '';
  $tr_active = '';
  if ($i == 1) {
    $j++;
    $tr_add_sub = "
      <tr>
        <td class='abu miring consolas f12 sub_number'>*$j</td>
        <td colspan=100%>
          <span class='consolas green f12 bold btn_aksi pointer' id=form_subfitur$id_fitur" . "__toggle>+ Add Subfitur Today</span>
          <form method=post id=form_subfitur$id_fitur class='hideit mt1'>
            <table width=100%>
              <tr>
                <td>
                  $select_h1
                </td>
                <td>
                  <button class='btn btn-success btn-sm ml1' name=btn_add_subfitur_daily value=$id_fitur>Add</button>
                </td>
              </tr>
              <tr>
                <td>
                  <input class='form-control form-control-sm' name=new_subfitur required minlength=5 maxlength=30 placeholder='Subfitur Baru...'/>
                </td>
              </tr>
              <tr>
                <td>
                  <textarea class='form-control form-control-sm' name=keterangan required minlength=20 maxlength=1000 placeholder='Keterangan...'></textarea>
                </td>
              </tr>
              <tr>
                <td>
                  <input class='form-control form-control-sm' name=href minlength=2 maxlength=100 placeholder='Link akses...'/>
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    ";
    $tr_active = 'tr_active';
  }

  $tr_daily .= "
    <tr class='$tr_active'>
      <td>$hari</td>
      <td>
        <table class='table table-hover td_trans'>
          $sub_tr
          $tr_add_sub
        </table>
      </td>
    </tr>
  ";
}

echo "
  <div style='max-height:75vh; overflow-y:scroll; position:relative'>
    <table class='table table-striped table-bordered'>
      $tr_daily
    </table>
  </div>
";
