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
  $s = "SELECT a.*, 
  a.username,   
  a.nama as nama_user,  
  a.role 
  FROM tb_user a 
  JOIN tb_role b ON a.role=b.role 
  WHERE a.username='$username'";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if (mysqli_num_rows($q) == 0) {
    unset($_SESSION['mmc_username']);
    unset($_SESSION['mmc_role']);

    die("Data username: $username tidak ada.");
  }

  $d = mysqli_fetch_assoc($q);

  $id_user = $d['id'];
  $is_login = 1;
  $role = $d['role'];
  $nama_user = $d['nama_user'];

  $_SESSION['mmc_role'] = $role;

  $nama_user = ucwords(strtolower($nama_user));

  $src_profile = "assets/img/user/$id_user.jpg";
  if (!file_exists($src_profile)) $src_profile = $profile_na;
}
