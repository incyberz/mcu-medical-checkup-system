<style>
  .item-corporate {
    /* background: #ddffff; */
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    /* border: solid 2px blue; */
  }

  #produk h3 {
    font-size: 20px;
  }
</style>
<?php

$divs = '';
$id_program = $_GET['id_program'] ?? die(erid('id_program')); //corporate at MMC

$s = "SELECT * FROM tb_program WHERE id='$id_program'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', "Maaf, data jenis program tidak ditemukan.");
} else {
  $d = mysqli_fetch_assoc($q);
  $nama_program = $d['nama'];
  $deskripsi_program = $d['deskripsi'];
}

set_title("$nama_program - $nama_sistem");
echo "
  <div class='section-title'>
    <h2>$nama_program</h2>
    <p>$back | $deskripsi_program</p>
  </div>
";


$s = "SELECT 
a.id as id_paket,
a.nama as nama_paket,
a.deskripsi,
a.info_biaya,
a.biaya,
a.customizable,
(
  SELECT COUNT(1) FROM tb_paket_detail 
  WHERE id_paket=a.id) count_pemeriksaan

FROM tb_paket a 
JOIN tb_program b ON a.id_program = b.id
WHERE a.id_program=$id_program 
AND status=1 -- status paket yang aktif 
AND customizable is null 
ORDER BY no";
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
    a.id as id_paket_sub,
    a.nama as nama_pemeriksaan,
    a.deskripsi 

    FROM tb_paket_sub a 
    WHERE a.id_klinik=$id_klinik 
    AND status=1 
    AND for_opsional=1 
    ";
    $lihat_detail = 'Lihat Pilihan Pemeriksaan';

    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $pilihan = '';
    while ($detail = mysqli_fetch_assoc($q2)) {
      $id_paket_sub = $detail['id_paket_sub'];
      $deskripsi = $detail['deskripsi'];
      $pilihan .= "
        <tr>
          <td>
            <input type=checkbox name=pemeriksaan__$id_paket_sub id=pemeriksaan__$id_paket_sub>
          </td>
          <td>
            <label for=pemeriksaan__$id_paket_sub class=pointer>
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

    FROM tb_paket_detail a 
    JOIN tb_paket_sub b ON a.id_paket_sub =b.id 
    WHERE a.id_paket=$paket[id_paket] 
    ORDER BY no";
    $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
    $details = '';
    while ($detail = mysqli_fetch_assoc($q2)) {
      $details .= "<li>$detail[nama_pemeriksaan]</li>";
    }
    if ($details) $details = "<ol class='f14 darkabu m0 pl3'>$details</ol>";
  }




  $id_toggle = 'detail' . $id_paket . '__toggle';
  $biaya_show = $paket['biaya'] ? number_format($paket['biaya'], 0) : '';
  $shout = $id_program == 1 ?  $paket['info_biaya'] : 'Rp' . $biaya_show;
  $shout = $shout == 'Rp' ? 'Custom Biaya' : $shout;

  $src = "assets/img/paket/$id_paket.jpg";
  if (file_exists($src)) {
    $paket =  "<img src='$src' class='w-100 br5'>";
  } else {
    $paket = "        
      <h3 >$paket[nama_paket]</h3>
      <div class='f14  mt1 mb2'>$paket[deskripsi]</div>
    ";
  }

  $divs .= "
  <div class='col-xl-4 col-md-6'>
    <div class='wadah p2 item-corporate gradasi-toska'>
      <div>
        $paket
      </div>
      <div class='f18 consolass darkblue mt1 mb1'>$shout</div>
      <div>
        <span class='btn_aksi pointer f12' id=$id_toggle> $img_detail $lihat_detail</span>
        <div id=detail$id_paket class='hideit wadah gradasi-kuning mt1 '>$details</div>
      </div>
      <div>
        $btn_pilih
      </div>
    </div>
  </div>
  ";
}

$alert = '';
if (!$count_valid_paket) $alert = div_alert('danger', "Maaf, belum ada Paket yang cocok untuk Program ini. Anda boleh menghubungi kami untuk informasi lebih lanjut dengan cara klik Nomor Whatsapp di paling atas.");

echo "
<section id='produk' class='produk p0'>
  <div class='row'>
    $divs
  </div>
  $alert
</section>
";
