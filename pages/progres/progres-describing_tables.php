<?php
$s = "DESCRIBE tb_progres_h1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$colField = [];
$colType = [];
$colLength = [];
$colNull = [];
$colKey = [];
$colDefault = [];
while ($d = mysqli_fetch_assoc($q)) {
  if (
    $d['Field'] == 'id'
    || $d['Field'] == 'date_created'
  ) continue;
  array_push($colField, $d['Field']);
  array_push($colNull, $d['Null']);
  array_push($colKey, $d['Key']);
  array_push($colDefault, $d['Default']);

  if ($d['Type'] == 'timestamp') {
    $Type = 'timestamp';
    $Length = 19;
  } else {
    $pos = strpos($d['Type'], '(');
    $pos2 = strpos($d['Type'], ')');
    $len = strlen($d['Type']);
    $len_type = $len - ($len - $pos);
    $len_length = $len - ($len - $pos2) - $len_type - 1;

    $Type = substr($d['Type'], 0, $len_type);
    $Length = intval(substr($d['Type'], $pos + 1, $len_length));
  }

  array_push($colType, $Type);
  array_push($colLength, $Length);
}


$s = "DESCRIBE tb_progres_sub";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$subcolField = [];
$subcolType = [];
$subcolLength = [];
$subcolNull = [];
$subcolKey = [];
$subcolDefault = [];
while ($d = mysqli_fetch_assoc($q)) {
  if (
    $d['Field'] == 'id'
    || $d['Field'] == 'date_created'
  ) continue;
  array_push($subcolField, $d['Field']);
  array_push($subcolNull, $d['Null']);
  array_push($subcolKey, $d['Key']);
  array_push($subcolDefault, $d['Default']);

  if ($d['Type'] == 'timestamp') {
    $Type = 'timestamp';
    $Length = 19;
  } else {
    $pos = strpos($d['Type'], '(');
    $pos2 = strpos($d['Type'], ')');
    $len = strlen($d['Type']);
    $len_type = $len - ($len - $pos);
    $len_length = $len - ($len - $pos2) - $len_type - 1;

    $Type = substr($d['Type'], 0, $len_type);
    $Length = intval(substr($d['Type'], $pos + 1, $len_length));
  }

  array_push($subcolType, $Type);
  array_push($subcolLength, $Length);
}
