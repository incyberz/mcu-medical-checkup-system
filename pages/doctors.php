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


  /* SOCIAL SPAN OR LINK */
  .doctors .member .social div {
    transition: ease-in-out 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50px;
    width: 32px;
    height: 32px;
    background: #d0bcd5;
    margin-right: 10px;
    cursor: no-drop;
  }

  .doctors .member .social div i {
    color: #fff;
    font-size: 16px;
    margin: 0 2px;
  }

  .doctors .member .social div:hover {
    background: #cc7719;
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
          'image' => 'ahmad.jpg',
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
          'image' => 'mutiara.jpg',
          'nama' => 'dr. Mutiara Putri Camelia',
          'jabatan' => 'Dokter Umum',
          'shout' => '',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'nila.jpg',
          'nama' => 'Nila Rosita',
          'jabatan' => 'Kepala Keuangan',
          'shout' => '',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'meyda.jpg',
          'nama' => 'Almeyda Putra Herdian, S.Kom',
          'jabatan' => 'Staf Marketing',
          'shout' => '',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        // [
        //   'image' => 'ani.png',
        //   'nama' => 'dr. Any Mariani',
        //   'jabatan' => 'Dokter Gigi',
        //   'shout' => '',
        //   'twitter' => '#',
        //   'facebook' => '#',
        //   'instagram' => '#',
        //   'linkedin' => '#',
        // ],
        [
          'image' => 'rhony.jpg',
          'nama' => 'Rhony Kustiawan',
          'jabatan' => 'Tenaga Medis',
          'shout' => '',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'herman.jpg',
          'nama' => 'Hermanto',
          'jabatan' => 'Tenaga Medis',
          'shout' => '',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
        [
          'image' => 'heru.jpg',
          'nama' => 'Heru Susanto',
          'jabatan' => 'Kepala Kepegawaian',
          'shout' => '',
          'twitter' => '#',
          'facebook' => '#',
          'instagram' => '#',
          'linkedin' => '#',
        ],
      ];

      function no_medsos($medsos, $nama)
      {
        return "<div onclick='alert(\"Maaf, saudara/i $nama belum memasukan data $medsos-nya.\")'><i class='ri-$medsos-fill'></i></div>";
      }
      function link_medsos($medsos)
      {
        return "<a href='$medsos'><i class='ri-$medsos-fill'></i></a>";
      }

      foreach ($arr as $key => $item) {
        $nama = $item['nama'];
        $link_twitter = strlen($item['twitter']) <= 1 ? no_medsos('twitter', $nama) : link_medsos('twitter');
        $link_facebook = strlen($item['facebook']) <= 1 ? no_medsos('facebook', $nama) : link_medsos('facebook');
        $link_instagram = strlen($item['instagram']) <= 1 ? no_medsos('instagram', $nama) : link_medsos('instagram');
        $link_linkedin = strlen($item['linkedin']) <= 1 ? no_medsos('linkedin', $nama) : link_medsos('linkedin');

        echo "
          <div class='col-lg-6'>
            <div class='member d-flex align-items-start'>
              <div class='piczzz'><img src='assets/img/dokter-dan-tim/$item[image]' class='img-tim' alt='$item[image]'></div>
              <div class='member-info'>
                <h4>$item[nama]</h4>
                <span>$item[jabatan]</span>
                <p>$item[shout]</p>
                <div class='social'>
                  $link_twitter
                  $link_facebook
                  $link_instagram
                  $link_linkedin
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