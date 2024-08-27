<?php
include "../conn.php";
$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? die("Error @ajax :: [id_pemeriksaan] belum terdefinisi.");
$id_perusahaan = $_GET['id_perusahaan'] ?? die("Error @ajax :: [id_perusahaan] belum terdefinisi.");
$id_import = $_GET['id_import'] ?? die("Error @ajax :: [id_import] belum terdefinisi.");
$keyword = $_GET['keyword'] ?? die("Error @ajax :: [keyword] belum terdefinisi.");
$limit = 20;


# ============================================================
# ADD FROM PASIEN CORPORATE || COR-MAN
# ============================================================
$arr_join = [
  'tb_harga_perusahaan b ON a.id_harga_perusahaan=b.id',
  'tb_order b ON a.order_no=b.order_no'
];

$div = '';
foreach ($arr_join as $join_b) {
  $s = "SELECT a.id,a.nama,c.arr_tanggal_by 
  FROM tb_pasien a 
  JOIN $join_b
  JOIN tb_hasil_pemeriksaan c ON a.id=c.id_pasien 
  WHERE (a.nama like '%$keyword%' OR a.id = '$keyword')
  AND b.id_perusahaan = $id_perusahaan  
  ORDER BY a.nama LIMIT $limit";
  // die($s);
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $i = 0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $nama = ucwords(strtolower($d['nama']));
    // $strip_id = strpos("salt||$d[arr_tanggal_by]", "||$id_pemeriksaan=") ? " (sudah)" : " - $d[id]";
    $info_sudah = strpos("salt||$d[arr_tanggal_by]", "||$id_pemeriksaan=") ? " (sudah) " : ''; // mode replace
    $strip_id = " - $d[id]"; // mode insert OR replace
    $div .= "<div class='item_pasien pointer p1' id=item_pasien__$id_import>$info_sudah$nama$strip_id</div>";

    if ($i == $limit) $div .= "<div style=color:red>Data limited di $limit item, silahkan perbarui keyword</div>";
  }
}



echo $div ? $div : "<div class='red f12 mt1 mb4'>Pasien dengan keyword <b>$keyword</b> tidak ditemukan</div>";
