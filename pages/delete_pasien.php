<?php
set_title("Delete Pasien");
only('users');
$id_pasien = $_GET['id_pasien'] ?? die(div_alert('danger', "Page ini membutuhkan index [id_pasien]"));
echo "<style>h3{margin-top:30px}</style>";
$s = "SELECT order_no,jenis FROM tb_pasien WHERE id=$id_pasien";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$order_no = '';
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', 'Data pasien tidak ditemukan'));
} else {
  $d = mysqli_fetch_assoc($q);
  $order_no = $d['order_no'];
  $jenis = $d['jenis'];
  $JENIS = strtoupper($jenis);
}

$konfirmasi_hapus = $_POST['konfirmasi_hapus'] ?? null;
if ($konfirmasi_hapus) {
  echo div_alert('danger', "Perform deleting pasien...");
} else {
  echo div_alert('danger', "
    <b class='red'>Perhatian!</b> 
    Seluruh sub-data yang mengacu pada pasien tersebut juga akan terhapus.<hr>Sub data-data pasien antara lain: 
  ");
}

$pesan = '';
$arr = ['hasil_pemeriksaan', 'jadwal', 'pembayaran', 'pasien'];
foreach ($arr as $tb) {
  $kolom = key2kolom($tb);
  echo "<h3 class='gradasi-merahs border-bottom p2 f18 darkblue br5 miring'>Data $kolom</h3>";
  $kolom = $tb == 'pasien' ? 'id' : 'id_pasien';
  $s = "SELECT * FROM tb_$tb WHERE $kolom=$id_pasien";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q)) {
    $d = mysqli_fetch_assoc($q);
    if ($konfirmasi_hapus) {
      $s2 = "DELETE FROM tb_$tb WHERE $kolom=$id_pasien";
      echolog($s2);
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    } else {
      echo '<pre class="wadah gradasi-kuning">';
      print_r($d);
      echo '</pre>';
    }
  } else {
    echo div_alert('info', "Pasien ini tidak punya data [ $tb ]");
  }
}

# ============================================================
# FORM KONFIRMASI HAPUS
# ============================================================
if ($konfirmasi_hapus) {
  echo div_alert('success mt2', "Pasien ini berhasil dihapus. | <a href='?pendaftaran'>Home Pendaftaran</a>");
} else {
  echo "
    <form method=post class='wadah gradasi-merah'>
      <h3 class='mt1'>Form Konfirmasi Hapus Pasien</h3>
      <div class='mb2 mt2'>
        <label>
          <input required type=checkbox > Saya yakin untuk menghapus data pasien berikut sub-datanya.
        </label>
      </div>
      <button class='btn btn-danger' name=konfirmasi_hapus value=1>Konfirmasi Hapus</button>
    </form>
  ";
}
