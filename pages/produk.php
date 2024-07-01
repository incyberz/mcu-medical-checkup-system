<section id="produk" class="produk">
  <div class="container">

    <div class="section-title">
      <h2><?= $produk['title'] ?></h2>
      <p><?= $produk['desc'] ?></p>
    </div>


    <div class="row">
      <?php
      $edit_section = $role == 'admin' ? edit_section('produk', 'produk (program kami)') : '';
      $edit_section .= "    
        <div id=edit_produk class='hideit wadah mt2 gradasi-kuning'>
          Untuk manage Program dan Paket-paketnya silahkan menuju laman <a href='?manage_paket'>Manage Paket</a>.
        </div>
      ";

      $s = "SELECT * FROM tb_jenis_program ORDER BY no";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      if (!mysqli_num_rows($q)) {
        echo div_alert('danger', 'Maaf, belum ada jenis program pada klinik ini.');
      } else {
        while ($d = mysqli_fetch_assoc($q)) {
          echo "
            <div class='col-lg-4 col-md-6 d-flex align-items-stretch'>
              <div class='icon-box'>
                <h4>
                  <a href='$d[href]'>
                    <img class='img-mcu' src='assets/img/$d[image]' alt='mcu-corporate'>
                    <div>$d[nama]</div>
                  </a>
                </h4>
                <p><span class='shout'>$d[shout]</span></p>
                <p>$d[deskripsi]</p>
              </div>
            </div>
          ";
        }
      }


      ?>
    </div>
    <?= $edit_section ?>

  </div>
</section>