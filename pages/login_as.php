<?php
$unlog = $_GET['unlog'] ?? false;
if ($unlog !== false) {
  $_SESSION['mmc_username'] =   $_SESSION['mmc_username_master'];
  $_SESSION['mmc_role'] =   $_SESSION['mmc_role_master'];
  unset($_SESSION['mmc_username_master']);
  unset($_SESSION['mmc_role_master']);
  echo div_alert('success', 'Unlog as sukses.');
  jsurl('?', 3000);
  exit;
}

$role = $_GET['role'] ?? 'pasien';
$username = $_GET['username'] ?? die(div_alert('danger', "Page ini membutuhkan index [username]"));
set_h2("Login as $role", "Anda akan login sebagai $role. Klik <b class=darkblue>UNLOG</b> agar dapat kembali ke role asal.");
only('users');
if ($_SESSION['mmc_role'] == 'pasien') {
  session_destroy();
  echo div_alert('danger', "Role pasien tidak bisa melakukan Login As");
  jsurl('?', 5000);
}

# ============================================================
# VALIDITY CHECK
# ============================================================
if ($role == 'pasien') {
  $s = "SELECT 1 FROM tb_pasien WHERE username='$username'";
} else {
  $s = "SELECT 1 FROM tb_user WHERE username='$username' AND role='$role'";
}
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (!mysqli_num_rows($q)) die(div_alert('danger', "Username $username role $role tidak ada pada database user"));







echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
// exit;

// set master
$_SESSION['mmc_username_master'] = $_SESSION['mmc_username'];
$_SESSION['mmc_role_master'] = $_SESSION['mmc_role'];


// set new active role
$_SESSION['mmc_username'] = $username;
$_SESSION['mmc_role'] = $role;


echo '<pre>';
var_dump($_SESSION);
echo '</pre>';

jsurl('?', 5000);
