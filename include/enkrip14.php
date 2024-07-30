<?php
function enkrip14($id_perusahaan, $arr_multiplier = [
  1 => 1234,
  2 => 684,
  3 => 79,
  4 => 3,
  5 => 1,
  6 => 1,
  7 => 1,
])
{
  $len = strlen($id_perusahaan);
  $idz[0] = '000000' . (2024 + ($id_perusahaan * $arr_multiplier[$len]));
  $idz[0] = substr($idz[0], strlen($idz[0]) - 7);
  $idz[1] = $len . rand(100000, 999999);

  $z = $len;
  for ($i = 0; $i < 14; $i++) {
    $z .= substr($idz[$i % 2], floor($i / 2), 1);
  }
  return $z;
}

function dekrip14($txt14digit, $arr_multiplier = [
  1 => 1234,
  2 => 684,
  3 => 79,
  4 => 3,
  5 => 1,
  6 => 1,
  7 => 1,
])
{
  $len = substr($txt14digit, 0, 1);
  $idx[0] = '';
  $idx[1] = '';
  for ($i = 0; $i < 14; $i++) {
    $idx[$i % 2] .= substr($txt14digit, $i, 1);
  }
  return (intval($idx[1]) - 2024) / $arr_multiplier[$len];
}
