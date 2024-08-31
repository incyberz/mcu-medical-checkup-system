<?php
$judul = 'Paket Harga Perusahaan';
set_h2($judul, "<a href='?manage_perusahaan'>Manage Perusahaan</a> | <a href='?manage_paket'>Manage Paket</a>");
only(['admin', 'marketing']);

# ============================================================
# INCLUDES
# ============================================================


# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_delete_harga'])) {
  $s = "DELETE FROM tb_harga_perusahaan WHERE id=$_POST[btn_delete_harga]";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  echo div_alert('success', "delete harga perusahaan sukses.");
  jsurl('', 1000);
}














# ============================================================
# MAIN SELECT
# ============================================================
$s = "SELECT  
b.nama as perusahaan,
c.nama as nama_paket,
a.id,
a.harga,
a.date_created as tanggal_buat,
a.date_expired as berlaku_hingga,
(SELECT COUNT(1) FROM tb_pasien WHERE id_harga_perusahaan=a.id) count_pasien

FROM tb_harga_perusahaan a 
JOIN tb_perusahaan b ON a.id_perusahaan=b.id 
JOIN tb_paket c ON a.id_paket=c.id
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  $key_hidden = ['id'];
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $td = '';
    foreach ($d as $key => $value) {
      if (in_array($key, $key_hidden)) continue;
      $kolom = '';

      if ($key == 'harga') {

        $form_delete_cb = $d['count_pasien']
          ? "<span onclick='alert(`Tidak bisa dihapus karena sudah digunakan pada $d[count_pasien] pasien.`)'>$img_delete_disabled</span>"
          : "
            <form method=post style='display:inline;margin:0;'>
              <button onclick='return confirm(`Delete harga ini?`)' class='btn-transparan p0' value=$d[id] name=btn_delete_harga>$img_delete</button>
            </form>
          ";

        $kolom = 'Harga Paket';
        $value = "$form_delete_cb $value";
      } elseif ($key == 'berlaku_hingga') {
        $value = $value ?? '<i class="f12 abu">--not set--</i>';
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
          <a href='?add_harga_paket' class='f14 m3'>$img_add Add $judul</a>
        </td>
      </tr>
    </table>
  </div>
" : div_alert('danger', "Data perusahaan tidak ditemukan.");
echo "$tb";
