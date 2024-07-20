<?php
$arr_penanda_gigi = [
  2 => ['O', 'Caries'],
  3 => ['@', 'Tambalan'],
  4 => ['X', 'Gigi Sudah tidak ada'],
  5 => ['H', 'Gigi belum tumbuh'],
  6 => ['E', 'Gigi goyang'],
  7 => ['^', 'Calculus'],
  8 => ['V', 'Radix'],
  1 => ['.', '(Tidak bertanda artinya normal)'],
];

$tr = '';
foreach ($arr_penanda_gigi as $rv) {
  $tr .= "<tr><td>$rv[0]</td><td>$rv[1]</td></tr>";
}
$tb_penanda_gigi = "<table>$tr</table>";

function simbol_gigi($kode)
{
  $arr = [
    2 => 'O',
    3 => '@',
    4 => 'X',
    5 => 'H',
    6 => 'E',
    7 => '^',
    8 => 'V',
    1 => '.',
  ];
  if (!array_key_exists($kode, $arr)) die("<span class=red>Kode gigi: $kode tidak ada dalam arr_penanda_gigi</span>");
  return $arr[$kode];
}
