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
$id_klinik = 1; //klinik MMC
$debug = '';
$unset = '<span class="kecil miring red consolas">unset</span>';
$null = '<span class="kecil miring red consolas">null</span>';
$null_gray = '<span class="f12 miring abu consolas">null</span>';
$hideit = 'hideit';
$today = date('Y-m-d');
$edit_section = '';

// set auto login
// $_SESSION['mmc_username'] = 'wh';

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
$no_wa = '';

if (isset($_SESSION['mmc_username'])) {
  $is_login = 1;
  $username = $_SESSION['mmc_username'];
}


# ================================================
# DATA PAGES AT
# ================================================
include 'data_pages.php';


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
include 'include/crud_icons.php';
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
  ?>
  <style>
    <?php if ($dm) {
      echo '.debug{display:inline; background:yellow; color: blue}';
    } else {
      echo '.debug{display:none;}';
    }
    ?>@media (max-width: 500px) {
      .desktop-only {
        display: none;
      }
    }

    section {
      padding-top: 140px;
      <?php if ($parameter) echo "padding-bottom: 15px;"; ?>
      /* min-height: 100vh; bahaya untuk section count */
    }

    #footer .footer-top {
      padding: 0;
    }
  </style>
</head>



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
    width: 280px;
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


<style>
  .btn_aksi {
    cursor: pointer;
  }

  .btn-edit-page {
    font-family: "Raleway", sans-serif;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 14px;
    letter-spacing: 1px;
    display: inline-block;
    padding: 12px 35px;
    margin-top: 10px;
    border-radius: 50px;
    transition: 0.5s;
    color: #fff;
    background: #844;
    text-shadow: none;
    text-align: center;
  }

  .btn-edit-page:hover {
    background: #3291e6;
    color: #ff0;
  }

  @media (max-width:400px) {
    .btn-edit-page {
      width: 100%;
    }
  }
</style>

<body>

  <?php include 'pages/topbar.php'; ?>
  <?php include 'pages/header.php'; ?>
  <?php include 'pages/save_settings_process.php'; ?>
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