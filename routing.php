<?php
$page_tujuan = "pages/$parameter.php";
if (!isset($parameter)) die('Routing memerlukan parameter.');

$arr = [
  '' => 'pages/home',
  'login' => 'pages/login/login',
];

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
