<?php
if (isset($_POST['btn_save_order'])) {
  foreach ($_POST['nomor'] as $k => $v) {
    if ($v !== '') {
      $modul = $_POST['modul'][$k];
      $request_by = $_POST['request_by'][$k];
      $s = "UPDATE tb_progres_modul SET 
      nomor = $v,
      modul = '$modul',
      request_by = $request_by 
      WHERE id=$k
      ";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    }
  }
  jsurl();
}


include 'include/arr_user.php';

$s = "SELECT 
a.id, 
a.nomor, 
a.modul, 
a.request_by 
FROM tb_progres_modul a 
ORDER BY a.nomor,a.modul";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id = $d['id'];
    $td = '';
    foreach ($d as $key => $value) {
      if (
        $key == 'id'
        || $key == 'date_created'
      ) continue;
      if ($i == 1) {
        $kolom = key2kolom($key);
        $th .= "<th>$kolom</th>";
      }


      // convert request by to select
      $name_arr = $key . '[' . $id . ']';
      if ($key == 'request_by') {
        $opt = '';
        foreach ($arr_user as $k2 => $v2) {
          $selected = $k2 == $value ? 'selected' : '';
          $opt .= "<option $selected value='$k2'>$v2</option>";
        }
        $input_value = "<select class='form-control' name=$name_arr >$opt</select>";
      } else {
        // convert value to input
        if (strlen($value) > 50) {
          $input_value = "<textarea required class='form-control' name=$name_arr >$value</textarea>";
        } else {
          if ($key == 'nomor') {
            $input_value = "<input type=number min=1 class='form-control' name=$name_arr value='$value' />";
          } else {
            $input_value = "<input required class='form-control' name=$name_arr value='$value' />";
          }
        }
      }

      $td .= "<td>$input_value</td>";
    }
    $tr .= "
      <tr>
        $td
      </tr>
    ";
  }
}

echo $tr ? "
  <form method=post style='height:65vh; position:relative; overflow-y:scroll' class='gradasi-hijau pl2 pr2'>
    <table class='table td_trans th_toska table-striped'>
      <thead style='position:sticky;top:0'>$th</thead>
      $tr
    </table>
    <button class='btn btn-primary w-100' name=btn_save_order>Save Order</button>
  </form>
" : div_alert('danger', "Data progres_modul tidak ditemukan.");
