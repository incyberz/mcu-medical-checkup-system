<?php
set_h2('Manage Cara Bayar');
only(['admin', 'marketing']);

# ============================================================
# INCLUDES
# ============================================================


# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_delete_perusahaan'])) {
  $s = "DELETE FROM tb_perusahaan WHERE id=$_POST[btn_delete_perusahaan]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "delete perusahaan sukses.");
  jsurl('', 1000);
}














# ============================================================
# MAIN SELECT CARA BAYAR
# ============================================================
$s = "SELECT  a.* FROM tb_cara_bayar a";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  $key_hidden = [];
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $td = '';
    foreach ($d as $key => $value) {
      if (in_array($key, $key_hidden)) continue;
      $kolom = '';

      if ($key == 'cara_bayar') {

        $form_delete_cb = "<span class='on-dev'>$img_delete</span>";

        $kolom = 'Kode Cara Bayar';
        $value = "$form_delete_cb $value";
      } elseif ($key == 'status') {
        $kolom = 'Status Aktif';
      }

      $td .= "<td>$value</td>";
      if ($i == 1) {
        $kolom = $kolom ? $kolom : key2kolom($key);
        $th .= "<th>$kolom</th>";
      }
    }
    $tr .= "
      <tr>
        $td
      </tr>
    ";
  }
}

$tb = $tr ? "
  <div class='gradasi-hijau' style='height:60vh; position:relative; overflow-y:scroll'>
    <table class='table table-striped td_trans th_toska table-bordered'>
      <thead style='position:sticky;top:0'>$th</thead>
      $tr
      <tr>
        <td colspan=100%>
          <span id=form_add_cb__toggle class='btn_aksi f14 m3'>$img_add Add Cara Bayar</span>
          <form class='hideit wadah gradasi-kuning mt4 hijau' id=form_add_cb>
            Fitur ini masih dalam tahap pengembangan. terimakasih.
          </form>
        </td>
      </tr>
    </table>
  </div>
" : div_alert('danger', "Data perusahaan tidak ditemukan.");
echo "$tb";
