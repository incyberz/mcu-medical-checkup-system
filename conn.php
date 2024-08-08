<?php
# ============================================================
# DATABASE CONNECTION
# ============================================================
$online_version = $_SERVER['SERVER_NAME'] == 'localhost' ? 0 : 1;

$dm_db = 0;
if ($online_version) {
  $db_server = "localhost";
  $db_user = "pesc7881_insho";
  $db_pass = "hq'qC3D}+Hzj@TT";
  $db_name = "pesc7881_mcu";

  $db_user = "iotikain_insho";
  $db_pass = "hq'qC3D}+Hzj@TT";
  $db_name = "iotikain_mcu";

  $db_user = "mmcclini_admin";
  $db_pass = "MMC-Clinic2024";
  $db_name = "mmcclini_mmc";
  // $db_name = "mmcclini_mcu2";
} else {
  $db_server = "localhost";
  $db_user = "root";
  $db_pass = '';
  $db_name = "db_mcu";
  if (1) {
    echo "<div style='position:fixed; top:60px; left:0; font-weight:bold; z-index:9999; background:red; padding:5px'>DB-ONLINE MODE</div>";
    $dm_db = 1;
    $db_name = "db_online_mcu";
  }
}

$cn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($cn->connect_errno) {
  echo "Error Konfigurasi# Tidak dapat terhubung ke MySQL Server :: $db_name";
  exit();
}

date_default_timezone_set("Asia/Jakarta");

function erid($a)
{
  return "<span style=color:red>Error, index [$a] belum terdefinisi.</span>";
}
function kosong($a)
{
  return "<span style=color:red>Error, index [$a] tidak boleh kosong.</span>";
}

function clean_sql($a)
{
  $a = str_replace('\'', '`', $a);
  // $a = str_replace('"','`',$a);
  $a = str_replace(';', ',', $a);
  return $a;
}

function div_alert($a, $b)
{
  return "<div class='alert alert-$a'>$b</div>";
}
