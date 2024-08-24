<?php
$aksi = $_GET['aksi'] ?? 'add';
$Save = $aksi == 'add' ? 'Save' : 'Update';
$id_perusahaan = '';
if ($aksi != 'add') {
  $id_perusahaan = $_GET['id_perusahaan'] ?? die(erid('id_perusahaan'));
}
set_h2(ucwords($aksi) . ' Perusahaan', "<a href='?manage_perusahaan'>$img_prev</a>");
only(['admin', 'marketing']);

# ============================================================
# INCLUDES
# ============================================================


# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_save_perusahaan'])) {
  $id = $_POST['btn_save_perusahaan'];
  unset($_POST['btn_save_perusahaan']);
  $isis = '';
  $koloms = '';
  $pairs = '';
  foreach ($_POST as $k => $v) {
    $koma = $koloms ? ',' : '';
    $koloms .= "$koma$k";
    $isis .= "$koma'$v'";
    $pairs .= "$koma$k='$v'";
  }

  if ($id) {
    $s = "UPDATE tb_perusahaan SET $pairs WHERE id=$id";
  } else {
    $s = "INSERT INTO tb_perusahaan ($koloms) VALUES ($isis)";
  }
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $Add = $id ? 'Update' : 'Add';
  echo div_alert('success', "$Add perusahaan sukses.");
  jsurl('', 1000);
}
if (isset($_POST['btn_delete_perusahaan'])) {
  $s = "DELETE FROM tb_perusahaan WHERE id=$_POST[btn_delete_perusahaan]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "delete perusahaan sukses.");
  jsurl('', 1000);
}














# ============================================================
# MAIN SELECT PERUSAHAAN
# ============================================================
if ($id_perusahaan) {
  $s = "SELECT * FROM tb_perusahaan WHERE id='$id_perusahaan'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $perusahaan = mysqli_fetch_assoc($q);
  } elseif (!mysqli_num_rows($q) and $id_perusahaan) {
    die(div_alert('danger', "Data perusahaan tidak ditemukan"));
  }
} else {
  $perusahaan = [];
}

$s = "SELECT * FROM tb_perusahaan LIMIT 1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    foreach ($d as $key => $value) {
      $value = $perusahaan[$key] ?? null;
      if (
        $key == 'id'
        || $key == 'date_created'
        || $key == 'image'
        || $key == 'jumlah_peserta'
        || $key == 'cara_bayar'
        || $key == 'nomor'
        || $key == 'whatsapp'
        || $key == 'jabatan_kontak'
        || $key == 'gender_kontak'
      ) continue;

      $kolom = key2kolom($key);

      if (strlen($value) > 50) {
        $input_value = "<textarea required class='form-control' name=$key >$value</textarea>";
      } else {
        $input_value = "<input required class='form-control' name=$key value='$value' />";
      }

      if ($key == 'nama_kontak') {
        if ($aksi == 'add') continue;
        $kolom = 'Data Kontak';
        if (!$value) {
          $value = $null;
        } else {
          $gender = $d['gender_kontak'] ? ' (' . strtoupper($d['gender_kontak']) . ')' : '';
          $value = "Whatsapp: $d[whatsapp], $value$gender, $d[jabatan_kontak]";
        }
        $input_value = "
          $value | <a target=_blank href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=kirim_link&tanggal_periksa=&edit_kontak=1'>Ubah Kontak</a>
        ";
      }


      $tr .= "
        <tr>
          <td>$kolom</td>
          <td>
            $input_value
          </td>
        </tr>
      ";
    } // end foreach $d
  } // end while

  # ============================================================
  # MODE BAYAR
  # ============================================================
  $tr_mode_bayar = '';
  if ($aksi == 'add') {
    $tr_mode_bayar = "
          <tr>
            <td>Cara Bayar</td>
            <td>
              <div>
                <label>
                  <input required type=radio name=cara_bayar value='bc'> By Corporate (ditanggung perusahaan)
                </label>
              </div>
              <div>
                <label>
                  <input required type=radio name=cara_bayar value='ci'> Corporate Individu (bayar perorangan)
                </label>
              </div>
            </td>
          </tr>
        
        ";
  }
  $tr .= $tr_mode_bayar;
}

$tb = $tr ? "
  <form method=post>
    <table class='table gradasi-hijau td_trans table-striped'>
      $tr
      <tr>
        <td>&nbsp;</td>
        <td>
          <button class='btn btn-primary' name=btn_save_perusahaan value=$id_perusahaan>$Save</button> 
        </td>
      </tr>
    </table>
  </form>
" : div_alert('danger', "Data perusahaan tidak ditemukan.");
echo "$tb";
