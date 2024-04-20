<style>
  .img-tim {
    width: 150px;
    height: 150px;
    object-fit: cover;
    transition: .2s;
    border-radius: 50%;
    border: solid 5px white;
    box-shadow: 0 0 5px gray;
    cursor: pointer;
  }

  @media (max-width:1024px) {
    .img-tim {
      border: solid 4px white;
      width: 120px;
      height: 120px;
    }

  }

  @media (max-width:600px) {
    .img-tim {
      border: solid 3px white;
      width: 90px;
      height: 90px;
    }

  }

  @media (max-width:360px) {
    .img-tim {
      border: solid 2px white;
      width: 70px;
      height: 70px;
    }

  }
</style>
<section id="doctors" class="doctors">
  <div class="container">

    <div class="section-title">
      <h2>Dokter dan Tim</h2>
      <p>Perkenalkan kami adalah Para Dokter Professional dan juga Tim Medis berpengalaman.</p>
    </div>

    <div class="row">

      <?php
      $edit_section = $role == 'admin' ? edit_section('doctors', 'dokter dan tim') : '';

      $arr = [
        [
          'image' => 'ahmad.png',
          'nama' => 'Dr. dr. Ahmad Nurkamali AZ, M.M',
          'jabatan' => 'Kepala Klinik dan MCU',
          'shout' => 'Selamat datang di My MCU! Semoga Anda mendapat pelayanan yang terbaik dari kami.',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'iin.jpg',
          'nama' => 'Iin Sholihin, S.T., M.Kom., MTA',
          'jabatan' => 'IT Support dan Programmer',
          'shout' => 'Otomatisasi medis dengan Teknologi Informasi membuat proses dan reporting medis terpantau dari smartphone Anda.',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'mutiara.png',
          'nama' => 'dr. Mutiara Putri Camelia',
          'jabatan' => 'Dokter Umum',
          'shout' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'ani.png',
          'nama' => 'dr. Any Mariani',
          'jabatan' => 'Dokter Gigi',
          'shout' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'rhony.png',
          'nama' => 'Rhony Kustiawan',
          'jabatan' => 'Tenaga Medis',
          'shout' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
      ];

      foreach ($arr as $key => $item) {
        echo "
          <div class='col-lg-6'>
            <div class='member d-flex align-items-start'>
              <div class='piczzz'><img src='assets/img/dokter-dan-tim/$item[image]' class='img-tim' alt='$item[image]'></div>
              <div class='member-info'>
                <h4>$item[nama]</h4>
                <span>$item[jabatan]</span>
                <p>$item[shout]</p>
                <div class='social'>
                  <a href='$item[twitter]'><i class='ri-twitter-fill'></i></a>
                  <a href='$item[facebook]'><i class='ri-facebook-fill'></i></a>
                  <a href='$item[instagram]'><i class='ri-instagram-fill'></i></a>
                  <a href='$item[linkedin]'> <i class='ri-linkedin-box-fill'></i> </a>
                </div>
              </div>
            </div>
          </div>
        ";
      }


      ?>



    </div>
    <?= $edit_section ?>

  </div>
</section><!-- End Doctors Section -->