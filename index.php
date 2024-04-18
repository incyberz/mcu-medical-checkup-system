<?php
session_start();
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


// set auto login
// $_SESSION['mcu_username'] = 'wh';

// set logout
// unset($_SESSION['mcu_username']);


// include 'pages/login.php';


# ================================================
# DATA SESSION
# ================================================
$id_user = '';
$is_login = 0;
$id_role = 0; // pengunjung
$sebagai = 'Pengunjung';
$username = '';
$nama_user = '';
$email = '';
$no_wa = '';

if (isset($_SESSION['mcu_username'])) {
  $is_login = 1;
  $username = $_SESSION['mcu_username'];
}



# ================================================
# KONEKSI KE MYSQL SERVER
# ================================================
include 'conn.php';

# ================================================
# USER DATA IF LOGIN
# ================================================
include 'data_user.php';



# ================================================
# INCLUDES
# ================================================
include 'include/insho_functions.php';
include 'include/wms_functions.php';
include 'include/data_perusahaan.php';
include 'include/crud_icons.php';
include 'include/arr_master.php';
include 'include/arr_sheet.php';


# ================================================
# GET URL PARAMETER
# ================================================
$parameter = '';
foreach ($_GET as $key => $value) {
  $parameter = $key;
  break;
}

# ================================================
# LOGOUT
# ================================================
if ($parameter == 'logout') {
  include 'pages/login/logout.php';
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Mutiara MCU Information System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

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

  <!-- =======================================================
  * Template Name: medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex justify-content-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope"></i> <a href="mailto:mmcpjk3@gmail.com">mmcpjk3@gmail.com</a>
        <i class="bi bi-phone"></i> 021-8909 5776
      </div>
      <div class="d-none d-lg-flex social-links align-items-center">
        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
      </div>
    </div>
  </div>

  <?php include 'pages/header.php'; ?>
  <?php if (!$parameter) include 'pages/hero.php'; ?>

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

<!-- Tambun Business Park Blok C12 Tambun Selatan
Kab Bekasi 17510 Jawa Barat Indonesia 
Telp 021-8909 5776 email: mmcpjk3@gmail.com  -->