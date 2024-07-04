<style>
  .menu_nakes {
    display: inline-block;
    padding: 10px !important;
    color: blue
  }
</style>
<?php
// hide other info menu when parameter terisi
$li_public = '';
$li_nakes = '';
// if (!$parameter) {
if ($username and $role != 'pasien') {
  $li_nakes = "
      <li><a class='nav-link gradasi-hijau bold menu_nakes' href='?pendaftaran'>Pendaftaran</a></li>
      <li><a class='nav-link gradasi-hijau bold menu_nakes' href='?pemeriksaan-cari_pasien'>Pemeriksaan</a></li>
    ";
} elseif (!$username) {
  $li_public = "
    <li><a class='nav-link scrollto' href='#why-us'>Keunggulan</a></li>
          <li><a class='nav-link scrollto' href='#about'>Tentang</a></li>
          <li><a class='nav-link scrollto' href='#produk'>Paket MCU</a></li>
          <li><a class='nav-link scrollto' href='#tim'>Dokter dan Tim</a></li>
          <li><a class='nav-link scrollto' href='#contact'>Kontak</a></li>
          <li><a class='nav-link scrollto' href='blog/'>Blog</a></li>
          ";
}
// } else

if (isset($_SESSION['mmc_username_master'])) {
  $li_public = "<li><a style='display:inline-block; padding: 10px; color:blue' class='nav-link scrollto gradasi-hijau bold' href='?login_as&unlog'>UNLOG</a></li>";
}

?>
<header id="header" class="fixed-top">
  <div class="container d-flex align-items-center">

    <h1 class="logo me-auto"><a href="index.html"><?= $img_header_logo ?></a></h1>
    <!-- Uncomment below if you prefer to use an image logo -->
    <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="nav-link scrollto active" href="?">Home</a></li>
        <?= $li_public ?>
        <?= $li_nakes ?>
        <!-- <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
          <ul>
            <li><a href="#">Drop Down 1</a></li>
            <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
              <ul>
                <li><a href="#">Deep Drop Down 1</a></li>
                <li><a href="#">Deep Drop Down 2</a></li>
                <li><a href="#">Deep Drop Down 3</a></li>
                <li><a href="#">Deep Drop Down 4</a></li>
                <li><a href="#">Deep Drop Down 5</a></li>
              </ul>
            </li>
            <li><a href="#">Drop Down 2</a></li>
            <li><a href="#">Drop Down 3</a></li>
            <li><a href="#">Drop Down 4</a></li>
          </ul>
        </li> -->
        <?php
        if ($username) {
          // login or login_as
          $Login_as = isset($_SESSION['mmc_username_master']) ? '<span class="blue bold">Login as</span>' : '';
          echo "
            <li class='dropdown'><a href='#'><span class='tebal darkblue'>$Login_as $username</span> <i class='bi bi-chevron-down'></i></a>
              <ul>
                <li><a href='#' onclick='onDev()'>Foto Profile</a></li>
                <li><a href='#' onclick='onDev()'>Biodata</a></li>
                <li><a href='?logout' onclick='return confirm(\"Logout?\")'>Logout</a></li>
              </ul>
            </li>
          ";
        } else {
          echo "<li><a class='nav-link ' href='?login'>Login</a></li>";
        }
        ?>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    <!-- <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span> Appointment</a> -->

  </div>
</header><!-- End Header -->