<?php
$judul = 'Pendaftar Home';
$sub_judul = "Selamat datang $nama_user di MMC Information System";
set_title($judul);
set_h2($judul, $sub_judul);
only(['pendaftar']);

$s = "SELECT 
a.order_no,
a.tanggal_order,
a.status,
b.nama as nama_paket,
c.nama as program,
d.nama as status_order,
(SELECT foto_profil FROM tb_pendaftar WHERE username=a.username_pendaftar) foto_profil  
FROM tb_order a 
JOIN tb_paket b ON a.id_paket=b.id 
JOIN tb_program c ON b.id_program=c.id 
JOIN tb_status_order d ON a.status=d.status 
WHERE username_pendaftar='$username'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  die(div_alert('danger', 'Data order tidak ditemukan. Silahkan hubungi admin MMC pada nomor whatsapp dipaling atas.'));
} else {
  $divs = '';
  while ($d = mysqli_fetch_assoc($q)) {
    $order_no = $d['order_no'];
    $tanggal_order = $d['tanggal_order'];
    $tgl = date('d-F-Y', strtotime($tanggal_order));
    $status = $d['status'];
    // $foto_profil = $d['foto_profil'];
    $status_show = $d['status_order'];

    $divs .= "
      <div class='card' style='width: 400px;'>
        <div class='card-body'>
          <h5 class='card-title'>$d[nama_paket]</h5>
          <p class='card-text'>Anda memilih Paket: <b>$d[nama_paket]</b> pada program: <i>$d[program]</i> pada tanggal $tgl dengan status: <i class=green>$status_show</i></p>
          <a href='?order_paket-lanjutan&order_no=$order_no' class='btn btn-primary'>Lanjutkan Order</a>
        </div>
      </div>    
    ";
  }
  echo "<div class='d-flex'>$divs</div>";
}
