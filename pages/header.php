<?php
$logo = $singkatan_sistem;
if ($header_logo) {
  $src = "assets/img/$header_logo";
  if (file_exists($src)) {
    $logo = "<a href='index.php' class='logo me-auto'><img src='$src' alt='$singkatan_sistem' class='img-fluid'></a>";
  } else {
    // warning logo missing
  }
}

// hide other info menu when parameter terisi
$li_anchor = '';
if (!$parameter) {
  $li_anchor = "
        <li><a class='nav-link scrollto' href='#why-us'>Keunggulan</a></li>
        <li><a class='nav-link scrollto' href='#about'>Tentang</a></li>
        <li><a class='nav-link scrollto' href='#produk'>Paket MCU</a></li>
        <li><a class='nav-link scrollto' href='#doctors'>Dokter dan Tim</a></li>
        <li><a class='nav-link scrollto' href='#contact'>Kontak</a></li>
  ";
}

?>
<header id="header" class="fixed-top">
  <div class="container d-flex align-items-center">

    <h1 class="logo me-auto"><a href="index.html"><?= $logo ?></a></h1>
    <!-- Uncomment below if you prefer to use an image logo -->
    <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

    <nav id="navbar" class="navbar order-last order-lg-0">
      <ul>
        <li><a class="nav-link scrollto active" href="?">Home</a></li>
        <?= $li_anchor ?>
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
        <li><a class="nav-link " href="?login">Login</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    <!-- <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span> Appointment</a> -->

  </div>
</header><!-- End Header -->