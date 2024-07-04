<!DOCTYPE html>
<html lang="en">
<?php
session_start();
// session_destroy();
// exit;
date_default_timezone_set("Asia/Jakarta");
# ================================================
# PHP INDEX
# ================================================
$dm = 0;
$debug = '';
$unset = '<span class="kecil miring red consolas">unset</span>';
$null = '<span class="kecil miring red consolas">null</span>';
$null_gray = '<span class="f12 miring abu consolas">null</span>';
$hideit = 'hideit';
$today = date('Y-m-d');
$detik = date('ymdHis');
$tanggal_show = date('d-F-Y');
$tgl_show = date('d-M-y');
$edit_section = '';

$id_klinik = 1; //klinik MMC
$lokasi_pages = 'pages';
$lokasi_pdf = 'assets/pdf';
$lokasi_img = 'assets/img';
$lokasi_icon = 'assets/img/icon';
$lokasi_paket = 'assets/img/paket';
$lokasi_carousel = 'assets/img/carousel-img';
$lokasi_tim = 'assets/img/dokter-dan-tim';
$lokasi_gallery = 'assets/img/gallery';
$lokasi_partner = 'assets/img/partner';
$lokasi_pasien = 'assets/img/pasien';
$lokasi_ilustrasi = 'assets/img/ilustrasi';
$lokasi_excel = 'assets/xls';
$lokasi_xls = 'assets/xls';

// $whatsapp_klinik = '6285212572979'; // pa ahmad
// $whatsapp_klinik = '6285975096020'; // MMC
// $whatsapp_klinik_show = '0859-7509-6020';

// set auto login
$_SESSION['mmc_username'] = 'nakes1';
$_SESSION['mmc_role'] = 'nakes';

// set logout
// unset($_SESSION['mmc_username']);


// include 'pages/login.php';


# ================================================
# DATA SESSION
# ================================================
$id_user = '';
$is_login = 0;
$id_role = 0; // pengunjung
$role = 'Pengunjung';
$username = '';
$nama_user = '';
$email = '';
$whatsapp = '';

if (isset($_SESSION['mmc_username'])) {
  $is_login = 1;
  $username = $_SESSION['mmc_username'];
}




# ================================================
# KONEKSI KE MYSQL SERVER
# ================================================
include 'conn.php';

# ================================================
# DATA PAGES AT
# ================================================
include 'klinik_data.php';

# ================================================
# USER DATA IF LOGIN
# ================================================
include 'user_data.php';



# ================================================
# INCLUDES
# ================================================
include 'include/insho_functions.php';
include 'include/crud_icons.php';
include 'include/img_icon.php';
include 'include/date_managements.php';
include 'include/session_managements.php';


# ================================================
# GET URL PARAMETER
# ================================================
$parameter = '';
foreach ($_GET as $key => $value) {
  $parameter = $key;
  break;
}

if (!$parameter and $username and $role != 'pasien') {
  jsurl('?dashboard_nakes');
}

# ================================================
# LOGOUT
# ================================================
if ($parameter == 'logout') {
  include 'pages/login/logout.php';
  exit;
}

$back = "<a href='#' onclick='history.back()'><i class='bi bi-arrow-left'></i> Back</a>";

function edit_section($page, $caption = '', $icon = '')
{
  $id = 'edit_' . $page . '__toggle';
  // return "<hr><a class='btn-edit-page' href='?manage-page&p=$page'>$icon Manage Section $caption</a>";
  return "<hr><span class='btn-edit-page btn_aksi' id=$id>$icon Manage Section $caption</span>";
}


?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mutiara Medical Center</title>
  <meta content="Penyedia Jasa Medical Checkup Paket Karyawan dan Individu dengan harga paling terjangkau di Bekasi dan Jawab Barat" name="description">
  <meta content="medical check up karyawan,pemeriksaan kesehatan perusahaan,program kesehatan karyawan,pemeriksaan medis untuk staf,paket medical check up perusahaan,screening kesehatan karyawan,layanan kesehatan perusahaan,check up kesehatan untuk pekerja,kesehatan kerja karyawan,biaya medical check up perusahaan,manfaat medical check up karyawan,klinik kesehatan perusahaan,pemeriksaan kesehatan rutin karyawan,kesehatan dan keselamatan kerja,program wellness perusahaan,konsultasi kesehatan karyawan,pelayanan medis di tempat kerja,tes kesehatan karyawan,check up kesehatan karyawan perusahaan,kebijakan kesehatan perusahaan" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/radio-toolbar.css" rel="stylesheet">
  <script src="assets/vendor/jquery/jquery.min.js"></script>

  <!-- =======================================================
  * Template Name: Medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <?php
  $insho_styles = file_exists('insho_styles.php') ? 'insho_styles.php' : '../insho_styles.php';
  include $insho_styles;
  include 'mmc_styles.php';
  ?>
</head>

<body>

  <?php include 'pages/topbar.php'; ?>
  <?php include 'pages/header.php'; ?>
  <?php include 'pages/save_settings_process.php'; ?>
  <?php
  if (!$parameter) {
    include 'pages/carousel/carousel.php';
    echo '<div>&nbsp;</div>';
    include 'pages/hero.php';
  }
  ?>

  <main id="main">
    <?php
    include 'routing.php';
    include 'pages/debug.php';
    ?>
  </main>

  <?php include 'pages/footer.php'; ?>

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>

<?php include 'include/js_btn_aksi.php'; ?>
<script>
  function onDev() {
    alert('Fitur ini masih dalam tahap pengembangan. Terimakasih sudah mencoba!');
  }
  $(function() {
    $('.on-dev').click(function() {
      onDev()
    })
  })
</script>