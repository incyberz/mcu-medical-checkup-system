<style>
  .menu_admin,
  .menu_nakes {
    display: inline-block;
    padding: 10px !important;
    color: blue
  }

  .badge {
    /* border: solid 1px #ccc; */
    display: inline-block;
    margin: 0 5px;
  }

  .badge-red {
    background: red;
  }

  .badge-blue {
    background: blue;
  }

  .badge-green {
    background: green;
  }
</style>
<?php
// hide other info menu when parameter terisi
$li_admin = '';
$li_public = '';
$li_nakes = '';

# ============================================================
# MENU INTERNAL USERS
# ============================================================
if ($username) {
  if ($role == 'pasien' || $role == 'pendaftar') {
  } else {
    $count_pasien_null = 0;
    $count_pasien_ready = 0;
    $count_pasien_sedang = 0;
    $count_pasien_selesai = 0;
    $count_pasien_unverif = 0;

    $s = "SELECT * FROM tb_header WHERE id_klinik=$id_klinik";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (!mysqli_num_rows($q)) {
      // auto create first
      $s = "INSERT INTO tb_header (
      id_klinik,
      count_pasien_null,
      count_pasien_ready,
      count_pasien_sedang,
      count_pasien_selesai,
      count_pasien_unverif,
      last_update
    ) VALUES (
      $id_klinik,
      $count_pasien_null,
      $count_pasien_ready,
      $count_pasien_sedang,
      $count_pasien_selesai,
      $count_pasien_unverif,
      '2020-1-1' -- initialisasi last_update pertama
    )";
      $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
      echo div_alert('success', 'Auto Create Header Count sukses.');
      jsurl();
    } else {
      $header = mysqli_fetch_assoc($q);
      $count_pasien_null = $header['count_pasien_null'];
      $count_pasien_ready = $header['count_pasien_ready'];
      $count_pasien_sedang = $header['count_pasien_sedang'];
      $count_pasien_selesai = $header['count_pasien_selesai'];
      $count_pasien_unverif = $header['count_pasien_unverif'];
      $last_update_header = $header['last_update'];
    }

    $count_pasien_null_show = !$count_pasien_null ? '' : "<span class='badge badge-red'>$count_pasien_null</span>";
    $count_pasien_ready_show = !$count_pasien_ready ? '' : "<span class='badge badge-red'>$count_pasien_ready</span>";
    $count_pasien_sedang_show = !$count_pasien_sedang ? '' : "<span class='badge badge-blue'>$count_pasien_sedang</span>";
    $count_pasien_selesai_show = !$count_pasien_selesai ? '' : "<span class='badge badge-green'>$count_pasien_selesai</span>";
    $count_pasien_unverif_show = !$count_pasien_unverif ? '' : "<span class='badge badge-red'>$count_pasien_unverif</span>";

    $li_nakes = "
      <li><a class='nav-link gradasi-hijau bold menu_nakes' href='?pendaftaran'>Pendaftaran $count_pasien_null_show</a></li>
      <li><a class='nav-link gradasi-hijau bold menu_nakes' href='?cari_pasien&aksi=mcu'>Pemeriksaan $count_pasien_ready_show $count_pasien_sedang_show</a></li>
      <li><a class='nav-link gradasi-hijau bold menu_nakes' href='?rekap_pemeriksaan'>Rekap $count_pasien_selesai_show $count_pasien_unverif_show</a></li>
    ";
  }
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

if ($username and ($role == 'admin' || $role == 'marketing')) {

  $menu_insho = '';
  if ($username == 'insho') {
    $menu_insho = "<li><a class='nav-link gradasi-merah bold menu_admin' href='?replacer_hasil_mcu'>Replacer Hasil MCU</a></li>";
  }

  $li_admin = "
    <li><a class='nav-link gradasi-hijau bold menu_admin' href='?progres'>Progres</a></li>
    <li class='dropdown '><a class='gradasi-hijau' href='#'><span>Manage</span> <i class='bi bi-chevron-down'></i></a>
      <ul>
        <li class='dropdown hideit'><a href='#'><span>Deep Drop Down</span> <i class='bi bi-chevron-right'></i></a>
          <ul>
            <li><a href='#'>Deep Drop Down 1</a></li>
            <li><a href='#'>Deep Drop Down 2</a></li>
            <li><a href='#'>Deep Drop Down 3</a></li>
            <li><a href='#'>Deep Drop Down 4</a></li>
            <li><a href='#'>Deep Drop Down 5</a></li>
          </ul>
        </li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?manage_paket'>Manage Paket</a></li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?manage_order'>Manage Order</a></li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?manage_pemeriksaan'>Manage Pemeriksaan</a></li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?manage_perusahaan'>Manage Perusahaan</a></li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?manage_cara_bayar'>Manage Cara Bayar</a></li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?paket_harga_perusahaan'>Paket Harga Perusahaan</a></li>
        <li><a class='nav-link gradasi-hijau bold menu_admin' href='?import'>Import Data</a></li>
        $menu_insho
      </ul>
    </li>
  ";
  if ($role == 'admin') {
    $li_admin .= "
      <li class='hideit'><a class='nav-link gradasi-hijau bold menu_admin' href='?manage_system'>System</a></li>
    ";
  }
}


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
        <?= $li_admin ?>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    <!-- <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span> Appointment</a> -->

  </div>
</header><!-- End Header -->