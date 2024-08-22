<?php
set_h2('Manage Perusahaan');
only(['admin', 'marketing']);

# ============================================================
# INCLUDES
# ============================================================


# ===========================================================
# PROCESSORS
# ===========================================================
if (isset($_POST['btn_add_pemeriksaan'])) {
  // $jenis = $_POST['jenis'] ?? die('index [jenis] belum terdefinisi.');
  // $nama = $_POST['nama'] ?? die('index [nama] belum terdefinisi.');
  // $singkatan = substr($nama, 0, 10);

  // $s = "INSERT INTO tb_pemeriksaan (
  //   id_klinik,
  //   jenis,
  //   nama,
  //   singkatan
  // ) VALUES (
  //   '$id_klinik',
  //   '$jenis',
  //   '$nama',
  //   '$singkatan'
  // )";
  // // echo $s;
  // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  // echo div_alert('success', "Add Pemeriksaan sukses. Silahkan pilih pemeriksaan tersebut untuk editing selanjutnya.");
  // jsurl('', 3000);
}














# ============================================================
# MAIN SELECT PERUSAHAAN
# ============================================================
$s = "SELECT a.*,
(SELECT 0) count_order, 
(SELECT 0) count_harga_perusahaan, 
(SELECT 0) count_pasien 
FROM tb_perusahaan a ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$tr = '';
if (mysqli_num_rows($q)) {
  $i = 0;
  $th = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
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
      $td .= "<td>$value</td>";
    }
    $tr .= "
      <tr>
        $td
      </tr>
    ";
  }
}

$tb = $tr ? "
  <table class=table>
    <thead>$th</thead>
    $tr
  </table>
" : div_alert('danger', "Data perusahaan tidak ditemukan.");
echo "$tb";
