<section id="produk" class="produk">
  <div class="container">

    <div class="section-title">
      <h2><?= $produk['title'] ?></h2>
      <p><?= $produk['desc'] ?></p>
    </div>


    <div class="row">
      <?php
      $edit_section = $role == 'admin' ? edit_section('produk', 'produk (program kami)') : '';

      // data from database
      $arr = [
        [
          'Program MCU',
          'Biaya mulai dari Rp70.000 dengan Paket Lengkap untuk prasyarat ketenagakerjaan. Biaya bersifat negotiable!',
          'mcu-corporate.jpg',
          '?program-mcu',
          ''
        ],
        [
          'Program In-House Clinic',
          'Program In-House Clinic untuk Corporate (perusahaan) dan Sekolah/Pesantren',
          'inhouse-clinic.avif',
          '?inhouse-clinic',
          ''
        ],
        [
          'Klinik Pratama',
          'Kami melayani pengobatan umum...',
          'klinik-pratama.avif',
          '?klinik-pratama',
          ''
        ],
      ];

      foreach ($arr as $arr_item) {
        echo "
          <div class='col-lg-4 col-md-6 d-flex align-items-stretch'>
            <div class='icon-box'>
              <h4>
                <a href='$arr_item[3]'>
                  <img class='img-mcu' src='assets/img/$arr_item[2]' alt='mcu-corporate'>
                  <div>$arr_item[0]</div>
                </a>
              </h4>
              <p><span class='shout'>$arr_item[4]</span></p>
              <p>$arr_item[1]</p>
            </div>
          </div>
        ";
      }


      ?>
    </div>
    <?= $edit_section ?>

  </div>
</section>