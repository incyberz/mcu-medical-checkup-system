<div class="section-title">
  <h2>MCU Mandiri</h2>
  <p><?= $back ?> | Silahkan Pilih Paket untuk Medical Chekup Mandiri!</p>
</div>

<section id="produk" class="produk p0">
  <div class="row">
    <?php

    // data from database
    $pakets = [
      [
        'title' => 'Paket Gula Darah',
        'desc' => 'Deskripsi Paket Gula Darah... Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque tempore autem accusantium! Provident, rerum in error quod possimus pariatur quisquam at. Tenetur qui architecto et iure cumque labore? Maiores, sapiente.',
        'src' => '?paket-mcu&paket=Gula Darah',
        'shout' => 'Rp70.000'
      ],
      [
        'title' => 'Paket Urine Lengkap',
        'desc' => 'Deskripsi Paket Urine Lengkap... Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque tempore autem accusantium! Provident, rerum in error quod possimus pariatur quisquam at. Tenetur qui architecto et iure cumque labore? Maiores, sapiente.',
        'src' => '?paket-mcu&paket=Urine Lengkap',
        'shout' => 'Rp80.000'
      ],
      [
        'title' => 'Paket Demam Berdarah',
        'desc' => 'Deskripsi Paket Demam Berdarah... Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque tempore autem accusantium! Provident, rerum in error quod possimus pariatur quisquam at. Tenetur qui architecto et iure cumque labore? Maiores, sapiente.',
        'src' => '?paket-mcu&paket=Demam Berdarah',
        'shout' => 'Rp100.000'
      ],
      [
        'title' => 'Paket EKG',
        'desc' => 'Deskripsi Paket EKG... Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque tempore autem accusantium! Provident, rerum in error quod possimus pariatur quisquam at. Tenetur qui architecto et iure cumque labore? Maiores, sapiente.',
        'src' => '?paket-mcu&paket=EKG',
        'shout' => 'Rp120.000'
      ],
      [
        'title' => 'Paket Custom',
        'desc' => 'Deskripsi Paket Custom... Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque tempore autem accusantium! Provident, rerum in error quod possimus pariatur quisquam at. Tenetur qui architecto et iure cumque labore? Maiores, sapiente.', ',',
        'src' => '?paket-mcu&paket=Custom',
        'shout' => 'Custom Biaya'
      ],
    ];

    foreach ($pakets as $key => $paket) {
      $details = '';
      $id_toggle = 'detail' . $key . '__toggle';
      echo "
      <div class='col-xl-4 col-md-6'>
        <div class='wadah p2'>
          <h3 >$paket[title]</h3>
          <div class='f12 abu mt1 mb2'>$paket[desc]</div>
          <div class='f18 consolas darkblue mt1 mb1'>$paket[shout]</div>
          <span class='btn_aksi pointer f12' id=$id_toggle> $img_detail Lihat Detail</span>
          <div id=detail$key class='hideit wadah gradasi-kuning mt1'>$details</div>
          <div class=mt2><a class='btn btn-success w-100' href='$paket[src]' >Pilih $paket[title]</a></div>
        </div>
      </div>
      ";
    }


    ?>
  </div>
</section>