<?php
$jenis = $_GET['jenis'] ?? 'mcu';
$s = "SELECT * FROM tb_jenis_program WHERE jenis='$jenis'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) {
  echo div_alert('danger', "Maaf, data jenis program tidak ditemukan.");
} else {
  $d = mysqli_fetch_assoc($q);
  $nama_jenis_program = $d['nama'];
  $deskripsi_jenis_program = $d['deskripsi'];
}

set_title("$nama_jenis_program - $nama_sistem");
$back = "<a href='?produk'>Program lain</a>";
echo "
  <div class='section-title'>
    <h2>$nama_jenis_program</h2>
    <p>$back | $deskripsi_jenis_program</p>
  </div>
";
?>

<section id="produk" class="produk p0">
  <?php
  $target = "program-$jenis.php";
  // die($target);
  // if (file_exists($target) $jenis == 'klinik-pratama') {
  if (file_exists("$lokasi_pages/$target")) {
    // include 'program-klinik-pratama.php';
    include $target;
  } else {
    // echo div_alert('danger m3', "Maaf belum ada handler untuk jenis program $jenis. Anda boleh melaporkan ke kami via Whatsapp di paling atas. Terimakasih.");
    $s = "SELECT *,a.id as id_program FROM tb_program a WHERE a.jenis='$jenis' AND a.id_klinik=$id_klinik";
    // echo $s;
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) {
      echo div_alert('danger m3', "Maaf, belum ada data untuk klasifikasi program <b class='darkblue consolas'>$jenis</b>.");
    } else {
      $divs = '';
      while ($program = mysqli_fetch_assoc($q)) {
        $id_program = $program['id_program'];
        $href = $program['href'] ?? "?program_detail&id_program=$id_program";
        $divs .= "
          <div class='col-lg-6 col-md-6 d-flex align-items-stretch'>
            <div class='icon-box'>
              <h4>
                <a href='$href'>
                  <img class='img-mcu' src='assets/img/$program[image]' alt='mcu-corporate'>
                  <div>$program[nama]</div>
                </a>
              </h4>
              <p><span class='shout'>$program[shout]</span></p>
              <div style='min-height:80px'><p>$program[deskripsi]</p></div>
              <a class='btn btn-success w-100 mt3' href='$href'>$program[caption]</a>
            </div>
          </div>
        ";
      }
      echo "<div class=row>$divs</div>";
    }
  }

  ?>
</section>