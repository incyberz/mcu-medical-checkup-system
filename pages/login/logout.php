<?php
if (isset($_GET['logout'])) {
  // delete cookie

  // echo '<pre>';
  // var_dump($_SESSION);
  // echo '</pre>';

  echo 'logging out...';

  unset($_SESSION['mmc_username']);
  unset($_SESSION['mmc_role']);
  unset($_SESSION['mmc_username_master']);
  unset($_SESSION['mmc_role_master']);


  echo '<script>location.replace("?")</script>';
  exit;
}
