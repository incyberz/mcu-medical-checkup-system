<?php
set_h2('Manage Perusahaan', "<a href='?add_perusahaan'>$img_add Add Perusahaan</a>");
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
# MAIN SELECT PERUSAHAAN
# ============================================================
$s = "SELECT 
a.id,
a.nama,
(
  SELECT p.harga FROM tb_harga_perusahaan p 
  WHERE p.id_perusahaan=a.id) harga_perusahaan, 
(
  SELECT count(1) FROM tb_order p 
  JOIN tb_pasien q ON p.order_no=q.order_no 
  WHERE p.id_perusahaan=a.id) count_pasien_free, 
(
  SELECT count(1) FROM tb_harga_perusahaan p 
  JOIN tb_pasien q ON q.id_harga_perusahaan=p.id 
  WHERE p.id_perusahaan=a.id) count_pasien_berbayar  
FROM tb_perusahaan a ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  $key_hidden = ['id', 'date_created', 'nomor', 'telepon', 'alamat', 'image', 'jumlah_peserta', 'whatsapp', 'nama_kontak', 'jabatan_kontak', 'gender_kontak', 'cara_bayar'];
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $id_perusahaan = $d['id'];


    $count_pasien =  $d['count_pasien_free'] + $d['count_pasien_berbayar'];
    $td = '';
    foreach ($d as $key => $value) {
      if (in_array($key, $key_hidden)) continue;
      $kolom = '';

      if ($key == 'nama') {
        $kolom = 'Nama Perusahaan';
        $icon_delete = $count_pasien ? "<i onclick='alert(`hapus dahulu sub-trx agar perusahaan ini dapat dihapus.`)'>$img_delete_disabled</i>" : "
          <form method=post style='display:inline' class='m0 p0'>
            <button class='transparan' name=btn_delete_perusahaan value=$id_perusahaan onclick='return confirm(`Hapus perusahaan ini?`)'>$img_delete</button>
          </form>
        ";
        $value = "
          $icon_delete 
          <a href='?add_perusahaan&aksi=edit&id_perusahaan=$id_perusahaan' onclick='return confirm(`Edit perusahaan ini?`)'>$img_edit</a> 
          $i. $value
        ";
      } elseif ($key == 'harga_perusahaan') {
        $value = !$value ? '<i class="f12 abu consolas">free</i>' : '<a href=?paket_harga_perusahaan>Rp ' . number_format($value) . '</a>';
      } elseif ($key == 'count_pasien_free' || $key == 'count_pasien_berbayar') {
        $value = !$value ? $value : "<a target=_blank onclick='return confirm(`Buka rekap perusahaan?`)' href='?rekap_perusahaan&id_perusahaan=$d[id]&mode=detail'>$value</a>";
      }
      if (substr($key, 0, 6) == 'count_') {
        $value = $value ? $value : '<i class="abu">-</i>';
      }
      $value = $count_pasien ? $value : "<span class='miring abu'>$value</span>";
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
    </table>
  </div>
" : div_alert('danger', "Data perusahaan tidak ditemukan.");
echo "$tb";
