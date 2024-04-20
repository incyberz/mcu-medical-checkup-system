<?php
if (isset($_GET['logout'])) {
  // delete cookie

  // echo '<pre>';
  // var_dump($_SESSION);
  // echo '</pre>';

  echo 'logging out...';

  unset($_SESSION['mmc_username']);
  unset($_SESSION['mmc_role']);


  echo '<script>location.replace("?")</script>';
  exit;
}
