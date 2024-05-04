<?php
$as = $_GET['as'] ?? '';
if (!$as) {
  set_title('Login Sebagai...');
  set_h2('Pilih Role Login');
?>
  <div class="row">
    <div class="col-lg-4 mb2">
      <div class="card">
        <div class="card-body tengah">
          <p>Saya adalah pasien Medical Checkup.</p>
          <a class="btn btn-primary w-100" href="?login&as=pasien">Login as Pasien</a>

        </div>
      </div>
    </div>
    <div class="col-lg-4 mb2">
      <div class="card">
        <div class="card-body tengah">
          <p>Saya adalah Pendaftar (perwakilan dari perusahaan).</p>
          <a class="btn btn-primary w-100" href="?login&as=pendaftar">Login as Pendaftar</a>

        </div>
      </div>
    </div>
    <div class="col-lg-4 mb2">
      <div class="card">
        <div class="card-body tengah">
          <p>Saya adalah admin website ini.</p>
          <a class="btn btn-secondary w-100" href="?login&as=user">Login as Admin</a>

        </div>
      </div>
    </div>
  </div>
<?php
  exit;
} else {
  $as = strtolower($as);
  $caption_username = 'Username';
  if ($as == 'user') {
    $welcome_login = "Anda dapat login sebagai dokter, petugas medis, marketing, atau sebagai administrator.";
  } elseif ($as == 'pendaftar') {
    $welcome_login = "Untuk perwakilan perusahaan (HRD/Marketing/Pimpinan), silahkan Anda login dengan username dan password yang diberikan oleh Tim Marketing kami.";
  } elseif ($as == 'pasien') {
    $caption_username = 'Kode-MCU';
    $welcome_login = "Untuk pasien Medical Checkup, silahkan Anda login dengan Kode-MCU dan password yang diberikan oleh Perwakilan Perusahaan Anda (Marketing/HRD)";
  }
  $welcome_login .= "<div class='tengah f14 mt1'><a href='?login'>Pilih Role lainnya</a></div>";
}
$pesan_login = '';
$username = $_GET['username'] ?? '';
$password = '';

if (isset($_POST['btn_login_mmc'])) {
  $username = strip_tags(clean_sql($_POST['username']));
  $password = strip_tags(clean_sql($_POST['password']));

  if (strlen($username) > 20 || strlen($password) > 20) {
    $pesan_login = div_alert('danger', 'Maaf, format username dan password invalid. Silahkan coba kembali!');
  } else {
    $sql_password = $username == $password ? 'password is null' : "password=md5('$password')";
    $s = "SELECT 1 from tb_$as WHERE username='$username' and $sql_password";
    // echo $s;
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $username_exist = 0;
    if (mysqli_num_rows($q) == 1) {
      $username_exist = 1;
    } else {
      $pesan_login = div_alert('danger', "Maaf, username dan password tidak tepat. Silahkan coba kembali!");
    }

    if ($username_exist) {
      // $d = mysqli_fetch_assoc($q);
      $_SESSION['mmc_username'] = $username;

      if ($as != 'user') $_SESSION['mmc_role'] = $as;



      echo '<div style="padding: 100px 30px">Processing login...</div><script>location.replace("?")</script>';
      exit;
    }
  }
}




?>
<style>
  .full {
    display: flex;
    min-height: 60vh;
  }

  .form-login {
    max-width: 400px;
    margin: auto;
  }
</style>
<div class="full" data-aos='fade-up'>
  <div class="wadah gradasi-biru form-login p-4">
    <h3>MMC Login <span class="proper"><?= $as ?></span></h3>
    <?= $pesan_login ?>
    <div class="mt2"><?= $welcome_login ?></div>
    <hr>
    <form method="post">
      <div class="form-group">
        <label for="username"><?= $caption_username ?></label>
        <input type="text" class="form-control" minlength=2 maxlength=20 required id="username" name="username" value="<?= $username ?>">
      </div>

      <div class="form-group">
        <label for="username">Password</label>
        <input type="password" class="form-control" minlength=2 maxlength=50 required id="password" name="password" value="<?= $password ?>">
      </div>

      <div class="form-group">
        <button class='btn btn-primary btn-block' name='btn_login_mmc'>Login</button>
      </div>
    </form>

    <!-- <div class="tengah mt3" data-aos="fade-up" data-aos-delay="300">Belum punya akun? Silahkan <a href="#" onclick='alert("Fitur Register belum tersedia. Silahkan hubungi developer.")'><b>Register</b></a></div> -->
    <!-- <div class="tengah mt3" data-aos="fade-up" data-aos-delay="300">Lupa password? <a href="#" onclick='alert("Fitur Reset Password belum tersedia. Silahkan hubungi developer.")'><b>Reset Password</b></a></div> -->

  </div>
</div>