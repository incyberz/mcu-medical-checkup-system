<style>
  .item-corporate {
    background: #ddffffaa;
  }
</style>
<?php
$divs = '';
$id_jenis = $_GET['id_jenis'] ?? die(erid('id_jenis')); //corporate at MMC
$Corporate = $id_jenis == 1 ? 'Corporate' : 'Mandiri';

$s = "SELECT 
a.id as id_paket,
a.nama as nama_paket,
a.deskripsi,
a.info_biaya,
a.biaya,
a.customizable,
(
  SELECT COUNT(1) FROM tb_detail_paket 
  WHERE id_paket=a.id) count_pemeriksaan

FROM tb_paket a 
JOIN tb_jenis_paket b ON a.id_jenis = b.id
WHERE a.id_jenis=$id_jenis ORDER BY no";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$count_valid_paket = 0;
while ($paket = mysqli_fetch_assoc($q)) {
  if ($paket['count_pemeriksaan'] == 0) continue;
  $count_valid_paket++;
  $id_paket = $paket['id_paket'];
  $customizable = $paket['customizable'];

  $where_id = $customizable ? "b.id_klinik=$id_klinik" : " a.id_paket=$paket[id_paket]";

  if ($customizable) {
    $s2 = "SELECT 
    a.id as id_pemeriksaan,
    a.nama as nama_pemeriksaan,
    a.deskripsi 

    FROM tb_pemeriksaan a 
    WHERE a.id_klinik=$id_klinik ";
    $lihat_detail = 'Lihat Pilihan Pemeriksaan';

    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $pilihan = '';
    while ($detail = mysqli_fetch_assoc($q2)) {
      $id_pemeriksaan = $detail['id_pemeriksaan'];
      $deskripsi = $detail['deskripsi'];
      $pilihan .= "
        <tr>
          <td>
            <input type=checkbox name=pemeriksaan__$id_pemeriksaan id=pemeriksaan__$id_pemeriksaan>
          </td>
          <td>
            <label for=pemeriksaan__$id_pemeriksaan class=pointer>
              $detail[nama_pemeriksaan] 
              <div class='f12 abu miring'>$deskripsi</div>
            </label>
          </td>
        </tr>
      ";
    }
    $details = "
      <form method=post>
        <table class='table table-bordered kiri'>
          $pilihan
        </table>
        <button class='btn btn-primary w-100' onclick='onDev()'>Submit Custom Pemeriksaan</button>
      </form>
    ";
    $btn_pilih = '';
  } else {
    $lihat_detail = 'Lihat Detail Pemeriksaan';
    $btn_pilih = "<div class=mt2><a class='btn btn-success w-100' href='?order-paket&id_paket=$paket[id_paket]' >Pilih $paket[nama_paket]</a></div>";

    $s2 = "SELECT 
    b.nama as nama_pemeriksaan

    FROM tb_detail_paket a 
    JOIN tb_pemeriksaan b ON a.id_pemeriksaan =b.id 
    WHERE a.id_paket=$paket[id_paket] ORDER BY no";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $details = '';
    while ($detail = mysqli_fetch_assoc($q2)) {
      $details .= "<li>$detail[nama_pemeriksaan]</li>";
    }
    if ($details) $details = "<ol class='f14 darkabu m0 pl3'>$details</ol>";
  }




  $id_toggle = 'detail' . $id_paket . '__toggle';
  $biaya_show = $paket['biaya'] ? number_format($paket['biaya'], 0) : '';
  $shout = $id_jenis == 1 ?  $paket['info_biaya'] : 'Rp' . $biaya_show;
  $shout = $shout == 'Rp' ? 'Custom Biaya' : $shout;


  $divs .= "
  <div class='col-xl-4 col-md-6'>
    <div class='wadah p2 item-corporate'>
      <h3 >$paket[nama_paket]</h3>
      <div class='f12 abu mt1 mb2'>$paket[deskripsi]</div>
      <div class='f18 consolas darkblue mt1 mb1'>$shout</div>
      <span class='btn_aksi pointer f12' id=$id_toggle> $img_detail $lihat_detail</span>
      <div id=detail$id_paket class='hideit wadah gradasi-kuning mt1 '>$details</div>
      $btn_pilih
    </div>
  </div>
  ";
}

$alert = '';
if (!$count_valid_paket) $alert = div_alert('danger', "Maaf, belum ada Paket yang cocok untuk Program ini. Anda boleh menghubungi kami untuk informasi lebih lanjut dengan cara klik Nomor Whatsapp di paling atas.");

echo "
<div class='section-title'>
  <h2>MCU $Corporate</h2>
  <p>$back | Silahkan Pilih Paket untuk Medical Chekup $Corporate!</p>
</div>

<section id='produk' class='produk p0'>
  <div class='row'>
    $divs
  </div>
  $alert
</section>
";
