<div class="section-title">
  <h2>Program MCU</h2>
  <p><?= $back ?> | Terdapat dua jenis Program Medical Checkup yang kami tawarkan yaitu MCU Corporate dan MCU Mandiri.</p>
</div>

<section id="produk" class="produk p0">
  <div class="row">
    <?php
    $jenis = $_GET['jenis'] ?? 'mcu';

    $s = "SELECT * FROM tb_program WHERE jenis='$jenis'";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    while ($mcu_program = mysqli_fetch_assoc($q)) {
      echo "
        <div class='col-lg-6 col-md-6 d-flex align-items-stretch'>
          <div class='icon-box'>
            <h4>
              <a href='$mcu_program[href]'>
                <img class='img-mcu' src='assets/img/$mcu_program[image]' alt='mcu-corporate'>
                <div>$mcu_program[nama]</div>
              </a>
            </h4>
            <p><span class='shout'>$mcu_program[shout]</span></p>
            <div style='min-height:80px'><p>$mcu_program[deskripsi]</p></div>
            <a class='btn btn-success w-100 mt3' href='$mcu_program[href]'>$mcu_program[caption]</a>
          </div>
        </div>
      ";
    }

    ?>
  </div>
</section>