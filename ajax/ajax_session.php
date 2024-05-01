<?php
session_start();
$username = $_SESSION['mmc_username'] ?? die('Silahkan Login terlebih dahulu. Err#1');
$role = $_SESSION['mmc_role'] ?? die('Silahkan Login terlebih dahulu. Err#2');
include '../conn.php';
// include '../user_vars.php';

function only($sebagai)
{
  if ($sebagai != $_SESSION['mmc_role'])
    die("Maaf, hak akses Anda tidak sesuai. Silahkan login sebagai $sebagai !");


  // die("<div class='alert alert-danger mt-2'><span class=red>Maaf Anda tidak berhak mengakses fitur ini.</span> Silahkan <a href='?logout' onclick='return confirm(\"Yakin untuk Logout?\")'>relogin sebagai $sebagai</a></div>");
}
