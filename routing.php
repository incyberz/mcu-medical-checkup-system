<?php
$page_tujuan = "pages/$parameter.php";
if (!isset($parameter)) die('Routing memerlukan parameter.');

// route home untuk pendaftar
if (!$parameter and $role == 'pendaftar' and $parameter != 'pendaftar_home') jsurl('?pendaftar_home');

// route home untuk pasien
if (!$parameter and $role == 'pasien' and $parameter != 'pasien_home') jsurl('?pasien_home');


$arr = [
  '' => 'pages/home',
  'login' => 'pages/login/login',
  'progres' => 'pages/progres/progres',
];

if ($parameter) echo '<section><div class=container>';
if (array_key_exists($parameter, $arr)) {
  // tujuan in array
  $tujuan = $arr[$parameter] . '.php';
  if (file_exists($tujuan)) {
    include $tujuan;
  } else {
    include 'pages/na.php';
  }
} else {
  // auto search pages
  if (file_exists($page_tujuan)) {
    include $page_tujuan;
  } else {
    include 'pages/na.php';
  }
}
if ($parameter) echo '</div></section>';
