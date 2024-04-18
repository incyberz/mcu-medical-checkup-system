<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEDICAL RECORD MMC - CLINIC | KLIKNIK MUTIARA 1</title>
  <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
  <!-- <link rel="stylesheet" href="../../assets/css/insho-styles.css" /> -->
  <link rel="stylesheet" href="../../assets/css/buku.css" />
  <link rel="stylesheet" href="../../assets/css/radio-toolbar.css" />

  <script src="../../assets/js/jquery.min.js"></script>
  <style>
    h2 {
      text-align: center;
      margin: 15px 0 10px 0;
    }

    section {
      min-height: 100vh;
    }
  </style>
</head>
<?php
include 'book_var.php';
include '../../../insho_styles.php';
include '../../include/mcu_functions.php';
include '../../include/insho_functions.php';
include '../../include/date_managements.php';
include '../../include/img_icon.php';
?>

<body>
  <!-- <div style="position:fixed; top:500px; right:10px">
    <button class="btn btn-success f30" onclick="location.reload()" style="padding: 5px 15px">REFRESH</button>
  </div> -->
  <div class="container">
    <section>
      <?php
      include 'cover.php';

      # =======================================================
      # NAMA PERUSAHAAN DAN BIODATA KARYAWAN
      # =======================================================
      echo '</section><section>';
      include 'nomor-mcu.php';
      echo '</section><section>';
      include 'perusahaan.php';
      echo '</section><section>';
      include 'biodata.php';

      # ===================================================
      # RIWAYAT PENYAKIT
      # ===================================================
      echo '</section><section>';
      include 'riwayat-penyakit.php';
      echo '</section><section>';
      include 'riwayat-pengobatan.php';
      echo '</section><section>';
      include 'riwayat-penyakit-keluarga.php';
      echo '</section><section>';
      include 'pola-hidup.php';
      echo '</section><section>';
      include 'gejala-penyakit.php';

      # ===================================================
      # KELUHAN SEKARANG
      # ===================================================
      echo '</section><section>';
      include 'keluhan-sekarang.php';

      # ===================================================
      # PEMERIKSAAN FISIK AWAL
      # ===================================================
      echo '</section><section>';
      include 'pemeriksaan-fisik-awal.php';

      # ===================================================
      # PEMERIKSAAN MATA
      # ===================================================
      echo '</section><section>';
      include 'pemeriksaan-mata.php';

      # ===================================================
      # PEMERIKSAAN GIGI
      # ===================================================
      echo '</section><section>';
      include 'pemeriksaan-gigi.php';

      # ===================================================
      # PEMERIKSAAN FISIK
      # ===================================================
      echo '</section><section>';
      include 'pemeriksaan-fisik.php';

      ?>
    </section>
  </div>
</body>
<script src="../../assets/js/buku.js"></script>

</html>