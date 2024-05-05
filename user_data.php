<?php
$profile_na = "assets/img/profile_na.jpg";
if ($username == '') {
  // belum login
  $id_user = '';
  $is_login = '';
  $id_role = 0;
  $role = 'Pengunjung';
  $nama_user = '';
  $src_profile = $profile_na;
} else {
  //telah login
  $role = $_SESSION['mmc_role'] ?? 'user';

  $s = "SELECT a.*, 
  a.nama as nama_user 
  FROM tb_$role a 
  WHERE a.username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (!mysqli_num_rows($q)) {
    die("Data username: $username tidak ada.");
  } else {
    // extract user data
    $d = mysqli_fetch_assoc($q);

    $id_user = $d['id'];
    $is_login = 1;
    $nama_user = $d['nama_user'];
    $gender = $d['gender'] ?? '';

    if (!$role) {
      $_SESSION['mmc_role'] = $role;
      $role = $d['role'];
    }

    $nama_user = ucwords(strtolower($nama_user));

    $src_profile = "assets/img/user/$id_user.jpg";
    if (!file_exists($src_profile)) $src_profile = $profile_na;
  }
}
