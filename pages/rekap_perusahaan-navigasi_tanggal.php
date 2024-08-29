<?php
if (isset($_POST['btn_filter_tanggal'])) {
  $arr = $_POST['tanggal_periksa'] ?? [];
  $tanggals = '';
  foreach ($arr as $key => $value) {
    $koma = $tanggals ? ',' : '';
    $tanggals .= "$koma$value";
  }
  if ($tanggals) jsurl("?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=$mode&tanggal_periksa=$tanggals");
}


# ============================================================
# PENENTUAN CARA BAYAR PERUSAHAAN : CORMAN | BY-CORP
# ============================================================
$arr_tanggal_periksa = [];
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
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

while ($d = mysqli_fetch_assoc($q)) {
  $tanggal_periksa = $d['tanggal_periksa'];
  if (!in_array($tanggal_periksa, $arr_tanggal_periksa)) array_push($arr_tanggal_periksa, $tanggal_periksa);
}

$nav_tanggal = '';
if (count($arr_tanggal_periksa) > 1) {
  foreach ($arr_tanggal_periksa as  $tanggal_periksa) {
    $primary = $tanggal_periksa == $get_tanggal_periksa ? 'primary' : 'secondary';
    $TanggalPeriksa = hari_tanggal($tanggal_periksa, 0, 0, 0);
    $nav_tanggal .= "
      <div class='mr2 hideit'>
        <a class='btn btn-$primary btn-sm' href='?rekap_perusahaan&id_perusahaan=$id_perusahaan&mode=$mode&tanggal_periksa=$tanggal_periksa'>
          $tanggal_periksa
        </a>
      </div>
      <div class='mr2 btn btn-secondary f14'>
        <label>
          <input type=checkbox name=tanggal_periksa[] value='$tanggal_periksa'> $TanggalPeriksa
        </label>
      </div>
    ";
  }
  $nav_tanggal .= "
      <div class='mr2'>
        <button class='btn btn-success btn-sm' name=btn_filter_tanggal>Filter</button>
      </div>
    ";

  echo  "
    <form method=post class='mb4 tengah'>
      <div class=mb2>Tanggal Pemeriksaan:</div>
      <div class='flex flex-center'>
        $nav_tanggal
      </div>
    </form>
  ";
  if (!$get_tanggal_periksa) {
    echo div_alert('info', 'Silahkan pilih salah satu tanggal.');
    exit;
  }
}
