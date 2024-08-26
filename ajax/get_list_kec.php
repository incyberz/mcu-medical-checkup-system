<?php
include "../conn.php";
$id_kab = $_GET['id_kab'] ?? die("Error @ajax :: [id_kab] belum terdefinisi.");;
$keyword = $_GET['keyword'] ?? die("Error @ajax :: [keyword] belum terdefinisi.");;

$sql_like = $keyword == 'none' ? 1 : "nama_kec like '%$keyword%'";

$s = "SELECT * FROM tb_kec WHERE $sql_like AND id_kab='$id_kab' ORDER BY nama_kec LIMIT 100";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
while ($d = mysqli_fetch_assoc($q)) {
  $li .= "<div class='item_kec pointer p1'>$d[nama_kec] - $d[id_kec]</div>";
}
echo $li ? $li : "<div class='red f12 mt1 mb4'>Kecamatan dengan keyword <b>$keyword</b> tidak ditemukan</div>";
