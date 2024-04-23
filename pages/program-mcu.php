<div class="section-title">
  <h2>Program MCU</h2>
  <p><?= $back ?> | Terdapat dua jenis Program Medical Checkup yang kami tawarkan yaitu MCU Corporate dan MCU Mandiri.</p>
</div>

<section id="produk" class="produk p0">
  <div class="row">
    <?php

    // data from database
    $mcu_programs = [
      [
        'title' => 'MCU Corporate',
        'desc' => 'Biaya mulai dari <span class="f22">Rp70.000</span> dengan Paket Lengkap untuk prasyarat ketenagakerjaan. Biaya bersifat negotiable! Cocok bagi Anda sebagai HRD atau Pimpinan Perusahaan.',
        'image' => 'mcu-corporate.jpg',
        'src' => '?program-detail&id_jenis=1',
        'caption' => 'Pilih Paket Corporate',
        'shout' => ''
      ],
      [
        'title' => 'MCU Mandiri',
        'desc' => 'Semisal Cek Gula Darah, Cek Darah Lengkap, Cek Urine dan lain sebagainya. Cocok bagi Anda yang ingin mengetahui gejala penyakit secara akurat atau ingin mengecek kondisi kesehatan tubuh secara rutin.',
        'image' => 'mcu-mandiri.avif',
        'src' => '?program-detail&id_jenis=2',
        'caption' => 'Pilih Paket Mandiri',
        'shout' => ''
      ],
    ];

    foreach ($mcu_programs as $mcu_program) {
      echo "
          <div class='col-lg-6 col-md-6 d-flex align-items-stretch'>
            <div class='icon-box'>
              <h4>
                <a href='$mcu_program[src]'>
                  <img class='img-mcu' src='assets/img/$mcu_program[image]' alt='mcu-corporate'>
                  <div>$mcu_program[title]</div>
                </a>
              </h4>
              <p><span class='shout'>$mcu_program[shout]</span></p>
              <div style='min-height:80px'><p>$mcu_program[desc]</p></div>
              <a class='btn btn-success w-100 mt3' href='$mcu_program[src]'>$mcu_program[caption]</a>
            </div>
          </div>
        ";
    }


    ?>
  </div>
</section>