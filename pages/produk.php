<style>
  .produk .icon-box {
    text-align: center;
    border: 1px solid #d5e1ed;
    padding: 80px 20px;
    transition: all ease-in-out 0.3s;
  }

  .produk .icon-box .icon {
    margin: 0 auto;
    width: 64px;
    height: 64px;
    background: #1977cc;
    border-radius: 5px;
    transition: all 0.3s ease-out 0s;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    transform-style: preserve-3d;
    position: relative;
    z-index: 2;
  }

  .produk .icon-box .icon i {
    color: #fff;
    font-size: 28px;
    transition: ease-in-out 0.3s;
  }

  .produk .icon-box .icon::before {
    position: absolute;
    content: "";
    left: -8px;
    top: -8px;
    height: 100%;
    width: 100%;
    background: rgba(25, 119, 204, 0.2);
    border-radius: 5px;
    transition: all 0.3s ease-out 0s;
    transform: translateZ(-1px);
    z-index: -1;
  }

  .produk .icon-box h4 {
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 24px;
  }

  .produk .icon-box h4 a {
    color: #2c4964;
  }

  .produk .icon-box p {
    line-height: 24px;
    font-size: 14px;
    margin-bottom: 0;
  }

  .produk .icon-box:hover {
    background: #1977cc;
    border-color: #1977cc;
  }

  .produk .icon-box:hover .icon {
    background: #fff;
  }

  .produk .icon-box:hover .icon i {
    color: #1977cc;
  }

  .produk .icon-box:hover .icon::before {
    background: rgba(255, 255, 255, 0.3);
  }

  .produk .icon-box:hover h4 a,
  .produk .icon-box:hover p {
    color: #fff;
  }

  /* my styles */
  .img-mcu {
    width: 300px;
    height: 200px;
    object-fit: cover;
    margin: 0 0 15px 0;
    transition: .2s;
    border-radius: 10px;
  }

  .img-mcu:hover {
    transform: scale(1.1);
  }

  .shout {
    color: #33a;
    font-weight: bold;
    font-size: 24px;
    font-family: consolas;
  }

  .produk .icon-box:hover .shout {
    color: #ff0;
  }
</style>
<section id="produk" class="produk">
  <div class="container">

    <div class="section-title">
      <h2>Paket MCU Harga Terbaik</h2>
      <p>Buktikan dan dapatkan harga terbaik paling terjangkau untuk semua produk kami!</p>
    </div>


    <div class="row">
      <?php
      $arr = [
        [
          'Paket MCU Corporate',
          'Harga mulai dari Rp70.000 dengan Paket Lengkap untuk prasyarat ketenagakerjaan. Marketing Fee is fully negotiable!',
          'mcu-corporate.jpg',
          '#zzz',
          'Best in Price!'
        ],
        [
          'Paket MCU Mandiri',
          'Bagi Anda yang ingin melakukan Medical Checkup secara mandiri. Anda dapat memilih Paket dan jenis pemeriksaan untuk Medical Checkup',
          'mcu-mandiri.avif',
          '#zzz',
          'Customizable!'
        ],
        [
          'Penyelenggara Klinik Sekolah dan Poskestren',
          'Hanya dengan Rp12.500/bulan para siswa dapat pemeriksaan dokter gratis, perawatan, pengobatan, hingga Medical Checkup',
          'klinik-sekolah.avif',
          'assets/pdf/proposal-klinik-sekolah-dan-poskestren.pdf',
          'Super Murah!'
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

  </div>
</section>