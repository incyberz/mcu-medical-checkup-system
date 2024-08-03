<?php
include "../conn.php";
$keyword = $_GET['keyword'] ?? die("Error @ajax :: [keyword] belum terdefinisi.");;

$s = "SELECT * FROM tb_kab WHERE nama_kab like '%$keyword%' LIMIT 10";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
while ($d = mysqli_fetch_assoc($q)) {
  // $id=$d['id'];
  $li .= "<li class='item_kab pointer p1'>$d[nama_kab] - $d[id_kab]</li>";
}
echo $li ? $li : "<div class='red f12 mt1 mb4'>Nama Kabupaten dengan keyword <b>$keyword</b> tidak ditemukan</div>";
