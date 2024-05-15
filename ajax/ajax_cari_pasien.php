<?php
include "../conn.php";
$keyword = $_GET['keyword'] ?? die("Error @ajax :: keyword index belum terdefinisi.");;
$order_no = $_GET['order_no'] ?? die("Error @ajax :: order_no index belum terdefinisi.");;

$s = "SELECT  
a.id as id_pasien,
a.nama as nama_pasien,
a.nikepeg as nik_pasien,
a.status as kode_status,
a.foto_profil,
a.nomor, -- nomor MCU
c.id as id_paket,
c.singkatan as singkatan_paket,
(SELECT nama FROM tb_status_pasien WHERE status=a.status) status_pasien 



FROM tb_pasien a 
JOIN tb_order b ON a.order_no=b.order_no 
JOIN tb_paket c ON b.id_paket=c.id 
WHERE a.order_no = '$order_no' -- Order yang aktif saja
AND (
  a.nama LIKE '%$keyword%' 
  OR a.nomor LIKE '%$keyword%' 
  OR a.nikepeg LIKE '%$keyword%' 
)
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$jumlah_pasien = mysqli_num_rows($q);


$i = 0;
if (mysqli_num_rows($q) == 0) die(div_alert('danger', "Data pasien dengan keyword <b>$keyword</b> tidak ditemukan"));
$tr = '';
$limited_info = '';
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $id_pasien = $d['id_pasien'];
  $id_paket = $d['id_paket'];
  $singkatan_paket = $d['singkatan_paket'];
  $status_show = '<div class="f12 red mt1">Status: belum pernah login</div>';

  $btn_print = "<a target=_blank class='btn btn-warning w-100' href='?print-label&id_pasien=$id_pasien&id_paket=$id_paket&nama_paket=$singkatan_paket'>Print Label Medis zzz</a>";

  // $d['kode_status'] = 8;

  if ($d['kode_status']) {
    if ($d['kode_status'] < 7) {
      $src = 'assets/img/icon/warning.png';
    } elseif ($d['kode_status'] == 7) {
      $src = 'assets/img/icon/check.png';
      $btn_print = "<a target=_blank class='btn btn-primary w-100' href='?print-label&id_pasien=$id_pasien&id_paket=$id_paket&nama_paket=$singkatan_paket'>Print Label Medis</a>";
    } else { // status > 7
      $src = 'assets/img/icon/check_brown.png';
      $btn_print = "<a target=_blank class='btn btn-secondary w-100' href='?print-label&id_pasien=$id_pasien&id_paket=$id_paket&nama_paket=$singkatan_paket'>Print Kembali Label</a>";
    }
    $icon = "<img src=$src height=20px>";
    $status_show = "
      <div class='f10 abu mt1 '>Status: </div>
      <div class='mb2 f12'>$d[kode_status] ~ $d[status_pasien] $icon</div>
      ";
  }

  $btn_print = ''; // fitur aborted
  $src = "assets/img/profile_na.jpg";
  if ($d['foto_profil']) {
    $src = "assets/img/pasien/$d[foto_profil]";
    $src_ajax = "../assets/img/pasien/$d[foto_profil]";
    if (!file_exists($src_ajax)) {
      $src = "assets/img/profile_missing.jpg";
    }
  }

  $href = "?cari-pasien&id_pasien=$id_pasien";
  $tr .= "
    <tr>
      <td>$i</td>
      <td>
        <a href='$href'>
          <img src='$src' class='foto_profil' />
        </a>
      </td>
      <td>
        <div><a href='$href'>$d[nama_pasien]</a></div>
        <div>NIK. $d[nik_pasien]</div>
        <div>MCU-$d[nomor] | $d[singkatan_paket]</div>
        <div>$status_show</div>
        $btn_print
      </td>
    </tr>
  ";

  if ($i == 10) {
    $limited_info = "
      <div class='mb2 pb2' style='border-bottom: solid 1px #ccc'>
        <span class='darkred miring'>limit data 10 dari $jumlah_pasien pasien</span>, 
        <span class='darkblue f12'>silahkan perbarui keyword</span>
      </div>
    ";
    break;
  }
}

$jumlah_pasien_info = $limited_info ? $limited_info : "
<div class='f14 biru mb2 pb2' style='border-bottom: solid 1px #ccc'>
  <span class='biru f24'>$jumlah_pasien</span>
  <span class='abu f14'>pasien ditemukan</span>
</div>
";

echo "
$jumlah_pasien_info
<table class=table>
  $tr
</table>
";
