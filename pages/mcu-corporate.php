<style>
  .item-corporate {
    background: #ddffffaa;
  }
</style>
<div class="section-title">
  <h2>MCU Corporate</h2>
  <p><?= $back ?> | Silahkan Pilih Paket untuk Medical Chekup Corporate!</p>
</div>

<section id="produk" class="produk p0">
  <div class="row">
    <?php
    $id_jenis = 1; //corporate at MMC
    $s = "SELECT 
    a.id as id_paket,
    a.nama as nama_paket,
    a.deskripsi,
    a.info_biaya,
    a.biaya,
    (
      SELECT COUNT(1) FROM tb_detail_paket 
      WHERE id_paket=a.id) count_pemeriksaan

    FROM tb_paket a 
    WHERE a.id_jenis=$id_jenis ORDER BY no";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $count_valid_paket = 0;
    while ($paket = mysqli_fetch_assoc($q)) {
      if ($paket['count_pemeriksaan'] == 0) continue;
      $count_valid_paket++;
      $id_paket = $paket['id_paket'];

      $s2 = "SELECT * FROM tb_detail_paket WHERE id_paket=$paket[id_paket] ORDER BY no";
      $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));


      $details = '';
      $id_toggle = 'detail' . $id_paket . '__toggle';
      $shout = $id_jenis == 1 ?  $paket['info_biaya'] : 'Rp' . $paket['biaya'];


      echo "
      <div class='col-xl-4 col-md-6'>
        <div class='wadah p2 item-corporate'>
          <h3 >$paket[nama_paket]</h3>
          <div class='f12 abu mt1 mb2'>$paket[deskripsi]</div>
          <div class='f18 consolas darkblue mt1 mb1'>$shout</div>
          <span class='btn_aksi pointer f12' id=$id_toggle> $img_detail Lihat Detail Pemeriksaan</span>
          <div id=detail$id_paket class='hideit wadah gradasi-kuning mt1'>$details</div>
          <div class=mt2><a class='btn btn-success w-100' href='?paket-mcu&id=$paket[id_paket]' >Pilih $paket[nama_paket]</a></div>
        </div>
      </div>
      ";
    }




    ?>
  </div>

  <?php
  // jika belum ada paket yang valid, tampilkan pesan alert
  if (!$count_valid_paket) echo div_alert('danger', "Maaf, belum ada Paket yang cocok untuk Program ini. Anda boleh menghubungi kami untuk informasi lebih lanjut dengan cara klik Nomor Whatsapp di paling atas."); ?>
</section>