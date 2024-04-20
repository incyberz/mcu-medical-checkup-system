<?php
$pesan_login = '';
$username = '';
$password = '';

if (isset($_POST['btn_login_wms'])) {
  $username = clean_sql($_POST['username']);
  $password = clean_sql($_POST['password']);

  if (strlen($username) > 20 || strlen($password) > 20) {
    $pesan_login = div_alert('danger', 'Maaf, format username dan password invalid. Silahkan coba kembali!');
  } else {
    $sql_password = $username == $password ? 'password is null' : "password=md5('$password')";
    // $sql_password = $username==$password ? 'password is null' : "password='$password'";
    // $sql_password = "password=md5('$password')";
    $s = "SELECT 1 from tb_user WHERE username='$username' and $sql_password";
    // echo $s;
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    if (mysqli_num_rows($q) == 1) {
      $d = mysqli_fetch_assoc($q);
      $_SESSION['mmc_username'] = $username;


      echo '<div style="padding: 100px 30px">Processing login...</div><script>location.replace("?")</script>';
      exit;
    } else {
      $pesan_login = div_alert('danger', 'Maaf, username dan password tidak tepat. Silahkan coba kembali!');
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
    <h3>MMC Login</h3>
    <?= $pesan_login ?>
    <div class="mt2">Anda dapat login sebagai pasien, dokter, petugas medis, atau sebagai administrator.</div>
    <hr>
    <form method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" minlength=2 maxlength=50 required id="username" name="username" value="<?= $username ?>">
      </div>

      <div class="form-group">
        <label for="username">Password</label>
        <input type="password" class="form-control" minlength=2 maxlength=50 required id="password" name="password" value="<?= $password ?>">
      </div>

      <div class="form-group">
        <button class='btn btn-primary btn-block' name='btn_login_wms'>Login</button>
      </div>
    </form>

    <!-- <div class="tengah mt3" data-aos="fade-up" data-aos-delay="300">Belum punya akun? Silahkan <a href="#" onclick='alert("Fitur Register belum tersedia. Silahkan hubungi developer.")'><b>Register</b></a></div> -->
    <!-- <div class="tengah mt3" data-aos="fade-up" data-aos-delay="300">Lupa password? <a href="#" onclick='alert("Fitur Reset Password belum tersedia. Silahkan hubungi developer.")'><b>Reset Password</b></a></div> -->

  </div>
</div>