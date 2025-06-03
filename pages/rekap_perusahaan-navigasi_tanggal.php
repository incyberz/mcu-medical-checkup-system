<?php
if (isset($_POST['btn_filter_tanggal'])) {
  $arr = $_POST['tanggal_periksa'] ?? [];
  $tanggals = '';
  foreach ($arr as $key => $value) {
    $koma = $tanggals ? ',' : '';
    $tanggals .= "$koma$value";
  }
  if ($tanggals) jsurl("?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=$mode&tanggal_periksa=$tanggals&mode_kesimpulan=$_POST[mode_kesimpulan]");
}


# ============================================================
# PENENTUAN CARA BAYAR PERUSAHAAN : CORMAN | BY-CORP
# ============================================================
$arr_tanggal_periksa_db = [];
if ($perusahaan['cara_bayar'] == 'ci' || $perusahaan['cara_bayar'] == 'bi') { // Cor-Idv
  $tb_c = "tb_harga_perusahaan c ON b.id_harga_perusahaan=c.id";
} else {
  $tb_c = "tb_order c ON b.order_no=c.order_no";
}

$s = "SELECT date(a.awal_periksa) tanggal_periksa 
FROM tb_hasil_pemeriksaan a 
JOIN tb_pasien b ON a.id_pasien=b.id 
JOIN $tb_c 
WHERE c.id_perusahaan = $id_perusahaan 
ORDER BY tanggal_periksa 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

while ($d = mysqli_fetch_assoc($q)) {
  $tanggal_periksa = $d['tanggal_periksa'];
  if (!in_array($tanggal_periksa, $arr_tanggal_periksa_db)) array_push($arr_tanggal_periksa_db, $tanggal_periksa);
}

$arr_tanggal_periksa = explode(',', $get_tanggal_periksa);
$nav_tanggal = '';
if (count($arr_tanggal_periksa_db) > 1) {
  foreach ($arr_tanggal_periksa_db as  $tanggal_periksa) {
    $primary = $tanggal_periksa == $get_tanggal_periksa ? 'primary' : 'secondary';
    $TanggalPeriksa = hari_tanggal($tanggal_periksa, 0, 0, 0);
    $secondary = in_array($tanggal_periksa, $arr_tanggal_periksa) ? 'primary' : 'secondary';
    $checked = in_array($tanggal_periksa, $arr_tanggal_periksa) ? 'checked' : '';
    $nav_tanggal .= "
      <div class='hideit'>
        <a class='btn btn-$primary btn-sm' href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=$mode&tanggal_periksa=$tanggal_periksa'>
          $tanggal_periksa
        </a>
      </div>
      <div class='btn btn-outline-$secondary f14'>
        <label>
          <input type=checkbox name=tanggal_periksa[] value='$tanggal_periksa' $checked> $TanggalPeriksa
        </label>
      </div>
    ";
  }

  $selected = [];
  $selected[0] = '';
  $selected[-1] = '';
  $selected[1] = '';
  $selected[intval($get_mode_kesimpulan)] = 'selected';

  $nav_tanggal .= "
    <div class=''>
      <select class='form-control form-control-sm border-success' name=mode_kesimpulan>
        <option value='0' $selected[0]>All Data</option>
        <option value='-1' $selected[-1]>Belum Disimpulkan</option>
        <option value='1' $selected[1]>Sudah Disimpulkan</option>
      </select>
    </div>
    <div>
      <button class='btn btn-success btn-sm' name=btn_filter_tanggal>Filter</button>
    </div>
  ";

  echo  "
    <form method=post class='mb4 tengah'>
      <div class=mb2>Tanggal Pemeriksaan:</div>
      <div class='flex flex-center gap-2'>
        $nav_tanggal
      </div>
    </form>
  ";
  if (!$get_tanggal_periksa) {
    echo div_alert('info', 'Silahkan pilih salah satu tanggal.');
    exit;
  }
}
