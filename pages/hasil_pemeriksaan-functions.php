<?php
function blok_hasil($kolom, $str_hasil, $is_consolas = false)
{
  if ($is_consolas) $str_hasil = "<span class=consolas>$str_hasil</span>";
  echo "
    <div class='blok_hasil'>
      <span class='column'>$kolom :</span>
      $str_hasil
    </div>
  ";
}


// function hasil2list($str_hasil)
// {
//   $tmp = explode('||', $str_hasil);
//   $li = '';
//   foreach ($tmp as $k => $v) {
//     if($v){

//     }
//   }
// }
