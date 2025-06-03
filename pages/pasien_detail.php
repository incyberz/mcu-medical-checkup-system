<style>
  table,
  table tr,
  table td {
    background: none !important;
  }
</style>
<?php
set_h2('Pasien Detail');
$id_pasien = $_GET['id_pasien'] ?? die('butuh index id_pasien');


# ============================================================
# PROCESS
# ============================================================
if (isset($_POST['btn_update_data_pasien'])) {
  $id = $_POST['btn_update_data_pasien'];
  unset($_POST['btn_update_data_pasien']);
  $pairs = '';
  foreach ($_POST as $k => $v) {
    # code...
    $koma = $pairs ? ',' : '';
    $pairs .= "$koma$k = '$v'";
  }

  $s = "UPDATE tb_pasien SET $pairs WHERE id=$id";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  jsurl();
}

# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT a.* 
FROM tb_pasien a WHERE id='$id_pasien'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $d = mysqli_fetch_assoc($q);
  foreach ($d as $key => $value) {
    if (
      $key == 'id'
      || $key == 'date_created'
    ) {
      continue;
    } elseif ($key == 'nama' || $key == 'tempat_lahir') {
      $value = "<input required class='form-control' name=$key value='$value' minlength=3 maxlength=30>";
    } elseif ($key == 'gender') {
      $checked_l = $value == 'l' ? 'checked' : '';
      $checked_p = $value == 'p' ? 'checked' : '';
      $value = "
      <div class='d-flex gap-2'>
        <label class=d-block>
          <input type=radio name=$key id=gender_l required value=l $checked_l> Laki-laki
        </label>
        <label class=d-block>
          <input type=radio name=$key id=gender_p required value=p $checked_p> Perempuan
        </label>
      </div>
      ";
    } elseif ($key == 'usia') {
      $value = "<input required type=number name=$key class='form-control' value='$value' min=1 max=100>";
    } elseif ($key == 'tanggal_lahir') {
      $value = "<input required type=date name=$key class='form-control' value='$value' min='1950-01-01' max='2024-01-01'>";
    }

    $kolom = key2kolom($key);
    $tr .= "
      <tr>
        <td>$kolom</td>
        <td>$value</td>
      </tr>
    ";
  }
} else {
  die('Data pasien tidak ditemukan.');
}

echo "
  <form method=post class='card p-3 gradasi-kuning'>
    <table class='table'>
      $tr
    </table>
    <button class='btn btn-primary w-100' name=btn_update_data_pasien value=$d[id]>Update Data Pasien</button>
  </form>
";
